<?php
session_start();
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
    <img src="../Images/logo.png" alt="Logo" class='logo' />
    <?php
    if(isset($_POST['submit'])){
        sanitizeData();
        $validate = $_POST['accounttype'] == 'S' ? 'validateStudent' : 'validateAdmin';
        $showForm = $_POST['accounttype'] == 'S' ? 'showStudentForm' : 'showAdminForm';
        $register = $_POST['accounttype'] == 'S' ? 'registerStudent' : 'registerAdmin';
        if($errors = array_filter($validate())){
            $errors = $validate();
            $showForm($errors);
        }
        else{
            $_SESSION['user'] = $register();
            header("Location: ..\index.php");
        }
    }
    else{
        showStudentForm(array('firstname'=>"",'lastname'=>'','class'=>'','accounttype'=>'','rollno'=>'','password'=>'','id'=>''));
    }
    ?>
</body>
<?php

function showAdminForm($errors){
    echo <<<FORM
    <form class='container' action="" method="post">
        <div class='header'>Create Account</div>
        <div class="inputsection">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="">
            <div class="error">$errors[firstname]</div>
        </div>
        <div class="inputsection" id='classorposition'>
            <label for="position">Position</label>
            <select name="position" id="">
                <option value="Principal">Principal</option>
                <option value="Vice-Principal">Vice-Principal</option>
                <option value="Coordinator">Coordinator</option>
                <option value="Teacher">Teacher</option>
            </select>
            <div class="error">$errors[position]</div>
        </div>
        <div class="inputsection">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="">
            <div class="error">$errors[lastname]</div>
        </div>
        <div class="inputsection">
            <label for="accounttype">Account Type</label>
            <select name="accounttype" id="accounttype">
                <option value="A">Admin</option>
                <option value="S">Student</option>
            </select>
            <div class="error">$errors[accounttype]</div>
        </div>
        <div class="inputsection" id='rollnoorcontact'>
            <label for="contact">Contact</label>
            <input type="tel" name="contact" id="">
            <div class="error">$errors[contact]</div>
        </div>
        <div class="inputsection">
            <input type="submit" class="submit" name="submit" value="Create Account">
            <div class="error">$errors[id]</div>
        </div>
        <div class="inputsection">
            <label for="password">Password</label>
            <input type="password" name="password" id="">
            <div class="error">$errors[password]</div>
        </div>
        <div class="inputsection">
            <a href="../index.php">Log-in</a>
        </div>
    </form>
    FORM;
}

function showStudentForm($errors){
    echo <<<FORM
    <form class='container' action="" method="post">
        <div class='header'>Create Account</div>
        <div class="inputsection">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="">
            <div class="error">$errors[firstname]</div>
        </div>
        <div class="inputsection" id='classorposition'>
            <label for="class">Class</label>
            <select name="class" id="">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <div class="error">$errors[class]</div>
        </div>
        <div class="inputsection">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="">
            <div class="error">$errors[lastname]</div>
        </div>
        <div class="inputsection">
            <label for="accounttype">Account Type</label>
            <select name="accounttype" id="accounttype">
                <option value="S">Student</option>
                <option value="A">Admin</option>
            </select>
            <div class="error">$errors[accounttype]</div>
        </div>
        <div class="inputsection" id='rollnoorcontact'>
            <label for="rollno">Roll Number</label>
            <input type="number" name="rollno" id="">
            <div class="error">$errors[rollno]</div>
        </div>
        <div class="inputsection">
            <input type="submit" class="submit" name="submit" value="Create Account">
            <div class="error">$errors[id]</div>
        </div>
        <div class="inputsection">
            <label for="password">Password</label>
            <input type="password" name="password" id="">
            <div class="error">$errors[password]</div>
        </div>
        <div class="inputsection">
            <a href="../index.php">Log-in</a>
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
    $errors = array('firstname'=>"",'lastname'=>'','class'=>'','accounttype'=>'','rollno'=>'','password'=>'','id'=>'');
    if(strlen($_POST['firstname'])<2 || strlen($_POST['firstname'])>32){
        $errors['firstname'] = "First Name must be between 2 and 32 characters";
    }
    if(strlen($_POST['lastname'])<2 || strlen($_POST['lastname'])>32){
        $errors['lastname'] = "Last Name must be between 2 and 32 characters";
    }
    if(!in_array($_POST['rollno'],range(1,30))){
        $errors['rollno'] = "Only 30 students in 1 class";
    }
    if(!in_array($_POST['class'],range(1,10))){
        $errors['class'] = "Only class 1 to 10 available";
    }
    if(strlen($_POST['password'])<8 || strlen($_POST['password'])>32){
        $errors['password'] = "Password must be between 8 and 32 characters";
    }
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM student
                WHERE studentid = '$_POST[class]-$_POST[rollno]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not execute query ".mysqli_error($conn));
    }
    if($row = mysqli_fetch_assoc($result)){
        $errors['id'] = "Roll no $_POST[rollno] from class $_POST[class] already registered, if this is a mistake contact an Admin.";
    }
    return $errors;
}

