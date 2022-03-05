<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  define('HOST', 'localhost');
  define('USER', 'root');
  define('PASS', '');
  define('DATABASE', 'domba');

  $con = mysqli_connect(HOST, USER, PASS, DATABASE) or die('Unable to Connect');

  function anti_injection($con, $data)
  {
    $isset = (isset($data)) ? $data : '';
    $data_1 = mysqli_real_escape_string($con, $isset);
    $data_2 = trim($data_1);
    $data_3 = stripcslashes($data_2);
    $data_4 = htmlspecialchars($data_3);
    $data_5 = strip_tags($data_4);
    return $data_5;
  }
  
  function acakhuruf($data)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
  
    for ($i = 0; $i < $data; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
  }

  function generateCode($length) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}