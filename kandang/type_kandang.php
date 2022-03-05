
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = mysqli_query($con, "SELECT config_tb.fv_configname, kavling_tb.fn_typekavid FROM kavling_tb
    LEFT OUTER JOIN config_tb ON config_tb.fn_configid = kavling_tb.fn_typekavid
    GROUP BY kavling_tb.fn_typekavid");
    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
             $hasil = array();
             $hasil['fv_configname'] = $row['fv_configname'];
             $hasil['fn_typekavid'] = $row['fn_typekavid'];
           
             array_push($response['data'], $hasil);
         }
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

