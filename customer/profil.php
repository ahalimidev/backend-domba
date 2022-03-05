<?php
require_once('../koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fn_cusid = anti_injection($con, $_POST['fn_cusid']);
    $sql = mysqli_query($con, "SELECT 
 customer_tb.fn_cusid, customer_tb.fv_codecus,customer_tb.fv_namecus,customer_tb.fv_identitynum,customer_tb.fv_address,customer_tb.fv_zipcode,customer_tb.fv_phone,customer_tb.fv_email,customer_tb.fv_image,country_tb.fv_countryname,province_tb.fv_provincename,city_tb.fv_cityname, customer_tb.fd_dateinput
 FROM customer_tb 
 LEFT OUTER JOIN country_tb ON country_tb.fn_countryid = customer_tb.fn_countryid
 LEFT OUTER JOIN province_tb ON province_tb.fn_provinceid  = customer_tb.fn_provinceid 
 LEFT OUTER JOIN city_tb ON city_tb.fn_cityid   = customer_tb.fn_cityid
 where customer_tb.fn_cusid = '$fn_cusid'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        $hasil = array();
        $hasil['fn_cusid'] = $row['fn_cusid'];
        $hasil['fv_codecus'] = $row['fv_codecus'];
        $hasil['fv_namecus'] = $row['fv_namecus'];
        $hasil['fv_identitynum'] = $row['fv_identitynum'];
        $hasil['fv_address'] = $row['fv_address'];
        $hasil['fv_zipcode'] = $row['fv_zipcode'];
        $hasil['fv_phone'] = $row['fv_phone'];
        $hasil['fv_email'] = $row['fv_email'];
        $hasil['fv_image'] = $row['fv_image'];
        $hasil['fv_countryname'] = $row['fv_countryname'];
        $hasil['fv_provincename'] = $row['fv_provincename'];
        $hasil['fv_cityname'] = $row['fv_cityname'];
        $hasil['fd_dateinput'] = $row['fd_dateinput'];
        $response["success"] = 1;
        $response['data'] = $hasil;
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
