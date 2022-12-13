<?php
include 'Functions.php';
include 'Header.php';
if(isset($_GET['action'])){
    if($_GET['action'] == 'logout'){
        unset($_SESSION['user']);
        header("Location: ..\index.php");
    }
    if($_GET['action'] == 'marksheet'){
        viewMarksheet();
    }
}
else{
    showMarks();
}
?>