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
    //view results
    if (isset($_POST['viewResults'])){
        $_SESSION['currExam'] = $_POST['viewResults'];
        header("Location: viewResult.php");
        exit();
    }
    //take test
    if (isset($_POST['takeTest'])){
        $_SESSION['currExam'] = $_POST['takeTest'];
        $sql = "SELECT duration FROM `exams` WHERE exam_id = ".$_POST['takeTest'];
        $result = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_assoc($result)){
            $_SESSION['examStart'] = time();
            $_SESSION['examEnd'] = time() + (60 * $row['duration']);
        }
        header("Location: takeTest1.php");
        exit();
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
    <link rel="stylesheet" href="examList.css">
</head>

<body>
    <div class="wrapper">
        <!-- sidebar starts -->
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="home.php">Exam Portal</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="profile.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
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
                            <a href="examList.php" class="sidebar-link">Your Exams</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="examRegistration.php" class="sidebar-link">Exam Registration</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a href="studentPayment.php" class="sidebar-link">
                        <i class="lni lni-protection"></i>
                        <span>Your Payments</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="sw.php" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Strenths / Weakness</span>
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
        <!-- sidebar ends -->


        <div class="main p-3">
            <div class="text-center">
                <h1>Student Dashboard</h1>
                <?php
                //print hello user
                    if (isset($_SESSION['logger'])){
                        $sql = "SELECT * FROM `users` WHERE roll =".$_SESSION['logger'];
                        $result = mysqli_query($con, $sql);
                        $row=mysqli_fetch_assoc($result);
                        echo "HELLO, ". $row['name'];
                    }
                ?>
                
            </div>

            <h1>Your Exams:</h1>
            <table>
                <thead>
                <tr>
                    <th>S. No.</th>
                    <th>Name</th>
                    <th>Duration</th>
                    <th>Fee</th>
                </tr>
                </thead>
                <tbody>
            <?php
                //table body
                $sql= "SELECT * FROM `exams` WHERE exam_id IN ( SELECT exam_id FROM `payments` WHERE user_id=".$_SESSION['logger'].")";
                $result = mysqli_query($con, $sql);
                $sno=1;
                while ($row=mysqli_fetch_assoc($result)){
                    echo "
                        <tr>
                        <td>".$sno."</td>
                        <td>".$row['title']."</td>
                        <td>".$row['duration']."</td>
                        <td>".$row['fee']."</td>
                    ";
                    $sql2= "SELECT * FROM `evaluations` WHERE user_id=".$_SESSION['logger']." AND exam_id=".$row['exam_id'];
                    $result2 = mysqli_query($con, $sql2);
                    $num = mysqli_num_rows($result2);
                    if ($num==1){
                        echo "<td>
                        <form action='examList.php' method='POST'>
                            <button type='submit' name='viewResults' value='".$row['exam_id']."'>View results</button>
                        </form>
                        </td></tr>";
                    }else{
                        echo "<td>
                        <form action='examList.php' method='POST'>
                            <button type='submit' name='takeTest' value='".$row['exam_id']."'>Take test</button>
                        </form>
                        </td></tr>";
                    }
                    $sno++;
                }
            ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>