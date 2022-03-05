<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = anti_injection($con,$_POST['fv_email']);
    $password = anti_injection($con,$_POST['fv_password']);
    $token = anti_injection($con,$_POST['fv_token']);
    
    $sql = mysqli_query($con, "SELECT * FROM customer_tb WHERE fv_email = '$email' ");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_array($sql);
        if(password_verify($password, $row['fv_password'])){
            $hasil = array();
            $hasil['fn_cusid'] = $row['fn_cusid'];
            $hasil['fv_namecus'] = $row['fv_namecus'];
            $hasil['fc_hold'] = $row['fc_hold'];
            $response["success"] = 1;

            $response['data'] = $hasil;
            echo json_encode($response);
        }else{
            $response["success"] = 0;
            $response["pesan"] = "Password Salah";
            echo json_encode($response);
        }
    }else{
        $response["success"] = 0;
        $response["pesan"] = "Username Salah";
        echo json_encode($response);
    }
;} else {
    $response["success"] = 0;
    $response["pesan"] = "tidak ada request";
    echo json_encode($response);
}