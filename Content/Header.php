<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: ..\Login\index.php");
}
echo <<<HEADER
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
    <div class="header">
        <a href='$_SERVER[PHP_SELF]' class="logo">
            <img src="..\Images\logo.png" alt="Logo" class='logoimg'>
        </a>
        <div class="options">
            <a href="">Profile</a>
            <a href="$_SERVER[PHP_SELF]?action=logout">Log-out</a>
        </div>
    </div>
HEADER;
?>
