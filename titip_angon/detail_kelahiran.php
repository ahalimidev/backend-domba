
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fc_nodoc = anti_injection($con, $_POST['fc_nodoc']);

    $sql = mysqli_query($con, "SELECT 
    sheepbirth_tb.fc_nodoc,
    sheepbirth_tb.fv_codesheep,
    sheepbirth_tb.fv_namesheep,
    sheepbirth_tb.fd_birthdate,
    TIMESTAMPDIFF(DAY, str_to_date(sheepbirth_tb.fd_birthdate, '%Y-%M-%d'), current_date) AS fn_age,
    x.fv_configname as fn_gender, xx.fv_configname as fn_sheeptype,sheep_tb.fn_perentid,
    sheepbirth_tb.fn_weight, 
    sheepbirth_tb.fn_height,
    sheepbirth_tb.fv_characteristics, 
    sheepbirth_tb.fv_image
    FROM sheepbirth_tb
    LEFT OUTER JOIN  config_tb x on x.fn_configid = sheepbirth_tb.fn_gender
    LEFT OUTER JOIN  config_tb xx on xx.fn_configid = sheepbirth_tb.fn_sheeptype
    WHERE sheepbirth_tb.fc_nodoc = '$fc_nodoc' ");
    if (mysqli_num_rows($sql) > 0) {
        //jika ada data
        $row = mysqli_fetch_array($sql);
        $hasil = array();
        $hasil['fc_nodoc'] = $row['fc_nodoc'];
        $hasil['fv_codesheep'] = $row['fv_codesheep'];
        $hasil['fv_namesheep'] = $row['fv_namesheep'];
        $hasil['fd_birthdate'] = $row['fd_birthdate'];
        $hasil['fn_age'] = $row['fn_age'];
        $hasil['fn_gender'] = $row['fn_gender'];
        $hasil['fn_sheeptype'] = $row['fn_sheeptype'];
        $hasil['fn_weight'] = $row['fn_weight'];
        $hasil['fn_height'] = $row['fn_height'];
        $hasil['fv_characteristics'] = $row['fv_characteristics'];
        $hasil['fv_image'] = $row['fv_image'];
        $hasil['fn_perentid'] = $row['fn_perentid'];
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
