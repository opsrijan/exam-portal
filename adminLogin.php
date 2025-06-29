<?php
    session_start();
    //Destroy any running sessions
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
        $sql = "SELECT * FROM `admin` WHERE email = '" .$email. "' AND password = '" . $passwd . "'";
        $result = mysqli_query($con, $sql);
        $num=mysqli_num_rows($result);
        if($num==0){
            echo "<p>wrong email or password.</p>";
        }
        else{
            session_start();
            $row=mysqli_fetch_assoc($result);
            $_SESSION['logger'] = $row['id'];
            header("Location: adminDashboard.php");
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
    <h1>Admin Login</h1>
    <!-- Admin login form -->
    <form action="adminLogin.php" method="POST">
        <input type="text" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="passwd">
        <button type="submit" name="login">Login</button>
    </form>
    <a href="index.php">Student Login</a>
</body>
</html>
