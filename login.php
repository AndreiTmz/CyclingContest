<?php
    $page_title = "Login";
    $section = "Login";
    $body_class = 'login-page';
    include("shared/db_connect.php");

    if (!empty($_SESSION['authenticated'])) {
        header('Location: admin.php');
        exit;
    }

    $user = "";
    $error = false;
    $loginError = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = is_string($_POST['user'] ?? null) ? trim($_POST['user']) : '';
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

        $statement = mysqli_prepare(
            $link,
            'SELECT password_hash FROM admin_accounts WHERE username = ?'
        );
        mysqli_stmt_bind_param($statement, 's', $user);
        mysqli_stmt_execute($statement);
        $password_hash = null;
        mysqli_stmt_bind_result($statement, $password_hash);
        $account_found = mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);

        if (!$account_found || !is_string($password_hash) || !password_verify($password, $password_hash)) {
            $error = true;
            $loginError = "Invalid credentials!";
		} else {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['authenticated'] = true;
            header('Location: admin.php');
            exit;
        }
    }

    include("shared/header.php");
?>

<h1 align="center">Only authorized persons can access this page!</h1>

<div class="login-layout">
    <div id="form">
        <form action="login.php" method="post">
            <div class="in-form">
                <div>
                    <div>
                        <label for="admin-user">Username</label>
                        <input id="admin-user" type="text" name="user" value="<?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter username" maxlength="20" autocomplete="username" autofocus required />
                    </div>
                </div>

                <hr />

                <div>
                    <div>
                        <label for="admin-password">Password</label>
                        <input id="admin-password" type="password" name="password" placeholder="Enter password" maxlength="100" autocomplete="current-password" required />
                    </div>
                </div>

                <?php if ($loginError !== '') { ?>
                    <p class="login-error" role="alert"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php } ?>

                <hr />

                <div class="form-actions">
                    <button type="submit" name="admin">Sign in</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
    include ("shared/footer.php");
?>


