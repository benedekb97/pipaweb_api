<?php
include('includes/init.php');
if(isset($_POST['api_user']) && isset($_POST['api_key'])){
    $api_user = $mysql->real_escape_string($_POST['api_user']);
    $api_key = $mysql->real_escape_string($_POST['api_key']);
}