<?php
    session_start();
    //Connect to database
    $con=mysqli_connect("localhost", "root", "", "examportal");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    //add Question
    if (isset($_POST['addQuestion'])){
        $sql = "SELECT * FROM `questions`";
        $result= mysqli_query($con, $sql);
        $num= mysqli_num_rows($result);

        $sql= "INSERT INTO `questions` (exam_id, question, A, B, C, D, correct)
        VALUES (".$_SESSION['currExam'].", '".$_POST['question']."', '".$_POST['A']."', '".$_POST['B']."', '".$_POST['C']."', '".$_POST['D']."', '".$_POST['correct']."')";
        $result= mysqli_query($con, $sql);
        echo "Question added successfully.";
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
    <h1>Add a new question in 
    <?php
        $sql="SELECT * FROM `exams` WHERE exam_id=".$_SESSION['currExam'];
        $result = mysqli_query($con, $sql);
        $row= mysqli_fetch_assoc($result);
        echo $row['title'];
    ?>
    </h1>
    <!-- Student register form -->
    <form action="addQuestion.php" method="POST">
        <input type="text" name="question" placeholder="Enter the question" required>
        <input type="text" name="A" placeholder="Option A" required>
        <input type="text" name="B" placeholder="Option B" required>
        <input type="text" name="C" placeholder="Option C" required>
        <input type="text" name="D" placeholder="Option D" required>
        <input type="text" name="correct" placeholder="A/B/C/D" required>
        <button type="submit" name="addQuestion">Add this question</button>
    </form>
    <a href="adminDashboard.php">Dashboard</a>
</body>
</html>