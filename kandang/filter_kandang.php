
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $status = anti_injection($con, $_POST['status']);
    $kandang = anti_injection($con, $_POST['kandang']);
    $kondisi = anti_injection($con, $_POST['kondisi']);
    $batas_min = anti_injection($con, $_POST['batas_min']);
    $batas_max = anti_injection($con, $_POST['batas_max']);
    $kapasitas_min = anti_injection($con, $_POST['kapasitas_min']);
    $kapasitas_max = anti_injection($con, $_POST['kapasitas_max']);
    $harga_min = anti_injection($con, $_POST['harga_min']);
    $harga_max = anti_injection($con, $_POST['harga_max']);

    $kondisi_x =  $kondisi == "0" || $kondisi == "" || $kondisi == null ? "" : "ORDER BY kavling_tb.fn_kavid $kondisi ";
    $kandang_x =  $kandang == "0" || $kandang == "" || $kandang == null ? "" : "AND kavling_tb.fn_typekavid = '$kandang' ";
    $batas_x =  $batas_min == "0" && $batas_max =="0" || $batas_min == "" && $batas_max =="" || $batas_min == null && $batas_max ==null ? "" : "AND kavling_tb.fn_size >= $batas_min AND kavling_tb.fn_size <= $batas_max ";
    $kapasitas_x =  $kapasitas_min == "0" && $kapasitas_max =="0" || $kapasitas_min == "" && $kapasitas_max =="" || $kapasitas_min == null && $kapasitas_max ==null ? "" : "AND listsheepsalemst_tb.fn_totalsheep >= $kapasitas_min AND listsheepsalemst_tb.fn_totalsheep <= $kapasitas_max ";

    $harga_x =  $harga_min == "0" && $harga_max =="0" || $harga_min == "" && $harga_max =="" || $harga_min == null && $harga_max ==null ? "" : "AND kavling_tb.fm_price >=  $harga_min AND kavling_tb.fm_price <= $harga_max ";

    $sql = mysqli_query($con, "SELECT kavling_tb.*, SUM(listsheepsalemst_tb.fn_totalsheep) as fn_totalsheep, config_tb.fv_configname FROM kavling_tb 
        LEFT OUTER JOIN registerkavsheep_tb on kavling_tb.fn_kavid = registerkavsheep_tb.fn_kavid
        LEFT OUTER JOIN listsheepsaledtl_tb on listsheepsaledtl_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
        LEFT OUTER JOIN listsheepsalemst_tb on listsheepsalemst_tb.fc_nodoc = listsheepsaledtl_tb.fc_nodoc
        LEFT OUTER JOIN config_tb on config_tb.fn_configid = kavling_tb.fn_typekavid
        WHERE kavling_tb.fc_status = '$status' $kandang_x $batas_x $kapasitas_x $harga_x 
        GROUP BY kavling_tb.fn_kavid $kondisi_x");
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

