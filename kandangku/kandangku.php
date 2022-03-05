
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);

    $sql = mysqli_query($con, "SELECT 
    kavling_tb.fn_kavid, kavling_tb.fv_codekav,
    kavling_tb.fv_namekav,kavling_tb.fv_picture, 
    COUNT(registerkavsheep_tb.fn_regid) as total_kambing,
    COUNT(sheepbirth_tb.fn_parentid) as kelahiran_kambing,
    COUNT(sheepdeath_tb.fn_sheepid) as kematian_kambing 
    FROM customer_tb
    LEFT OUTER JOIN registerkavcus_tb ON registerkavcus_tb.fn_cusid = customer_tb.fn_cusid
    LEFT OUTER JOIN kavling_tb ON kavling_tb.fn_kavid = registerkavcus_tb.fn_kavid
    LEFT OUTER JOIN registerkavsheep_tb ON registerkavsheep_tb.fn_kavid = kavling_tb.fn_kavid
    LEFT OUTER JOIN sheep_tb on sheep_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
    LEFT OUTER JOIN sheepdeath_tb on sheepdeath_tb.fn_sheepid = sheep_tb.fn_sheepid
    LEFT OUTER JOIN sheepbirth_tb ON sheepbirth_tb.fn_parentid = sheep_tb.fn_perentid
    where registerkavcus_tb.fn_cusid = '$fn_cusid'
    GROUP BY kavling_tb.fn_kavid;");
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
             $hasil['fv_picture'] = $row['fv_picture'];
             $hasil['total_kambing'] = $row['total_kambing'];
             $hasil['kelahiran_kambing'] = $row['kelahiran_kambing'];
             $hasil['kematian_kambing'] = $row['kematian_kambing'];
          
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

