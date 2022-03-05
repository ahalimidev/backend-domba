
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_kavid = anti_injection($con, $_POST['fn_kavid']);

    $sql = mysqli_query($con, "SELECT 
    sheep_tb.fn_sheepid,
    sheep_tb.fv_codesheep,sheep_tb.fv_namesheep,
    sheep_tb.fd_dateofbirth,sheep_tb.fn_age,
    x.fv_configname as fn_gender, xx.fv_configname as fn_sheeptype,
    sheep_tb.fn_weight, sheep_tb.fn_height,
    sheep_tb.fv_characteristics, sheep_tb.fv_image,
    sheepdeath_tb.fd_deathdate,sheepdeath_tb.fv_disease,sheepdeath_tb.fv_deathstatement,sheepdeath_tb.fv_preventivemeasure
    FROM sheepdeath_tb
    LEFT OUTER JOIN sheep_tb ON sheep_tb.fn_sheepid = sheepdeath_tb.fn_sheepid
    LEFT OUTER JOIN registerkavsheep_tb on registerkavsheep_tb.fn_sheepid = sheep_tb.fn_sheepid
    LEFT OUTER JOIN config_tb x on x.fn_configid = sheep_tb.fn_gender
    LEFT OUTER JOIN config_tb xx on xx.fn_configid = sheep_tb.fn_sheeptype
    where registerkavsheep_tb.fn_kavid = '$fn_kavid' ");
    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
             $hasil = array();
             $hasil['fn_sheepid'] = $row['fn_sheepid'];
             $hasil['fv_codesheep'] = $row['fv_codesheep'];
             $hasil['fv_namesheep'] = $row['fv_namesheep'];
             $hasil['fd_dateofbirth'] = $row['fd_dateofbirth'];
             $hasil['fn_age'] = $row['fn_age'];
             $hasil['fn_gender'] = $row['fn_gender'];
             $hasil['fn_sheeptype'] = $row['fn_sheeptype'];
             $hasil['fn_weight'] = $row['fn_weight'];
             $hasil['fn_height'] = $row['fn_height'];
             $hasil['fv_characteristics'] = $row['fv_characteristics'];
             $hasil['fv_image'] = $row['fv_image'];
             $hasil['fd_deathdate'] = $row['fd_deathdate'];
             $hasil['fv_disease'] = $row['fv_disease'];
             $hasil['fv_deathstatement'] = $row['fv_deathstatement'];
             $hasil['fv_preventivemeasure'] = $row['fv_preventivemeasure'];
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

