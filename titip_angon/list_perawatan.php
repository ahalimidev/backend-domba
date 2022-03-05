
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);

    $sql = mysqli_query($con, "SELECT sheep_tb.fn_sheepid,sheep_tb.fv_codesheep,sheep_tb.fv_namesheep, sheep_tb.fv_image, MAX(treatmentmst_tb.fc_nodoc) as fc_nodoc
        FROM treatmentdtl_tb
        LEFT OUTER JOIN  registerkavsheep_tb on treatmentdtl_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        LEFT OUTER JOIN sheep_tb ON sheep_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        LEFT OUTER JOIN treatmentmst_tb on treatmentmst_tb.fc_nodoc = treatmentdtl_tb.fc_nodoc
        LEFT OUTER JOIN registerkavcus_tb on registerkavcus_tb.fn_kavid = registerkavsheep_tb.fn_kavid
        WHERE registerkavcus_tb.fn_cusid = '$fn_cusid'  and sheep_tb.fn_sheepid is not null
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
             $x = mysqli_fetch_array(mysqli_query($con, "SELECT fd_treatdate, fv_desc FROM treatmentmst_tb WHERE  fc_nodoc = '$xxx' "));
             if($x == null){
                $hasil['fd_treatdate'] = "";
                $hasil['fv_desc'] = "";
             }else{
                $hasil['fd_treatdate'] = $x['fd_treatdate'];
                $hasil['fv_desc'] =  $x['fv_desc'];
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

