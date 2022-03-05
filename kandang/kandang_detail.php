
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fc_status = anti_injection($con, $_POST['fc_status']);

    $sql = mysqli_query($con, " SELECT kavling_tb.*, SUM(listsheepsalemst_tb.fn_totalsheep) as fn_totalsheep, config_tb.fv_configname FROM kavling_tb 
        LEFT OUTER JOIN listsheepsalemst_tb on listsheepsalemst_tb.fn_kavid = kavling_tb.fn_kavid
        LEFT OUTER JOIN config_tb on config_tb.fn_configid = kavling_tb.fn_typekavid
        WHERE kavling_tb.fc_status = '$fc_status'
        GROUP BY kavling_tb.fn_kavid");
    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
             $hasil = array();
             $hasil['fn_kavid'] = $row['fn_kavid'];
             $hasil['fv_codekav'] = $row['fv_codekav'];
             $hasil['fv_namekav'] = $row['fv_namekav'];
             $hasil['fn_totalsheep'] = $row['fn_totalsheep'];
             $hasil['fn_size'] = $row['fn_size'];
             $hasil['fv_configname'] = $row['fv_configname'];
             $hasil['fm_price'] = "Rp " . number_format($row['fm_price'],0,',','.');
             $hasil['fv_picture'] = $row['fv_picture'];
             $hasil['fv_desc'] = $row['fv_desc'];
             $hasil['fc_status'] = $row['fc_status'];
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

