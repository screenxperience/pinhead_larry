<?php
session_start();

session_regenerate_id();

if(empty($_SESSION['user_login']))
{
    header('location:http://'.$_SERVER['HTTP_HOST'].'/login.php');
    exit;
}
?>