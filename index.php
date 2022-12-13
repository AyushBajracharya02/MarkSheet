<?php
session_start();
if(isset($_SESSION['user']['adminid'])){
    header("Location: ../Content/Adminpage.php");
}
if(isset($_SESSION['user']['studentid'])){
    header("Location: ../Content/Homepage.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src='script.js' defer></script>
</head>

<body>
    <img src="./Images/logo.png" alt="Logo" class="logo">
    <?php
    if(isset($_POST['submit'])){
        sanitizeData();
        $validate = $_POST['accounttype'] == 'S' ? 'validateStudent' : 'validateAdmin';
        $showForm = $_POST['accounttype'] == 'S' ? 'showStudentForm' : 'showAdminForm';
        $login = $_POST['accounttype'] == 'S' ? 'loginStudent' : 'loginAdmin';
        if($error =  $validate()){
            $showForm($error);
        }
        else{
            $_SESSION['user'] = $login();
            if(isset($_SESSION['user']['adminid'])){
                header("Location: ./Content/Adminpage.php");
            }
            if(isset($_SESSION['user']['studentid'])){
                header("Location: ./Content/Homepage.php");
            }
        }
    }
    else{
        showStudentForm("");
    }
    ?>
</body>
<?php

function showStudentForm($error){
    echo <<<FORM
    <form action="" method="POST" class='container'>
        <div class="header">
            Login
        </div>
        <div class="inputsection" id="studentoradmin">
            <label for="studentid">Student ID</label>
            <input type="text" name="studentid">
        </div>
        <div class="inputsection">
            <label for="password">Password</label>
            <input type="password" name="password">
            <div class="error">$error</div>
        </div>
        <div class="inputsection">
            <label for="accounttype">Account Type</label>
            <select name="accounttype" id="accounttype">
                <option value="S">Student</option>
                <option value="A">Admin</option>
            </select>
        </div>
        <div class="inputsection">
            <input type="submit" class='submit' value="Log-in" name="submit">
            <a href=".\Register\index.php">Create Account</a>
        </div>
    </form>
    FORM;
}

function showAdminForm($error){
    echo <<<FORM
    <form action="" method="POST" class='container'>
        <div class="header">
            Login
        </div>
        <div class="inputsection" id="studentoradmin">
            <label for="adminid">Admin ID</label>
            <input type="text" name="adminid">
        </div>
        <div class="inputsection">
            <label for="password">Password</label>
            <input type="password" name="password">
            <div class="error">$error</div>
        </div>
        <div class="inputsection">
            <label for="accounttype">Account Type</label>
            <select name="accounttype" id="accounttype">
                <option value="A">Admin</option>
                <option value="S">Student</option>
            </select>
        </div>
        <div class="inputsection">
            <input type="submit" class='submit' value="Log-in" name="submit">
            <a href=".\Register\index.php">Create Account</a>
        </div>
    </form>
    FORM;
}

function sanitizeData(){
    foreach($_POST as $key => $value){
        $_POST[$key] = htmlentities(trim($value));
    }
}

function validateStudent(){
    $error = "";
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM student
                WHERE studentid = '$_POST[studentid]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not execute query ".mysqli_error($conn));
    }
    if(!$row = mysqli_fetch_assoc($result)){
        $error = "Invalid Id or Password.";
    }
    mysqli_close($conn);
    return $error;
}

function loginStudent(){
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM student
                WHERE studentid = '$_POST[studentid]' and password='$_POST[password]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not execute query ".mysqli_error($conn));
    }
    mysqli_close($conn);
    return mysqli_fetch_assoc($result);
}

function validateAdmin(){
    $error = "";
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM admin
                WHERE adminid = '$_POST[adminid]' and password='$_POST[password]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not execute query ".mysqli_error($conn));
    }
    if(!$row = mysqli_fetch_assoc($result)){
        $error = "Invalid Id or Password.";
    }
    mysqli_close($conn);
    return $error;
}

function loginAdmin(){
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM admin
                WHERE adminid = '$_POST[adminid]' and password='$_POST[password]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not execute query ".mysqli_error($conn));
    }
    mysqli_close($conn);
    return mysqli_fetch_assoc($result);
}
?>
</html>