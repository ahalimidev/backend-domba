
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_kavid = anti_injection($con, $_POST['fn_kavid']);

    $sql = mysqli_query($con, "SELECT sheep_tb.fn_sheepid,sheep_tb.fv_codesheep,sheep_tb.fv_namesheep, sheep_tb.fv_image, MAX(sheepdevelopment_tb.fc_nodoc) as fc_nodoc
        FROM sheepdevelopment_tb
        LEFT OUTER JOIN registerkavsheep_tb on sheepdevelopment_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        LEFT OUTER JOIN sheep_tb ON sheep_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        WHERE registerkavsheep_tb.fn_kavid = '$fn_kavid'  and sheep_tb.fn_sheepid is not null
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
             $x = mysqli_fetch_array(mysqli_query($con, "SELECT fd_devdate,fn_weight, fn_height,fv_characteristics,fv_desc FROM sheepdevelopment_tb WHERE  fc_nodoc = '$xxx' "));
             if($x == null){
                $hasil['fd_devdate'] = "";
                $hasil['fn_weight'] = "";
                $hasil['fv_characteristics'] = "";
                $hasil['fv_desc'] = "";
             }else{
                $hasil['fd_devdate'] = $x['fd_devdate'];
                $hasil['fn_weight'] =  $x['fn_weight'];
                $hasil['fn_height'] =  $x['fn_height'];
                $hasil['fv_characteristics'] =  $x['fv_characteristics'];
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

