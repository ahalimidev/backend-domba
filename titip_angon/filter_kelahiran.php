
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $akhir = anti_injection($con, $_POST['akhir']);
    $awal = anti_injection($con, $_POST['awal']);
    $kondisi = anti_injection($con, $_POST['kondisi']);
    $kondisi_x =  $kondisi == "0" || $kondisi == "" || $kondisi == null ? "" : " ORDER BY sheepbirth_tb.fd_birthdate $kondisi";
    $date_x =  $awal == "0" && $akhir =="0" || $awal == "" && $akhir =="" || $awal == null && $akhir ==null ? "" : " AND date(sheepbirth_tb.fd_birthdate) >=  '$awal' AND date(sheepbirth_tb.fd_birthdate) <= '$akhir' ";

    $sql = mysqli_query($con, "SELECT sheepbirth_tb.fc_nodoc,
    sheepbirth_tb.fv_codesheep,
    sheepbirth_tb.fv_namesheep,
    sheepbirth_tb.fd_birthdate,
    TIMESTAMPDIFF(DAY, str_to_date(sheepbirth_tb.fd_birthdate, '%Y-%M-%d'), current_date) AS fn_age,
    x.fv_configname as fn_gender, xx.fv_configname as fn_sheeptype,
    sheepbirth_tb.fn_weight, 
    sheepbirth_tb.fn_height,
    sheepbirth_tb.fv_characteristics, 
    sheepbirth_tb.fv_image
    FROM sheepbirth_tb
    LEFT OUTER JOIN sheep_tb ON sheep_tb.fn_sheepid = sheepbirth_tb.fn_parentid
    LEFT OUTER JOIN registerkavsheep_tb on  sheep_tb.fn_sheepid = registerkavsheep_tb.fn_sheepid
    LEFT OUTER JOIN  config_tb x on x.fn_configid = sheepbirth_tb.fn_gender
    LEFT OUTER JOIN  config_tb xx on xx.fn_configid = sheepbirth_tb.fn_sheeptype
    LEFT OUTER JOIN registerkavcus_tb on registerkavcus_tb.fn_kavid = registerkavsheep_tb.fn_kavid
    WHERE sheepbirth_tb.fn_parentid IS NOT NULL AND registerkavcus_tb.fn_cusid = '$fn_cusid'  $date_x  $kondisi_x");

    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
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

