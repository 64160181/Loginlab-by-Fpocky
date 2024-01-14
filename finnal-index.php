<?php
include('config.php');
$msg = '';

$ip = $_SERVER["REMOTE_ADDR"];
mysqli_query($dbcon, "INSERT INTO `ip` (`address` ,`timestamp`)VALUES ('$ip',CURRENT_TIMESTAMP)");
$result = mysqli_query($dbcon, "SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > (now() - interval 1 second)");
$count = mysqli_fetch_array($result, MYSQLI_NUM);

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userstatus = mysqli_query($dbcon, "SELECT `username`,`status` FROM users WHERE username = '$username'");
    $userstatus_row = mysqli_fetch_assoc($userstatus);

    if ($userstatus_row['status'] == 'N') {
        $msg = "Your account is locked";
    } elseif ($count[0] >= 3) {
        mysqli_query($dbcon, "UPDATE users SET `status` = 'N' WHERE username = '$username'");
        $msg = "Too many failed login attempts. Your account is locked";
    } else {
        $stmt = $dbcon->prepare("SELECT username FROM users WHERE username=? AND password=SHA1(?)");
        $stmt->bind_param("ss", $username, $password);
        
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "Login successful";
        } else {
            $msg = "Place enter valid login details.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Template</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="assets/images/login.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="assets/images/logo.svg" alt="logo" class="logo">
                            </div>
                            <p class="login-card-description">Sign into your account</p>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-group">
                                    <label for="username" class="sr-only">Email</label>
                                    <input type="text" name="username" id="username" class="form-control"
                                        placeholder="your username">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="***********">
                                </div>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit">
                            </form>
                            <p class="login-card-footer-text">
                                <?php echo $msg; ?>
                            </p>
                            <nav class="login-card-footer-nav">
                                <a href="#!">Terms of use.</a>
                                <a href="#!">Privacy policy</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>