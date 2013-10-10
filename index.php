<?php

define('USERNAME_MC_KEY', '0b1U2G3');
define('PASSWORD_MC_KEY', '4P5e6T');

require_once 'savant/Savant3.php';
require_once 'savant/SavantPaginate.php';
include_once 'conn_data.php';
include_once 'functions.php';

$tpl = new Savant3();
session_start();

$connect = db_connect($server, $database, $user_name, $user_password);
if($connect === false) {
    die('An error occured! Please try again later.');
}

if(isset($_GET['where']) && ($_GET['where'] === 'login')) {
    if(isset($_POST['user_name']) && isset($_POST['password'])) {
        $result = select_to_array('SELECT user_id FROM users WHERE user_login = 
            "'.$_POST['user_name'].'" AND user_pass = "'.md5($_POST['password']).'"');
        if(count($result) > 0) {
            $_SESSION['current_user_id'] = $result[0]['user_id'];
            header('Location: index.php');
            exit;
        } else {
            $tpl->message = 'Login process failed. Please try again later.';
        }
    }
    $tpl->display('template/login_form.tpl.php');
    exit;
}

if(isset($_GET['where']) && ($_GET['where'] === 'register')) {
    if(isset($_POST['user_name']) && isset($_POST['password'])
    && isset($_POST['reenter_password'])) {
        if($_POST['password'] === $_POST['reenter_password']) {
            $result = add_user(
                $_POST['user_name'],
                $_POST['password']            
            );
            if($result === true) {
                header('Location: index.php?where=login');
                exit;
            } else {
                $tpl->message = 'Registration process failed. Please try again later.';
            }
        }
    }
    $tpl->display('template/registration_form.tpl.php');
    exit;
}

if(!isset($_SESSION['current_user_id'])) {
    header('Location: index.php?where=login');
    exit;
}

if(isset($_GET['modify'])) {
    $_GET['modify'] = trim($_GET['modify']);
    
    if($_GET['modify'] == 'delete') {
        /* Make sure id isn't empty,
         * we don't want to delete the whole database */
        if(!empty($_GET['user_data_id'])) {
            $result = delete_row($_GET['user_data_id']);
            
            if($result === false) {
                $result = array('success' => 0);
            } else {
                $result = array('success' => 1);            
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;          
        }
    } else if($_GET['modify'] == 'add') {
        $result = add_row(
            $_SESSION['current_user_id'],
            $_GET['user_data_for'],
            $_GET['user_data_name'],
            $_GET['user_data_password']   
        );
        
        if($result === false) {
            $result = array('success' => 0);
        } else {
            $result = array('success' => 1, 'insert_id' => $result);          
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    } else if($_GET['modify'] == 'save') {
        /* Make sure id isn't empty,
         * we don't want to update the whole database */        
        if(!empty($_GET['user_data_id'])) {
            $result = update_row(
                $_GET['user_data_id'],
                $_GET['user_data_for'],
                $_GET['user_data_name'],
                $_GET['user_data_password']
            );

            if($result === false) {
                $result = array('success' => 0);
            } else {
                $result = array('success' => 1);
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;           
        }
    }
}

$rows = select_to_array('SELECT * FROM user_data WHERE user_data_user_id = '.$_SESSION['current_user_id']);
foreach ($rows as &$row) { 
    $row['user_data_name'] = our_decrypt($row['user_data_name'], USERNAME_MC_KEY);
    $row['user_data_password'] = our_decrypt($row['user_data_password'], PASSWORD_MC_KEY);
}
$tpl->rows = $rows;
$tpl->display('template/user_data.tpl.php');

?>
