<?php
    $page_title="Register";
    $section="Registration";
    $body_class = 'registration-page';
    include("shared/db_connect.php");
    include("shared/enums.php");

	$error = false;
	$registration_error = "";
	$validation_errors = [];
    $first_name = "";
    $last_name = "";
    $date_of_birth = "";
    $email = "";
    $phone_number = "";
    $gender = "";
    $selected_category_ids = [];
    $categories = [];
    $raw_categories = [];
    $birth_date = null;

    $categories_query = "SELECT id, name FROM categories ORDER BY name";
    $categories_result = mysqli_query($link, $categories_query);
    $available_categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
    $available_category_ids = array_map('intval', array_column($available_categories, 'id'));
            
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register']))
    {		
        $read_string = static function (string $key): string {
            $value = $_POST[$key] ?? '';
            return is_string($value) ? trim($value) : '';
        };
        $first_name = $read_string('first_name');
        $last_name = $read_string('last_name');
        $date_of_birth = $read_string('date_of_birth');
        $email = $read_string('email');
        $phone_number = $read_string('phone_number');
        $gender = $read_string('gender');
        $raw_categories = $_POST['categories'] ?? [];
        if (is_array($raw_categories)) {
            foreach ($raw_categories as $category_id) {
                if (is_string($category_id) || is_int($category_id)) {
                    $selected_category_ids[] = (string)$category_id;
                }
            }
        }

        $validate_name = static function (string $value): string {
            if ($value === '') {
                return 'This field is required.';
            }
            if (preg_match('/^[\p{L}][\p{L} \'\-]{2,49}$/u', $value) !== 1) {
                return 'Enter 3 to 50 letters, spaces, hyphens, or apostrophes.';
            }
            return '';
        };

        $validation_errors['first_name'] = $validate_name($first_name);
        $validation_errors['last_name'] = $validate_name($last_name);

        if ($date_of_birth === '') {
            $validation_errors['date_of_birth'] = 'Date of birth is required.';
        } else {
            $birth_date = DateTimeImmutable::createFromFormat('!Y-m-d', $date_of_birth);
            $date_errors = DateTimeImmutable::getLastErrors();
            if (!$birth_date || ($date_errors && ($date_errors['warning_count'] > 0 || $date_errors['error_count'] > 0)) ||
                $birth_date->format('Y-m-d') !== $date_of_birth || $birth_date > new DateTimeImmutable('today')) {
                $birth_date = null;
                $validation_errors['date_of_birth'] = 'Enter a real date that is not in the future.';
            }
        }

        if ($email === '') {
            $validation_errors['email'] = 'Email is required.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 254) {
            $validation_errors['email'] = 'Enter a valid email address.';
        } else {
            $email_statement = mysqli_prepare($link, 'SELECT id FROM competitors WHERE email = ?');
            mysqli_stmt_bind_param($email_statement, 's', $email);
            mysqli_stmt_execute($email_statement);
            mysqli_stmt_store_result($email_statement);
            $email_exists = mysqli_stmt_num_rows($email_statement) > 0;
            mysqli_stmt_close($email_statement);
            if ($email_exists) {
                $validation_errors['email'] = 'This email is already in use.';
            }
        }

        if ($phone_number === '') {
            $validation_errors['phone_number'] = 'Phone number is required.';
        } elseif (preg_match('/^[0-9]{7,20}$/', $phone_number) !== 1) {
            $validation_errors['phone_number'] = 'Enter a phone number using 7 to 20 digits.';
        }

        if (!in_array($gender, ['m', 'f'], true)) {
            $validation_errors['gender'] = 'Select a gender.';
        }

        if (!is_array($raw_categories) || $raw_categories === []) {
            $validation_errors['categories'] = 'Select at least one category.';
        } else {
            foreach ($raw_categories as $category_id) {
                if (!is_string($category_id) && !is_int($category_id)) {
                    $validation_errors['categories'] = 'Select valid categories.';
                    break;
                }
                $validated_id = filter_var($category_id, FILTER_VALIDATE_INT);
                if ($validated_id === false || $validated_id < 1 || !in_array($validated_id, $available_category_ids, true)) {
                    $validation_errors['categories'] = 'Select valid categories.';
                    break;
                }
                if (!in_array($validated_id, $categories, true)) {
                    $categories[] = $validated_id;
                }
            }
        }

        $validation_errors = array_filter($validation_errors);
        $error = $validation_errors !== [];

        if (!$error)
        {
            $gender_for_database = strtoupper($gender);
            $competitor_age = $birth_date->diff(new DateTimeImmutable('today'))->y;
            if ($competitor_age < 18) {
                $age_category = age_category::JUNIOR->name;
            } elseif ($competitor_age < 25) {
                $age_category = age_category::INTERMEDIATE->name;
            } else {
                $age_category = age_category::SENIOR->name;
            }

            try {
                mysqli_begin_transaction($link);
                $competitor_statement = mysqli_prepare(
                    $link,
                    'INSERT INTO competitors (first_name, last_name, date_of_birth, email, phone_number, gender) VALUES (?, ?, ?, ?, ?, ?)'
                );
                mysqli_stmt_bind_param(
                    $competitor_statement,
                    'ssssss',
                    $first_name,
                    $last_name,
                    $date_of_birth,
                    $email,
                    $phone_number,
                    $gender_for_database
                );
                mysqli_stmt_execute($competitor_statement);
                $competitor_id = mysqli_insert_id($link);
                mysqli_stmt_close($competitor_statement);

                $result_statement = mysqli_prepare(
                    $link,
                    'INSERT INTO results (competitor_id, category_id, finish_position, status, age_category) VALUES (?, ?, NULL, ?, ?)'
                );
                $status = result_status::REGISTERED->name;
                mysqli_stmt_bind_param($result_statement, 'iiss', $competitor_id, $category_id, $status, $age_category);
                foreach ($categories as $category_id) {
                    mysqli_stmt_execute($result_statement);
                }
                mysqli_stmt_close($result_statement);
                mysqli_commit($link);

                $_SESSION['registered_competitor_id'] = $competitor_id;
                header('Location: registration_completed.php');
                exit;
            } catch (mysqli_sql_exception $exception) {
                mysqli_rollback($link);
                error_log($exception->getMessage());
                if ((int)$exception->getCode() === 1062) {
                    $error = true;
                    $validation_errors['email'] = 'This email is already in use.';
                } else {
                    $registration_error = "Registration could not be completed. Please try again.";
                }
            }
        }
    }

    include("shared/header.php");
