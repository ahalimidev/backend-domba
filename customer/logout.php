<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = anti_injection($con,$_POST['fn_cusid']);

    $sql = "UPDATE  customer_tb SET fv_token ='' WHERE  fn_cusid = '$id' ";
    if(mysqli_query($con,$sql)){
        $response["success"] = 1;
        $response["pesan"] = "Berhasil";
        echo json_encode($response);
    }else{
        $response["success"] = 0;
        $response["pesan"] = "Gagal";
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["pesan"] = "tidak ada request";
    echo json_encode($response);
}