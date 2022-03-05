
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_kavid = anti_injection($con, $_POST['fn_kavid']);

    $sql = mysqli_query($con, "SELECT sheep_tb.fn_sheepid,sheep_tb.fv_codesheep,sheep_tb.fv_namesheep, sheep_tb.fv_image, MAX(sheephealth_tb.fc_nodoc) as fc_nodoc
        FROM sheephealth_tb
        LEFT OUTER JOIN registerkavsheep_tb on sheephealth_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        LEFT OUTER JOIN sheep_tb ON sheep_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        WHERE registerkavsheep_tb.fn_kavid = '$fn_kavid' and sheep_tb.fn_sheepid is not null
        GROUP BY registerkavsheep_tb.fn_sheepid");
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
             $hasil['fv_image'] = $row['fv_image'];
             $xxx = $row['fc_nodoc'];
             $x = mysqli_fetch_array(mysqli_query($con, "SELECT fd_healthdate,fv_disease,fv_diseasetreatment,fv_preventivemeasure FROM sheephealth_tb WHERE fc_nodoc = '$xxx'"));
             if($x == null){
                $hasil['fd_healthdate'] = "";
                $hasil['fv_disease'] = "";
                $hasil['fv_diseasetreatment'] = "";
                $hasil['fv_preventivemeasure'] = "";
             }else{
                $hasil['fd_healthdate'] = $x['fd_healthdate'];
                $hasil['fv_disease'] =  $x['fv_disease'];
                $hasil['fv_diseasetreatment'] = $x['fv_diseasetreatment'];
                $hasil['fv_preventivemeasure'] =  $x['fv_preventivemeasure'];
             }
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

