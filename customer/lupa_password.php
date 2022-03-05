<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = anti_injection($con, $_POST['fv_code']);
    $password = anti_injection($con, $_POST['fv_password']);
    $hashpassword = password_hash($password, PASSWORD_BCRYPT);
    $token = anti_injection($con, $_POST['fv_token']);

    $sql = mysqli_query($con, "SELECT * FROM customer_tb WHERE fv_code = '$code' ");
    if (mysqli_num_rows($sql) > 0) {
        mysqli_query($con, "UPDATE  customer_tb SET fv_password ='$hashpassword' WHERE  fv_code = '$code' ");
        $row = mysqli_fetch_array($sql);
        $hasil = array();
        $hasil['fn_cusid'] = $row['fn_cusid'];
        $hasil['fv_namecus'] = $row['fv_namecus'];
        $hasil['fc_hold'] = $row['fc_hold'];
        $response["success"] = 1;
        $response['data'] = $hasil;
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["pesan"] = "Gagal";
        echo json_encode($response);
    };
} else {
    $response["success"] = 0;
    $response["pesan"] = "tidak ada request";
    echo json_encode($response);
}
