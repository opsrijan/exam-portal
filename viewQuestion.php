<?php
    session_start();
    //Function to don't let anyone unauthorized enter.
    if (!isset($_SESSION['logger'])){
        session_destroy();
        header("Location: index.php");
        mysqli_close($con);
        exit();
    }
    //connect to database
    $con=mysqli_connect("localhost", "root", "", "examportal");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    if (isset($_POST['update'])){
        $sql = "UPDATE `questions` 
        SET question='".$_POST['question']."', 
        A='".$_POST['A']."', 
        B='".$_POST['B']."', 
        C='".$_POST['C']."', 
        D='".$_POST['D']."', 
        correct='".$_POST['correct']."'
        WHERE question_id=".$_POST['update'];
        $result=mysqli_query($con, $sql);
        echo "Updated successfully.";
    }
    if (isset($_POST['delete'])){
        $sql="UPDATE `questions` SET exam_id=-1 WHERE question_id=".$_POST['delete'];
        $result=mysqli_query($con, $sql);
        echo "Deleted Successfully.";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="examList.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="adminDashboard.php">Exam Portal</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="addAdmin.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Add another admin</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-agenda"></i>
                        <span>Exams</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="addExam.php" class="sidebar-link">Add an exam</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="manageExam.php" class="sidebar-link">Manage an exam</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a href="payment.php" class="sidebar-link">
                        <i class="lni lni-protection"></i>
                        <span>Payments</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="index.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>



        <div class="main p-3">
            <div class="text-center">
                <h1>Admin Dashboard</h1>
<?php
    if (isset($_SESSION['logger'])){
        $sql = "SELECT * FROM `admin` WHERE id =".$_SESSION['logger'];
        $result = mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
        echo "HELLO, ". $row['name'];
    }
?>
                
            </div>
            <h1>Questions of the exam 
            <?php
                $sql = "SELECT * FROM `exams` WHERE exam_id=".$_SESSION['currExam'];
                $result= mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                echo $row['title'];
            ?>
            :</h1>
            
            <?php
                $sql= "SELECT * FROM `questions` WHERE exam_id =".$_SESSION['currExam'];
                $result = mysqli_query($con, $sql);
                $sno=1;
                while ($row=mysqli_fetch_assoc($result)){
                    if (!isset($_POST['edit'])){
                        echo $sno." ".$row['question']."<br>A. ";
                        echo $row['A']."<br>B. ";
                        echo $row['B']."<br>C. ";
                        echo $row['C']."<br>D. ";
                        echo $row['D']."<br><br>";
                        echo "Correct: ".$row['correct']."<br>";
                        
                        echo "
                            <form action='viewQuestion.php' method='POST'>
                                <button type='submit' name='edit' value='".$row['question_id']."'>Edit Question</button>
                                <button type='submit' name='delete' value='".$row['question_id']."'>Delete Question</button>
                            </form>";
                    }
                    else if ($_POST['edit']!=$row['question_id']){
                        echo $sno." ".$row['question']."<br>A. ";
                        echo $row['A']."<br>B. ";
                        echo $row['B']."<br>C. ";
                        echo $row['C']."<br>D. ";
                        echo $row['D']."<br><br>";
                        echo "Correct: ".$row['correct']."<br>";
                        echo "
                            <form action='viewQuestion.php' method='POST'>
                                <button type='submit' name='edit' value='".$row['question_id']."'>Edit Question</button>
                                <button type='submit' name='delete' value='".$row['question_id']."'>Delete Question</button>
                            </form>";
                    }
                    else{
                        echo "<form action='viewQuestion.php' method='POST'>";
                        echo "Question: <input type='text' name='question' value='".$row['question']."'> <br>";
                        echo "Option A: <input type='text' name='A' value='".$row['A']."'> <br>";
                        echo "Option B: <input type='text' name='B' value='".$row['B']."'> <br>";
                        echo "Option C: <input type='text' name='C' value='".$row['C']."'> <br>";
                        echo "Option D: <input type='text' name='D' value='".$row['D']."'> <br>";
                        echo "correct option: <input type='text' name='correct' value='".$row['correct']."' <br>";
                        echo "  <button type='submit' name='update' value='".$row['question_id']."'>Update Question</button>
                                <button type='submit' name='delete' value='".$row['question_id']."'>Delete Question</button>";
                        echo "</form>";
                    }

                    echo "<hr>";
                    $sno++;
                }
            ?>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>