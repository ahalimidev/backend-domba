
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $awal = anti_injection($con, $_POST['awal']);
    $akhir = anti_injection($con, $_POST['akhir']);

    $sql_filter = $awal == "" && $akhir == "" ? " WHERE fn_cusid ='$fn_cusid' " : " WHERE fn_cusid ='$fn_cusid' AND DATE(fd_withdrawaldate)  >='$awal' AND DATE(fd_withdrawaldate) < '$akhir' ";


    $sql = mysqli_query($con, "SELECT fc_nodoc,DATE_FORMAT(fd_withdrawaldate, '%d-%m-%y %H:%i') fd_withdrawaldate, fm_saldo,fm_amount,fv_picture,fv_desc  FROM withdrawalsaldocus_tb $sql_filter ");
    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
             $hasil = array();
             $hasil['fc_nodoc'] = $row['fc_nodoc'];
             $hasil['fd_withdrawaldate'] = $row['fd_withdrawaldate'];
             $hasil['fm_saldo'] = "Rp " . number_format($row['fm_saldo'],0,',','.');
             $hasil['fm_amount'] = "Rp " . number_format($row['fm_amount'],0,',','.');
             $hasil['fv_picture'] = $row['fv_picture'];
             $hasil['fv_desc'] = $row['fv_desc'];
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

