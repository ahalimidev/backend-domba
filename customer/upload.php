<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $nama_logo = $_FILES['fv_image']['name'];
    $x_logo = explode('.', $nama_logo);
    $ekstensi_logo = strtolower(end($x_logo));
    $nama_baru_logo = acakhuruf(32) . '.' . $ekstensi_logo;
    $file_tmp_logo = $_FILES['fv_image']['tmp_name'];
    $ekstensi_diperbolehkan    = array('png', 'jpg');

    $sql = mysqli_query($con, "SELECT * FROM customer_tb WHERE fn_cusid = '$fn_cusid' ");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        $gambar = $row['fv_image'];
        $edit = "UPDATE customer_tb SET fv_image = '$nama_baru_logo' where fn_cusid = '$fn_cusid'";
        $query_edit;
        if (in_array($ekstensi_logo, $ekstensi_diperbolehkan) === true) {
            //gambar kosong
            
            if ($gambar == null) {
                move_uploaded_file($file_tmp_logo, './image/' . $nama_baru_logo);
                $query_edit = mysqli_query($con, $edit);
            } else {
                if (file_exists('./image/'.$gambar)) {
                    if (unlink('./image/' . $gambar)) {
                        move_uploaded_file($file_tmp_logo, './image/' . $nama_baru_logo);
                        $query_edit = mysqli_query($con, $edit);
                    } else {
                        move_uploaded_file($file_tmp_logo, './image/' . $nama_baru_logo);
                        $query_edit = mysqli_query($con, $edit);
                    }
                } else {
                    move_uploaded_file($file_tmp_logo, './image/' . $nama_baru_logo);
                    $query_edit = mysqli_query($con, $edit);
                }
                //hapus gambar lalu upload
                if ($query_edit === true) {
                    $response["success"] = 1;
                    $response["pesan"] = "Berhasil Update";
                    echo json_encode($response);
                } else {
                    $response["success"] = 0;
                    $response["pesan"] = "Gagal Update";
                    echo json_encode($response);
                }
            }
        } else {
            //apabila tidak sesuai dengan ketentuan
            $response["success"] = 0;
            $response["pesan"] = "Tidak sesuai dengan PNG, JPG";
            echo json_encode($response);
        }
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
