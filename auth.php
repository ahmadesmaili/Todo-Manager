<?php
include "bootstrap/init.php";

$home_url = siteUrl();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];
    $params = $_POST;
//    dd($params);
    if ($action == 'register') {
        $result = register($params);
        if (!$result) {
            msgError('Error: an error in Registration');
        } else {
            msgSuccess("Registration is successfully. Welcome to 7Todo <br><a href='{$home_url}auth.php'>Please Logiin</a>");
//            header("Location:".siteUrl());
        }
    } elseif ($action == 'login') {
        $result = login($params['email'], $params['password']);
        if (!$result) {
            msgError('Error: mail or password is incorrect');
        } else {
//            msgSuccess("login is successfully.<br><a href='$home_url'>Manage Tasks</a>");
            redirect(siteUrl());
        }
    }
}

include "tpl/tpl-auth.php";