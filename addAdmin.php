<?php
    session_start();
    //Connect to database
    $con=mysqli_connect("localhost", "root", "", "examportal");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    //register function
    if (isset($_POST['register'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $sql = "SELECT * FROM `admin`";
        $result = mysqli_query($con, $sql);
        $sql = "INSERT INTO `admin` (`name`, `email`, `password`) VALUES ('$name', '$email', '$passwd')";
        $result = mysqli_query($con, $sql);
        echo "Resgistered Successfully";
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
    <h1>Add a new admin</h1>
    <!-- Student register form -->
    <form action="addAdmin.php" method="POST">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="passwd" placeholder="Password">
        <button type="submit" name="register">Register</button>
    </form>
    <a href="adminDashboard.php">Dashboard</a>
</body>
</html>