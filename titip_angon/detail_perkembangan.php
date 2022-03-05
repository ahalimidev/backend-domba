
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_sheepid = anti_injection($con, $_POST['fn_sheepid']);

    $sql = mysqli_query($con, "SELECT 
    sheep_tb.fn_sheepid,
    sheep_tb.fv_codesheep,sheep_tb.fv_namesheep,
    sheep_tb.fd_dateofbirth,sheep_tb.fn_age,
    x.fv_configname as fn_gender, xx.fv_configname as fn_sheeptype,
    sheep_tb.fn_weight, sheep_tb.fn_height,
    sheep_tb.fv_characteristics, sheep_tb.fv_image
    FROM sheep_tb
    LEFT OUTER JOIN  config_tb x on x.fn_configid = sheep_tb.fn_gender
    LEFT OUTER JOIN  config_tb xx on xx.fn_configid = sheep_tb.fn_sheeptype
    where sheep_tb.fn_sheepid = '$fn_sheepid' ");
    if (mysqli_num_rows($sql) > 0) {
        //jika ada data
        $row = mysqli_fetch_array($sql);
        $response["success"] = 1;
        $hasil = array();
        $response['data_all'] = array();

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
        $x = mysqli_query($con, "SELECT fd_devdate,fn_weight, fn_height,fv_characteristics,fv_desc FROM sheepdevelopment_tb WHERE  fn_sheepid = '$fn_sheepid'");

        if(mysqli_num_rows($x) > 0){
            while ($row1 = mysqli_fetch_array($x)) {
                //tampilkan data
                $hasil1 = array();
                $hasil1['fd_devdate'] = $row1['fd_devdate'];
                $hasil1['fn_weight'] =  $row1['fn_weight'];
                $hasil1['fn_height'] =  $row1['fn_height'];
                $hasil1['fv_characteristics'] =  $row1['fv_characteristics'];
                $hasil1['fv_desc'] =  $row1['fv_desc'];
                array_push($response['data_all'], $hasil1);
            }
          
        }else{
            $hasil1 = array();
            $hasil1['fd_devdate'] = "";
                $hasil1['fn_weight'] = "";
                $hasil1['fv_characteristics'] = "";
                $hasil1['fv_desc'] = "";
            array_push($response['data_all'], $hasil1);
        }
        $response['data_one'] = $hasil;
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
