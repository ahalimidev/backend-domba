
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $sql = mysqli_query($con, "SELECT SUM(fm_saldo) AS total FROM saldocustomer_tb WHERE fn_cusid ='$fn_cusid' ");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        $response["success"] = 1;
        $response["total"] ="Rp " . number_format($row['total'],0,',','.');

        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["pesan"] = "Gagal";
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["pesan"] = "tidak ada request";
    echo json_encode($response);
}