?>

<h2 align="center">Register</h2>
<div class="registration-layout">
<img src="images/register.jpg" class="register_img" />
<div id="form">	
    <form action="register.php" method="post">		
        <div class="in-form">		
            <?php
                $given_first_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
                $given_last_name = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
                $given_date_of_birth = htmlspecialchars($date_of_birth, ENT_QUOTES, 'UTF-8');
                $given_email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                $given_phone_number = htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8');
            ?>
                
            <div>
                <div>
                    <span><span></span>First name*</span>
                    <input type="text" name="first_name" class="right" placeholder="John" maxlength="50" value="<?php echo $given_first_name; ?>" autocomplete="given-name" required autofocus />
                </div>
                <span class="<?php echo isset($validation_errors['first_name']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
                
            <hr />
                
            <div class="">
                <div class=""> 
                    <span class=""><span class=""></span>Last name*</span>
                    <input type="text" name="last_name" class="right" placeholder="Doe" maxlength="50" value="<?php echo $given_last_name;?>" autocomplete="family-name" required />
                </div>
                <span class="<?php echo isset($validation_errors['last_name']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
                
            <hr />
            
            <div class="">
                <div class="">
                    <span class=""><span class=""></span>Date of birth*</span>
                    <input type="date" name="date_of_birth" class="right" lang="en-GB" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $given_date_of_birth;?>" required />
                </div>
                <span class="<?php echo isset($validation_errors['date_of_birth']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <hr />
                
            <div class="">
                <div class="">
                    <span class=""><span class=""></span>Email*</span>
                    <input type="email" name="email" class="right" placeholder="john.doe@example.com" maxlength="254" value="<?php echo $given_email;?>" autocomplete="email" required />
                </div>
                <span class="<?php echo isset($validation_errors['email']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div >

            <hr />
                
            <div class="">
                <div class="">
                    <span class=""><span class=""></span>Phone number*</span>
                    <input type="tel" name="phone_number" class="right" placeholder="0788100100" maxlength="20" pattern="[0-9]{7,20}" inputmode="numeric" value="<?php echo $given_phone_number;?>" autocomplete="tel" required />
                </div>
                <span class="<?php echo isset($validation_errors['phone_number']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
                
            <hr />
                
            <div>
                <div class="gender segmented-control">
                    <label>
                        <input type="radio" name="gender" value="m" <?php if ($gender === "m") { ?> checked <?php } ?> required />
                        <span>Male</span>
                    </label>
                    <label>
                        <input type="radio" name="gender" value="f" <?php if ($gender === "f") { ?> checked <?php } ?> />
                        <span>Female</span>
                    </label>
                </div>
                <span class="<?php echo isset($validation_errors['gender']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['gender'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <hr />

            <div>
                <div class="category-field">
                    <span class=""><span class=""></span>Category*</span>
                    <div class="category-tiles" role="group" aria-label="Categories">
                        <?php
                            foreach ($available_categories as $row) {
                                $category_id_value = (string)$row['id'];
                                $category_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                        ?>
                            <label>
                                <input type="checkbox" name="categories[]" value="<?php echo (int)$row['id']; ?>" <?php if (in_array($category_id_value, $selected_category_ids, true)) { ?>checked <?php } ?> />
                                <span><?php echo $category_name; ?></span>
                            </label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <span class="<?php echo isset($validation_errors['categories']) ? 'error' : ''; ?>"><?php echo htmlspecialchars($validation_errors['categories'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <hr />
                
            <p align="center"> *Fields marked with an asterisk are required!</p>
                
            <div class="form-actions">
                <button type="submit" name="register">Let's ride!</button>
            </div>
                
            <div class="success">
                <?php if ($registration_error !== '') { ?>
                    <div class="error"><?php echo htmlspecialchars($registration_error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php } ?>
                <?php
                    if ( isset($errMSG) ) 
                    {
                    ?>
                        <div>
                            <div>
                                <span></span> <?php echo $errMSG; ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
            </div>
                
        </div>		
    </form>
</div>

<div class="viewMedia">
    <h3 align="center">Media</h3>
    <ul>
        <li></li>
        <li><h3 align="center"><a href="photos.php"> View photos </a></h3></li>
        <li><h3 align="center"><a href="videos.php"> View videos </a></h3></li>
        <li><h3 align="center"><a href="routes.php"> View routes </a></h3></li>
    </ul>
</div>
</div>

<?php include("shared/footer.php");?>