function validateAdmin(){
    $errors = array('firstname'=>"",'lastname'=>'','position'=>'','accounttype'=>'','contact'=>'','password'=>'','id'=>'');
    if(strlen($_POST['firstname'])<2 || strlen($_POST['firstname'])>32){
        $errors['firstname'] = "First Name must be between 2 and 32 characters";
    }
    if(strlen($_POST['lastname'])<2 || strlen($_POST['lastname'])>32){
        $errors['lastname'] = "Last Name must be between 2 and 32 characters";
    }
    if(strlen($_POST['contact'])!=10){
        $errors['contact'] = "Enter a valid number";
    }
    if(strlen($_POST['password'])<8 || strlen($_POST['password'])>32){
        $errors['password'] = "Password must be between 8 and 32 characters";
    }
    $positions = array('Principal','Vice-Principal','Coordinator','Teacher');
    if(!in_array($_POST['position'],$positions)){
        $errors['position'] = "Enter a valid position";
    }
    if(in_array($_POST['position'],array_slice($positions,0,2))){
        if(!$conn = mysqli_connect('localhost','root','','grade')){
            die("Could not establish connection ".mysqli_connect_error());
        }
        $query = "SELECT * FROM admin
                WHERE position = '$_POST[position]'";
        if(!$result = mysqli_query($conn,$query)){
            die("Could not execute query ".mysqli_error($conn));
        }
        if($row = mysqli_fetch_assoc($result)){
            $errors['id'] = "$_POST[position] already exists.";
        }
    }
    return $errors;
}

function registerStudent(){
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "INSERT into student
                VALUES('$_POST[class]-$_POST[rollno]','$_POST[firstname]','$_POST[lastname]','$_POST[class]','$_POST[rollno]','$_POST[password]');";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't insert into database ".mysqli_error($conn));
    }
    return $user = array('firstname'=>$_POST['firstname'],'lastname'=>$_POST['lastname'],'class'=>$_POST['class'],'rollno'=>$_POST['rollno']);
}

function registerAdmin(){
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not establish connection ".mysqli_connect_error());
    }
    $query = "SELECT * FROM admin
                WHERE position = '$_POST[position]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't insert into database ".mysqli_error($conn));
    }
    $current = count(mysqli_fetch_all($result))+1;
    $id = substr($_POST['position'],0,1)."-$current";
    $query = "INSERT into admin
                VALUES('$id','$_POST[firstname]','$_POST[lastname]','$_POST[position]','$_POST[contact]','$_POST[password]');";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't insert into database ".mysqli_error($conn));
    }
    return $user = array('firstname'=>$_POST['firstname'],'lastname'=>$_POST['lastname'],'position'=>$_POST['position'],'contact'=>$_POST['contact']);
}

?>
</html>