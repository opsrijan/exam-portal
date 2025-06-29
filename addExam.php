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

    //add Exam
    if (isset($_POST['addExam'])){
        $title=$_POST['title'];
        $duration=$_POST['duration'];
        $fees=$_POST['fees'];
        $sql = "SELECT * FROM `exams`";
        $result = mysqli_query($con, $sql);
        $num = mysqli_num_rows($result);
        $sql= "INSERT INTO `exams` (exam_id, title, duration, fee) VALUES (".($num+1).", '".$title."', ".$duration.", ".$fees.")";
        $result = mysqli_query($con, $sql);
        echo "Exam added successfully.";
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
    <link rel="stylesheet" href="addExam.css">
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
                <form action="addExam.php" method='POST'>
                    <input type="text" name='title' placeholder='Enter title of exam'><br>
                    <input type="int" name='duration' placeholder='Enter duration'><br>
                    <input type="int" name='fees' placeholder='Enter fees'><br>
                    <button type='submit' name='addExam'>Add Exam</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>