<?php
include 'Functions.php';
include 'Header.php';

if(isset($_GET['action'])){
    if($_GET['action'] == 'logout'){
        unset($_SESSION['user']);
        header('Location: ..\index.php');
    }
    if(($_GET['action'] == 'entermarks')||($_GET['action'] == 'editmarksheet')){
        if(isset($_POST['submit'])){
            sanitizeData();
            $showForm=$_POST['submit']=='Submit Marks'?'showForm':'editmarksheet';          
            $updateDatabase=$_POST['submit']=='Submit Marks'?'submitMarks':'updateMarks';
            $_GET['class'] = substr($_GET['studentid'],0,2);
            if($error = validateData()){
                $showForm($error);
            }
            else{
                $updateDatabase(getPercentage());
                $showForm("");
            }
        }
        else{
            showForm("");
        }
    }
    if($_GET['action'] == 'showmarks'){
        showMarks();
    }
    if($_GET['action'] == 'marksheet'){
        viewMarksheet();
    }
    if($_GET['action']=='editmarksheet'){
        editmarksheet('');
    }
}
else{
    showTable();
}


?>
<?php
function getNumberOfStudents($class){
    if(!$conn = mysqli_connect("localhost",'root','','grade')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    $query = "SELECT count(studentid) from student
                where class='$class'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not fetch data ".mysqli_error($conn));
    }
    return mysqli_fetch_assoc($result)['count(studentid)'];
}

function showTable(){
    $headings = array('Class',"Total Seats",'Number of Students','Options');
    echo "<div class='container'>
        <table cellspacing='0' align='center'>
            <tr><td>";
    echo implode("</td><td>",$headings);
    echo "</tr>";
    for($i=1;$i<=10;$i++){
        $numberOfStudents = getNumberOfStudents($i);
        echo "<tr>
                <td>$i</td>
                <td>30</td>
                <td>$numberOfStudents</td>
                <td>
                    <a href='$_SERVER[PHP_SELF]?action=entermarks&class=$i'>Enter Marks</a>
                    <br/>
                    <a href='$_SERVER[PHP_SELF]?action=showmarks&class=$i'>Show Marks</a>
                </td>
            </tr>";
    }
    echo "</table>
        </div>";
}

function showForm($error){
    echo <<<FORM
    <div class='container'>
        <form action='' class='dataentry' method='POST'>
            <div class='formheader'>Enter Marks</div>
            <div class='error'>$error</div>
            <div class='rollinputsection'>
                <label for='rollno'>Roll Number</label>
                <input type='number' name='rollno' id='rollno'>
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
                <input type='number' name='english' id='english'>
                <input type='number' name='nepali' id='nepali'>
                <input type='number' name='computer' id='computer'>
                <input type='number' name='maths' id='maths'>
                <input type='number' name='science' id='science'>
            </div>
            <div class='inputsection'>
                <input type='submit' class='submit' name='submit' value='Submit Marks'>
            </div>
            <div class='inputsection'>
                <a href='$_SERVER[PHP_SELF]'>Go Back</a>
            </div>
        </form>
    </div>
FORM;
}

function sanitizeData(){
    foreach($_POST as $key => $value){
        $_POST[$key] = htmlentities($value);
    }
}

function validateData(){
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    $query = "SELECT * FROM student
                WHERE class='$_GET[class]' AND rollno='$_POST[rollno]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Could not retrive data from database ".mysqli_error($conn));
    }
    mysqli_close($conn);
    if(!$row = mysqli_fetch_assoc($result)){
        return "Could not find Roll no $_POST[rollno] from Class $_GET[class]";
    }
    foreach($_POST as $key => $value){
        if($key != 'rollno' && $key != 'submit'){
            if($value > 100 || $value < 0){
                return "Marks must be between 0 and 100";
            }
        }
    }
    return "";
}

function getPercentage(){
    $sum = 0;
    foreach($_POST as $key => $value){
        if($key != 'rollno' && $key != 'submit'){
            if($value < 40){
                return 'fail';
            }
            else{
                $sum+=$value;
            }
        }
    }
    return $sum/5;
}

function submitMarks($result){
    
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    if(strlen(strval($_GET['class']))==1){
        $_GET['class']="0$_GET[class]";
    }
    if(strlen(strval($_POST['rollno']))==1){
        $_POST['rollno']="0$_POST[rollno]";
    }
    $id = $_GET['class']."-$_POST[rollno]";
    
    $query = "INSERT into student_marks
                VALUES('$id','$_POST[english]','$_POST[nepali]','$_POST[computer]','$_POST[maths]','$_POST[science]','$result')";
  

    

    if(!$result = mysqli_query($conn,$query)){
        die("Could not submit data ".mysqli_error($conn));
    }
    mysqli_close($conn);
}

function updateMarks($result){
    
    if(!$conn = mysqli_connect('localhost','root','','grade')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    if(strlen(strval($_GET['class']))==1){
        $_GET['class']="0$_GET[class]";
    }
    if(strlen(strval($_POST['rollno']))==1){
        $_POST['rollno']="0$_POST[rollno]";
    }
    $id = $_GET['class']."-$_POST[rollno]";
    
        
        $query= "UPDATE student_marks where studentid=$id
        SET english='$_POST[english]', nepali='$_POST[nepali]', computer='$_POST[computer]', maths='$_POST[maths]', science='$_POST[science]',
        result='$result'

        ";
    

    if(!$result = mysqli_query($conn,$query)){
        die("Could not submit data ".mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>
<body>
</body>
</html>