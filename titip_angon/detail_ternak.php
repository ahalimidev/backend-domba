
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_sheepid = anti_injection($con, $_POST['fn_sheepid']);

    $sql = mysqli_query($con, "SELECT 
    sheep_tb.fn_sheepid,
    sheep_tb.fv_codesheep,sheep_tb.fv_namesheep,
    sheep_tb.fd_dateofbirth,sheep_tb.fn_age,
    x.fv_configname as fn_gender, xx.fv_configname as fn_sheeptype,sheep_tb.fn_perentid,
    sheep_tb.fn_weight, sheep_tb.fn_height,
    sheep_tb.fv_characteristics, sheep_tb.fv_image
    FROM sheep_tb
    LEFT OUTER JOIN  config_tb x on x.fn_configid = sheep_tb.fn_gender
    LEFT OUTER JOIN  config_tb xx on xx.fn_configid = sheep_tb.fn_sheeptype
    where sheep_tb.fn_sheepid = '$fn_sheepid' ");
    if (mysqli_num_rows($sql) > 0) {
        //jika ada data
        $row = mysqli_fetch_array($sql);
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
        $hasil['fn_perentid'] = $row['fn_perentid'];
        $hasil['fv_characteristics'] = $row['fv_characteristics'];
        $hasil['fv_image'] = $row['fv_image'];
        $response["success"] = 1;
        $response['data'] = $hasil;
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
