<?php
    session_start();
    // Remove any running sessions as soon as u visit this page
    if (isset($_SESSION['logger'])){
        session_destroy();
    }
    //Connect to database
    $con=mysqli_connect("localhost", "root", "", "examportal");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    //Login function
    if (isset($_POST['login'])){
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $sql = "SELECT * FROM `users` WHERE email = '" .$email. "' AND password = '" . $passwd . "'";
        $result = mysqli_query($con, $sql);
        $num=mysqli_num_rows($result);
        if($num==0){
            echo "<p>wrong email or password.</p>";
        }
        else{
            session_start();
            $row=mysqli_fetch_assoc($result);
            $_SESSION['logger'] = $row['roll'];
            header("Location:  home.php");
            exit();
        }
        mysqli_close($con);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Student Login</h1>
    <!-- Login form starts -->
    <form action="index.php" method="POST">
        <input type="text" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="passwd">
        <button type="submit" name="login">Login</button>
    </form>
    <!-- Login form ends -->
    <a href="register.php">Don't have an account? Register Here</a>
    <a href="adminLogin.php">Admin Login</a>
</body>
</html>
