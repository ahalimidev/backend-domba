<?php
require_once('../koneksi.php');
require_once('../send_email.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = generateCode(6);
    $email = anti_injection($con,$_POST['fv_email']);
    $sql = mysqli_query($con, "SELECT * FROM customer_tb WHERE fv_email = '$email' ");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_array($sql);
        //sendEamil($row['fv_email'],$row['fv_namecus'],"verification", "Verification code is : <h4>".$code."</h4>");
        mysqli_query($con,"UPDATE customer_tb SET fv_code = '$code'  WHERE fv_email = '$email'");
        $response["success"] = 1;
        $response["pesan"] = "Berhasil";
        echo json_encode($response);

    }else{
        $response["success"] = 0;
        $response["pesan"] = "Username Salah";
        echo json_encode($response);
    }

} else {
    $response["success"] = 0;
    $response["pesan"] = "tidak ada request";
    echo json_encode($response);
}

