<?php

function showMarks()
{
    $headings = array("Student ID", 'English', 'Nepali', 'Computer', 'Maths', 'Science', 'Result', 'Options');
    if (!$conn = mysqli_connect('localhost', 'root', '', 'grade')) {
        die("Could not connect to database " . mysqli_connect_error());
    }
    if (isset($_SESSION['user']['adminid'])) {
        $query = "SELECT * FROM student_marks
        WHERE studentid IN (SELECT studentid FROM student
                            WHERE class='$_GET[class]')";
    }
    if (isset($_SESSION['user']['studentid'])) {
        $query = "SELECT * FROM student_marks
                WHERE studentid IN (SELECT studentid FROM student
                                    WHERE class='{$_SESSION['user']['class']}')";
    }
    if (!$result = mysqli_query($conn, $query)) {
        die("Could not fetch data " . mysqli_error($conn));
    }
    echo "<div class='container'>
        <table cellspacing='0' align='center'>
            <tr><td>";
    echo implode("</td><td>", $headings);
    echo "</td></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>";
        echo implode("</td><td>", $row);
        echo "</td><td><a href='$_SERVER[PHP_SELF]?action=marksheet&studentid=$row[studentid]'>View Marksheet</a><br/>";
        if (isset($_SESSION['user']['adminid'])) {
            echo " <a href='$_SERVER[PHP_SELF]?action=editmarksheet&studentid=$row[studentid]'>Edit Marksheet</a>";
        }
        echo "</td></tr>";
    }
    echo "</table></div>";
}

function  editmarksheet($error){
    if (!$conn = mysqli_connect('localhost', 'root', '', 'grade')) {
        die("Could not connect to database " . mysqli_connect_error());
    }
    $query = "SELECT * FROM student_marks
                WHERE studentid = '$_GET[studentid]'";
    if (!$result = mysqli_query($conn, $query)) {
        die("Could not fetch data " . mysqli_error($conn));
    }
$defaults=mysqli_fetch_assoc($result);
$rollno=substr($defaults['studentid'],3,2);

    echo <<<FORM
    <div class='container'>
        <form action='' class='dataentry' method='POST'>
            <div class='formheader'>Enter Marks</div>
            <div class='error'>$error</div>
            <div class='rollinputsection'>
                <label for='rollno'>Roll Number</label>
                <input type='number' name='rollno' id='rollno' value="$rollno">
            </div>
            <div class='subjects'>
                <div class='inputheader'>Subjects</div>
                <label for='english'>English</label>
                <label for='nepali'>Nepali</label>
                <label for='computer'>Computer</label>
                <label for='maths'>Maths</label>
                <label for='science'>Science</label>
            </div>
            <div class='marks'>
                <div class='inputheader'>Marks</div>
                <input type='number' name='english' id='english' value="$defaults[english]">
                <input type='number' name='nepali' id='nepali' value="$defaults[nepali]">
                <input type='number' name='computer' id='computer' value="$defaults[computer]">
                <input type='number' name='maths' id='maths' value="$defaults[maths]">
                <input type='number' name='science' id='science' value="$defaults[science]">
            </div>
            <div class='inputsection'>
                <input type='submit' class='submit' name='submit' value='Update Marks'>
            </div>
            <div class='inputsection'>
                <a href='$_SERVER[PHP_SELF]'>Go Back</a>
            </div>
        </form>
    </div>
FORM;


}


function viewMarksheet()
{
    if (!$conn = mysqli_connect('localhost', 'root', '', 'grade')) {
        die("Could not connect to database " . mysqli_connect_error());
    }
    $detailsquery = "SELECT * FROM student
                WHERE studentid = '$_GET[studentid]'";
    $marksquery = "SELECT * FROM student_marks
                WHERE studentid = '$_GET[studentid]'";
    if (!$detailsresult = mysqli_query($conn, $detailsquery)) {
        die("Could not fetch data " . mysqli_error($conn));
    }
    if (!$marksresult = mysqli_query($conn, $marksquery)) {
        die("Could not fetch data " . mysqli_error($conn));
    }
    $studentdetails = mysqli_fetch_assoc($detailsresult);
    $marksdetails = mysqli_fetch_assoc($marksresult);
    echo <<<MARKSHEET
        <div class='container'>
            <div class='marksheet'>
                <div class='marksheetheader'>MARKSHEET</div>
                <div class='studentdetails'>
                    <div class='name'>
                        Name: $studentdetails[firstname] $studentdetails[lastname]
                    </div>
                    <div class='classandroll'>
                        <div>Class: $studentdetails[class]</div>
                        <div>Roll no: $studentdetails[rollno]</div>
                    </div>
                </div>
                <div class='marksandsubject'>
                    <div>Subject</div>
                    <div>Full Marks</div>
                    <div>Pass Marks</div>
                    <div>Obtained Marks</div>
    MARKSHEET;
    $total = 0;
    foreach (array_slice($marksdetails, 1, -1) as $subject => $marks) {
        echo "<div>" . ucfirst($subject) . "</div><div>100</div><div>40</div><div>$marks</div>";
        $total += $marks;
    }
    echo <<<MARKSHEET
                    <div>Total</div>
                    <div>500</div>
                    <div>200</div>
                    <div>$total</div>
                </div>
                <div class='finalresult'>
                    <div>Result</div>
                    <div>$marksdetails[result]</div>
                </div>
            </div>
        </div>
    MARKSHEET;
}
