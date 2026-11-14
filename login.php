<?php
    $page_title = "Login";
    $section = "Login";
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

<div id="form" style="margin:10px 0 10px 270px;">
    <form action="login.php" method="post">
        <div class="in-form">
            <div>
                <div>
                    <span>Username</span>
                    <input type="text" value="" name="user" class="right" placeholder="Enter username" maxlength="20" autofocus />
                </div>						
            </div>
            
            <hr />
            
            <div>
                <div>
                    <span>Password</span>
                    <input type="password" value="" name="password" class="right" placeholder="Enter password" maxlength="100" />
                </div>	
            </div>
            
            <hr />
            
            <span <?php if($error==true) echo 'class="loginError"';?>><?php echo $loginError; ?> </span>
            
            <div class="">
                <button type="submit" name="admin"> Login</button>
            </div>
        </div>
    </form>
</div>

<?php
    include ("shared/footer.php");
?>


