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
    //register function
    if (isset($_POST['register'])){
        $name = $_POST['name'];
        $roll = $_POST['roll'];
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $sql = "SELECT * FROM `users` WHERE roll = ".$roll;
        $result = mysqli_query($con, $sql);
        $num=mysqli_num_rows($result);
        if($num==0){
            $sql = "INSERT INTO `users` (`roll`, `name`, `email`, `password`, `photo`) VALUES ('$roll', '$name', '$email', '$passwd', 'img.jpg');";
            $result = mysqli_query($con, $sql);
            echo "Resgistered Successfully";
            header("Location: index.php");
            exit();
        }
        else{
            echo "Roll no. already exists.";
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
    <h1>Student Register</h1>
    <!-- Student register form -->
    <form action="register.php" method="POST">
        <input type="text" name="roll" placeholder="Roll">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="passwd" placeholder="Password">
        <button type="submit" name="register">Register</button>
    </form>
    <a href="index.php">Back to Login page</a>
</body>
</html>
