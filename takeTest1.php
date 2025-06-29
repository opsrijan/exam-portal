<?php
session_start();

// Function to prevent unauthorized access
if (!isset($_SESSION['logger'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Connect to database
$con = mysqli_connect("localhost", "root", "", "examportal");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$remainingTime = $_SESSION['examEnd'] - time();

// Submit answer logic
if (isset($_POST['confirm']) Or $remainingTime <= 0) {
    $sql = "SELECT * FROM `questions` WHERE exam_id={$_SESSION['currExam']}";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $questionID=$row['question_id'];
        if (isset($_POST[$questionID])) {
            $selected = $_POST[$questionID];
    
            // Check if the answer already exists
            $sql2 = "SELECT * FROM `student_answers` WHERE user_id={$_SESSION['logger']} AND question_id={$questionID}";
            $result2 = mysqli_query($con, $sql2);
            $sql4 = "SELECT * FROM `student_answers`";
            $num = mysqli_num_rows(mysqli_query($con, $sql4));
            if (mysqli_num_rows($result2) == 0) {
                // Insert new answer
                $sql3 = "INSERT INTO `student_answers` (answer_id, user_id, question_id, selected) VALUES (".($num+1).",{$_SESSION['logger']}, {$questionID}, '{$selected}')";
            } else {
                // Update existing answer
                $sql3 = "UPDATE `student_answers` SET selected='{$selected}' WHERE user_id={$_SESSION['logger']} AND question_id={$questionID}";
            }
    
            mysqli_query($con, $sql3);
        }
    }
    //answers updated in sql

    $score=0;
    $roll = $_SESSION['logger'];
    $exam = $_SESSION['currExam'];
    $sql = "SELECT * FROM `evaluations`";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);
    $sql = "SELECT * FROM `questions` WHERE exam_id = ".$exam;
    $result = mysqli_query($con, $sql);
    while ($row=mysqli_fetch_assoc($result)){
        $qID = $row['question_id'];
        $correctAns = $row['correct'];
        $subsql = "SELECT * FROM `student_answers` WHERE question_id =".$qID." AND user_id=".$roll;
        $subresult = mysqli_query($con, $subsql);
        $subrow = mysqli_fetch_assoc($subresult);
        if ($subrow['selected']==$row['correct']){
            $score++;
        }
    }
    $sql = "INSERT INTO `evaluations` (eval_id, user_id, exam_id, score) VALUES (".($num+1).", ".$roll.", ".$exam.", ".$score.")";
    $res = mysqli_query($con, $sql);
    header("Location: examList.php");
    exit();
}

// Convert remaining time to minutes and seconds
$minutes = floor($remainingTime / 60);
$seconds = $remainingTime % 60;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="examList.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button"><i class="lni lni-grid-alt"></i></button>
                <div class="sidebar-logo"><a href="home.php">Exam Portal</a></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item"><a href="profile.php" class="sidebar-link"><i class="lni lni-user"></i> <span>Profile</span></a></li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false">
                        <i class="lni lni-agenda"></i> <span>Exams</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="examList.php" class="sidebar-link">Your Exams</a></li>
                        <li class="sidebar-item"><a href="examRegistration.php" class="sidebar-link">Exam Registration</a></li>
                    </ul>
                </li>
                <li class="sidebar-item"><a href="studentPayment.php" class="sidebar-link"><i class="lni lni-protection"></i> <span>Your Payments</span></a></li>
                <li class="sidebar-item"><a href="sw.php" class="sidebar-link"><i class="lni lni-popup"></i> <span>Strengths / Weakness</span></a></li>
            </ul>
            <div class="sidebar-footer"><a href="index.php" class="sidebar-link"><i class="lni lni-exit"></i> <span>Logout</span></a></div>
        </aside>

        <!-- Exam Timer -->
        <div class="p-3">
            <h2>Exam Timer</h2>
            <p>Time Remaining: <span id="timer"><?php echo "$minutes:$seconds"; ?></span></p>
            
        </div>

        <div class="main p-3">
            <div class="text-center">
                <h1>Student Dashboard</h1>
                <?php
                if (isset($_SESSION['logger'])) {
                    $sql = "SELECT * FROM `users` WHERE roll={$_SESSION['logger']}";
                    $result = mysqli_query($con, $sql);
                    $profile = mysqli_fetch_assoc($result);
                    echo "<h3>Hello, " . $profile['name'] . "</h3>";
                }
                ?>
            </div>

            <!-- Display Questions -->
            <?php
            if (isset($_SESSION['currExam'])) {
                $sql = "SELECT * FROM `questions` WHERE exam_id={$_SESSION['currExam']}";
                $result = mysqli_query($con, $sql);
                $sno = 1;
                echo "<form id='exam-form' action='takeTest1.php' method='POST'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<h5>{$sno}. {$row['question']}</h5>";
                    echo "<input type='radio' name='{$row['question_id']}' value='A'> {$row['A']}<br>";
                    echo "<input type='radio' name='{$row['question_id']}' value='B'> {$row['B']}<br>";
                    echo "<input type='radio' name='{$row['question_id']}' value='C'> {$row['C']}<br>";
                    echo "<input type='radio' name='{$row['question_id']}' value='D'> {$row['D']}<br>";
                    
                    $sno++;
                }
                echo "<button type='submit' name='confirm'>Submit Answer</button><hr>";
                echo "</form>";
            }
            ?>
        </div>
    </div>

    <!-- JavaScript Countdown Timer -->
    <script>
        function startTimer(duration) {
            let timer = duration;
            let display = document.getElementById("timer");

            let countdown = setInterval(function () {
                let minutes = Math.floor(timer / 60);
                let seconds = timer % 60;
                display.textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
                
                if (timer <= 0) {
                    clearInterval(countdown);
                    alert("Time is up! Auto-submitting the exam.");
                    document.getElementById("exam-form").submit();
                }
                timer--;
            }, 1000);
        }

        window.onload = function () {
            startTimer(<?php echo $remainingTime; ?>);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
