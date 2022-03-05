
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fc_status = anti_injection($con, $_POST['fc_status']);
    $fv_codekav = anti_injection($con, $_POST['fv_codekav']);

    $sql = mysqli_query($con, " SELECT kavling_tb.*, SUM(listsheepsalemst_tb.fn_totalsheep) as fn_totalsheep, config_tb.fv_configname FROM kavling_tb 
    LEFT OUTER JOIN registerkavsheep_tb on kavling_tb.fn_kavid = registerkavsheep_tb.fn_kavid
    LEFT OUTER JOIN listsheepsaledtl_tb on listsheepsaledtl_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
    LEFT OUTER JOIN listsheepsalemst_tb on listsheepsalemst_tb.fc_nodoc = listsheepsaledtl_tb.fc_nodoc
    LEFT OUTER JOIN config_tb on config_tb.fn_configid = kavling_tb.fn_typekavid
        WHERE kavling_tb.fc_status = '$fc_status' and kavling_tb.fv_codekav LIKE '%$fv_codekav%'
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

