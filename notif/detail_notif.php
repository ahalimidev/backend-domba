
<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $days = anti_injection($con, $_POST['days']);
    $sql = "";
    if($days == '1'){
        $sql = mysqli_query($con, "SELECT fc_notif  ,DATE_FORMAT(fd_date, '%d-%m-%y %H:%i') as fd_date, fv_desc  FROM notifcustomer where fn_cusid ='$fn_cusid' and date(fd_date) >= DATE(NOW()) - INTERVAL 1 DAY ");

    }else if($days == '7'){
        $sql = mysqli_query($con, "SELECT fc_notif  ,DATE_FORMAT(fd_date, '%d-%m-%y %H:%i') as fd_date, fv_desc  FROM notifcustomer where fn_cusid ='$fn_cusid' and date(fd_date) >= DATE(NOW()) - INTERVAL 7 DAY  ");

    }else if($days == '14'){
        $sql = mysqli_query($con, "SELECT fc_notif  ,DATE_FORMAT(fd_date, '%d-%m-%y %H:%i') as fd_date, fv_desc  FROM notifcustomer where fn_cusid ='$fn_cusid' and date(fd_date) >= DATE(NOW()) - INTERVAL 14 DAY  ");

    }else if($days == '30'){
        $sql = mysqli_query($con, "SELECT fc_notif  ,DATE_FORMAT(fd_date, '%d-%m-%y %H:%i') as fd_date, fv_desc  FROM notifcustomer where fn_cusid ='$fn_cusid' and date(fd_date) >= DATE(NOW()) - INTERVAL 30 DAY  ");
    }else{
        $sql = mysqli_query($con, "SELECT fc_notif  ,DATE_FORMAT(fd_date, '%d-%m-%y %H:%i') as fd_date, fv_desc  FROM notifcustomer where fn_cusid ='$fn_cusid'");

    }

    if (mysqli_num_rows($sql) > 0) {
         //jika ada data
         $response["success"] = 1;
         $response['data'] = array();
         while ($row = mysqli_fetch_array($sql)) {
             //tampilkan data
             $hasil = array();
             $hasil['fc_notif'] = $row['fc_notif'];
             $hasil['fd_date'] = $row['fd_date'];
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

