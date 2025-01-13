<?php  
	function encryptIt( $q ) {
	    $cryptKey  = 'Helper4webcall:9997772595';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );

	}

	function decryptIt( $q ) {
	    $cryptKey  = 'Helper4webcall:9997772595';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}

	function words($value){

		include("conn.php");
		
		$not_fake = mysqli_real_escape_string($link , $value);

		return $not_fake;
	} 

    function RealNumber($value, $decimal){

        if ($value == 0) {
            $res = 0;
        } else {

            $res = number_format($value, $decimal);
        }
        
        return $res;
    }

    function stringLimit($name, $limit){

        if (strlen($name) > $limit){
            $name = substr($name, 0, $limit) . '...';
        }else{
            $name = $name;
        }

        return $name;
    }

    function proper_date($date){

        $newdate = date("M d Y", strtotime($date));

        return $newdate;
    }

    function properDateWithDay($date){

        $newdate = date("M d Y (l)", strtotime($date));

        return $newdate;
    }

    function proper_time($time){
        
        $newtime = date("g:i A", strtotime($time));

        return $newtime;
    }

    function todayOrBefore($date){

        $dateToday = date("Y-m-d");
        $dateDay = date("Y-m-d", strtotime($date));

        if ($dateDay == $dateToday) {
            $res = date("g:i A", strtotime($date));
        } else {
            $res = date("Md Y g:i A", strtotime($date));
        }
        
        return $res;

    }

	function get_curr_age($birthday){
        //values
        $date_now = strtotime(date("Y-m-d"));
        $value = strtotime($birthday);

        //subtract in seconds
        $date_diff = $date_now-$value;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_year_two_param($before, $later){
        //values
        $value_one = strtotime($later);
        $value_two = strtotime($before);

        //subtract in seconds
        $date_diff = $value_one-$value_two;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_timeage($basetime, $currenttime){
        $secs = $currenttime - $basetime;
        $days = $secs / 86400;

        if ($days < 1 ) {
            $age = 1;
        }else{
            $age = 1 + $days;
        }

        //classify weather day, month or year
        if ($age < 30.5) {
            $creditage = floor($age)." day(s)";
        }else if ($age >= 30.5 && $age < 365.25) {
            $creditage = floor(($age / 30.5))." month(s)";
        }else{
            $creditage = floor(($age / 265.25))." year(s)";
        }

        return $creditage;
    }

    function displayImage($image, $default, $directory){

        if (empty($image)) {
            $res = $default;
        }else{
            $res = $directory . "" . $image;
        }

        return $res;

    }

    function imageUpload($input, $location){

        $errors= array();
        $file_name = $_FILES[$input]['name'];

        if (empty($file_name)) {
            $res = "";
        } else {
            $file_size =$_FILES[$input]['size'];
            $file_tmp =$_FILES[$input]['tmp_name'];
            $file_type=$_FILES[$input]['type'];
            $file_extension = pathinfo($_FILES[$input]['name'], PATHINFO_EXTENSION);

            $final_filename = date("YmdHis")."_".$file_name;

            $extensions= array("jpeg","jpg","png","jfif");

            if(in_array($file_extension, $extensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }

            if($file_size > 26000000){
                $errors[]='File size must be excately 25 MB';
            }

            $file_directory = $location."".$final_filename;

            if(empty($errors)==true){

                move_uploaded_file($file_tmp, $file_directory);
                $res = $final_filename;

            }else{

                if ($file_tmp == "") {
                    $res = "";
                }else{
                    $res = "error";
                }

            }
        }

        return $res;

    }

    function get_status($stat_val){
    	if ($stat_val == 1) {
    		$your_stat_val = "Member";
    	}else{
    		$your_stat_val = "Non-Member";
    	}

    	return $your_stat_val;
    }

    function my_notify($note_text,$user){

    	include("conn.php");

    	$note_now = date("Y-m-d H:i:s");
    	$my_notification_full = $note_text." by ".$user;
    	
    	//insert to database
    	$insert_data=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) Values('$my_notification_full','$note_now')");
    }

    function by_pin_get_user($my_pin, $my_type){

        include("conn.php");

        $my_en_pin = words(encryptIt($my_pin));
        
        //get the user id from 
        $get_id=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_value`='$my_en_pin' AND `gy_sec_type`='$my_type'");
        $get_pin_row=$get_id->fetch_array();

        $my_user_info_id = $get_pin_row['gy_user_id'];

        return $my_user_info_id;
    }

    function get_days($fromdate, $todate) {
        $fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
        $todate = \DateTime::createFromFormat('Y-m-d', $todate);
        return new \DatePeriod(
            $fromdate,
            new \DateInterval('P1D'),
            $todate->modify('+1 day')
        );
    }

    // $datePeriod = get_days_in_two_dates($date1, $date2);
    // foreach($datePeriod as $date) {
    //     echo $date->format('d'), PHP_EOL;
    // }

    function data_verify($my_ver_data){
        if ($my_ver_data == "") {
            $my_ver_data_value = "No Data";
        }else{
            $my_ver_data_value = $my_ver_data;
        }

        return $my_ver_data_value;
    }

    function my_rand_str( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function my_rand_int( $length ) {
        $chars = "0123456789";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function toAlpha($number) {
        $alphabet = array('Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
        $count = count($alphabet);
    
        $integerPart = floor($number);
        $alphaInteger = '';
    
        if ($integerPart == 10) {
            $alphaInteger = "AZ";
        } else if ($integerPart <= $count && $integerPart > 0) {
            $alphaInteger = $alphabet[$integerPart];
        } else {
            while ($integerPart > 0) {
                $modulo = $integerPart % $count;
                $alphaInteger = $alphabet[$modulo] . $alphaInteger;
                $integerPart = floor($integerPart / $count);
            }
        }
    
        $decimalPart = $number - floor($number);
        $alphaDecimal = '';
    
        if ($decimalPart > 0) {
            $alphaDecimal = '.';
            while ($decimalPart > 0) {
                $decimalPart *= $count;
                $index = floor($decimalPart);
                $alphaDecimal .= $alphabet[$index];
                $decimalPart -= $index;
                if (strlen($alphaDecimal) > 2) break;
            }
        }

        if ($number <= 0) {
            return "Z";
        } else {
            return $alphaInteger . $alphaDecimal;
        }
    
    }
    
    function toBeta($alpha) {
        $alphabet = array('Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
        $count = count($alphabet);
        $alphaToIndex = array_flip($alphabet);
    
        $parts = explode('.', $alpha);
        $alphaInteger = $parts[0];
        $alphaDecimal = isset($parts[1]) ? $parts[1] : '';
    
        $integerPart = 0;
        $length = strlen($alphaInteger);
        for ($i = 0; $i < $length; $i++) {
            $integerPart = $integerPart * $count + $alphaToIndex[$alphaInteger[$i]];
        }
    
        $decimalPart = 0;
        $decimalMultiplier = 1 / $count;
        $length = strlen($alphaDecimal);
        for ($i = 0; $i < $length; $i++) {
            $decimalPart += $alphaToIndex[$alphaDecimal[$i]] * $decimalMultiplier;
            $decimalMultiplier /= $count;
        }
    
        return $integerPart + $decimalPart;
    }

    function paymentMethod($var){

        if ($var == 0) {
            $res = "CASH";
        } else if ($var == 1) {
            $res = "CHEQUE";
        } else if ($var == 2) {
            $res = "CARD";
        } else if ($var == 3) {
            $res = "DEPOSIT";
        } else {
            $res = "-";
        }
        
        return $res;

    }

    function getUserRole($userType){

        if ($userType == "0") {
            $res = "Administrator";
        }else if ($userType == "1") {
            $res = "Salesman";
        }else if ($userType == "2") {
            $res = "Cashier";
        }else if ($userType == "3") {
            $res = "Moderator";
        }else if ($userType == "4") {
            $res = "Bodega Staff";
        }else if ($userType == "5") {
            $res = "Customer Service";
        }else{
            $res = "unknown";
        }

        return $res;

    }

    function getChange($paymentMethod, $change, $royalFee){

        if ($paymentMethod == 1) {
            $res = $change - $royalFee;
        } else {
            $res = $change;
        }
        
        return $res;
    }

    function none_if_empty($value){

        if ($value == "") {
            $res = "...";
        } else {
            $res = $value;
        }
        
        return $res;
    }
    
    function addCharsAfter($string, $chars){

        $res = $string."".$chars;

        return $res;

    }
    
    function removeCharThatStarts($string, $char){

        $res = substr($string, 0, strpos($string, $char));

        if (empty($res)) {
            return $string;
        } else {
            return $res;
        }

    }
    
    function checkfile($file){

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext == "csv") {
            $r_value = 1;
        }else{
            $r_value = 0;
        }

        return $r_value;
    }

    function latest_code($ltable, $lcolumn, $lfirstcount){

        include("conn.php");

        $getlatest=$link->query("SELECT `".$lcolumn."` FROM `".$ltable."` ORDER BY `".$lcolumn."` DESC LIMIT 1");
        $latestrow=$getlatest->fetch_array();
        $countl=$getlatest->num_rows;

        if ($countl == 0) {
            $mylatestcode = $lfirstcount;
        }else{
            $mylatestcode = $latestrow[$lcolumn] + 1;
        }

        return $mylatestcode;
    }

    function get_branch_name($branch_id){

        include("conn.php");

        $statement=$link->query("SELECT `gy_branch_name` From `gy_branch` Where `gy_branch_id`='$branch_id'");
        $row=$statement->fetch_array();
        $count=$statement->num_rows;

        if ($count > 0) {
            $result = $row['gy_branch_name'];
        }else{
            $result = "All";
        }

        return $result;
    }

    // password pins

    function getPinType($type){

        if ($type == 'delete_pin') {

            $res = "Delete PIN";

        }else if ($type == 'delete_product') {

            $res = "Delete Product/Item";

        }else if ($type == 'add_discount') {

            $res = "Add Discount ";
            
        }else if ($type == 'delete_sales') {

            $res = "Void Sale/Transaction";
            
        }else if ($type == 'update_cash') {

            $res = "Update Beginning Balance";
            
        }else if ($type == 'delete_trans') {

            $res = "Void Order List";
            
        }else if ($type == 'remittance') {

            $res = "Add Remittance";
            
        }else if ($type == 'cash_breakdown') {

            $res = "Cash Breakdown";
            
        }else if ($type == 'void_remittance') {

            $res = "Void Remittance";
            
        }else if ($type == 'custom_breakdown') {

            $res = "Custom Breakdown";
            
        }else if ($type == 'expenses') {

            $res = "All Expenses Permission";
            
        }else if ($type == 'ref_rep') {

            $res = "Refund/Replace";
            
        }else if ($type == 'print') {

            $res = "Duplicate Thermal Print ";
            
        }else if ($type == 'restock_pullout_stock_transfer') {

            $res = "Re-Stock/Pull-Out/Stock Transfer ";
            
        }else if ($type == 'users') {

            $res = "System Users ";
            
        }else if ($type == 'delete_supplier') {

            $res = "Delete Supplier ";
            
        }else if ($type == 'void_tra') {

            $res = "TRA Void ";
            
        }else if ($type == 'void_ro') {

            $res = "Request Order Void ";
            
        }else if ($type == 'bodega') {

            $res = "Bodega Permissions";
            
        }else{
            $res = "Unknown";
            
        }

        return $res;

    }

    // project

    function updateProject($appName, $appTitle){

        include 'conn.php';

        $stmt=$link->query("UPDATE gy_my_project SET
                        gy_project_name = '$appName',
                        gy_system_title = '$appTitle'");
        if ($stmt) {
            return 1;
        } else {
            return 0;
        }

    }

    // categories

    function selectCategories(){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_category 
                                Order By 
                                gy_cat_name 
                                ASC");
        
        return $statement;

    }
    
    function selectCategory($catId){

        include 'conn.php';

        $stmt=$link->query("SELECT 
                            * 
                            From 
                            gy_category 
                            Where
                            gy_cat_id = '$catId'");
        
        return $stmt;

    }

    function countProductByCategory($catName){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                gy_product_id 
                                From 
                                gy_products
                                Where
                                gy_product_cat = '$catName'");
        $count=$statement->num_rows;
        
        return $count;

    }
    
    function createCategory($catName){

        include 'conn.php';

        $stmt=$link->query("INSERT INTO gy_category
                            (
                                gy_cat_name
                            ) 
                            Values
                            (
                                '$catName'
                            )");

        if ($stmt) {
            return true;
        } else {
            return false;
        }

    }

    function deleteCategory($catId){

        include 'conn.php';

        $stmt=$link->query("DELETE FROM gy_category
                            Where
                            gy_cat_id = '$catId'");
        if ($stmt) {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    function updateCategoryName($catId, $inputName){

        include 'conn.php';

        $stmt=$link->query("UPDATE gy_category SET
                            gy_cat_name = '$inputName'
                            Where
                            gy_cat_id = '$catId'");
        if ($stmt) {
            return 1;
        } else {
            return 0;
        }

    }

    function updateProductsCategory($productCategory, $newCategory){

        include 'conn.php';

        $stmt=$link->query("UPDATE gy_products SET
                            gy_product_cat = '$newCategory'
                            Where
                            gy_product_cat = '$productCategory'");
        if ($stmt) {
            return 1;
        } else {
            return 0;
        }

    }
    
    function checkCategoryNameIFChanged($catId, $inputName){

        include 'conn.php';

        $stmt=$link->query("SELECT gy_cat_name 
                            FROM 
                            gy_category 
                            WHERE 
                            gy_cat_id = '$catId'");
        $res=$stmt->fetch_array();

        if ($res['gy_cat_name'] != $inputName) {
            return 1;
        } else {
            return 0;
        }

    }
    
    function count_cat_qty($cat, $date){

        include 'conn.php';

        if (empty($date)) {
            $query = "SELECT 
                    gy_product_id 
                    From 
                    gy_products 
                    Where 
                    gy_product_cat='$cat'";
        } else {
            $query = "SELECT 
                    gy_product_id 
                    From 
                    gy_products 
                    Where 
                    gy_product_cat='$cat'
                    AND
                    date(gy_product_update_date) 
                    BETWEEN
                    '$date' AND CURDATE()";
        }

        $statement=$link->query($query);
        $count=$statement->num_rows;

        return $count;

    }

    // units

    function selectUnits(){

        include 'conn.php';

        $stmt=$link->query("SELECT * FROM gy_unit Order By gy_unit_name ASC");

        return $stmt;

    }

    function createUnit($newUnit){

        include 'conn.php';

        $stmt=$link->query("INSERT INTO gy_unit
                            (
                                gy_unit_name
                            )
                            Values
                            (
                                '$newUnit'
                            )");
        if ($stmt) {
            return 1;
        } else {
            return 0;
        }

    }

    function deleteUnit($unitId){

        include 'conn.php';

        $stmt=$link->query("DELETE FROM gy_unit Where gy_unit_id = '$unitId'");

        if ($stmt) {
            return 1;
        } else {
            return 0;
        }

    }

    // supplier

    function selectSuppliers(){

        include 'conn.php';

        $statement=$link->query("SELECT * From gy_supplier Order By gy_supplier_name ASC");

        return $statement;

    }

    function selectSupplier($supplierCode){

        include 'conn.php';

        $statement=$link->query("SELECT * From gy_supplier
                                Where
                                gy_supplier_code = '$supplierCode'");

        return $statement;

    }

    // products

    function selectProduct($productId){

        include 'conn.php';

        $statement=$link->query("SELECT * From gy_products
                                Where
                                gy_product_id = '$productId'");
        return $statement;

    }

    function selectProductByCode($productCode){

        include 'conn.php';

        $statement=$link->query("SELECT * From gy_products
                                Where
                                gy_product_code = '$productCode'");
        return $statement;

    }

    function countAlbum($limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                gy_product_quantity != 0
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                gy_product_quantity != 0
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }

        $count=$statement->num_rows;
        
        return $count;

    }

    function selectAlbum($limit){

        include 'conn.php';

        $statement=$link->query("SELECT 
                            * 
                            From 
                            gy_products 
                            Where
                            gy_product_quantity != 0
                            Order By 
                            gy_product_code 
                            ASC
                            LIMIT $limit");
        
        return $statement;

    }

    function countAlbumSearch($search_text, $limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }

        $count=$statement->num_rows;
        
        return $count;

    }

    function selectAlbumSearch($search_text, $limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }
        
        return $statement;

    }

    function updateProductImage($image, $productId){

        include 'conn.php';

        $statement=$link->query("UPDATE
                                    gy_products
                                    SET
                                    gy_product_image = '$image'
                                    Where
                                    gy_product_id = '$productId'");
        if ($statement) {
            return true;
        } else {
            return false;
        }

    }

    function getProductCode($productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_code
                                FROM
                                gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_code'];

    }

    function getProductId($productCode){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_id
                                FROM
                                gy_products
                                Where
                                gy_product_code = '$productCode'");
        $res=$statement->fetch_array();

        return $res['gy_product_id'];

    }

    function getProductName($productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_name
                                FROM
                                gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_name'];

    }

    function getProductDesc($productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_desc
                                FROM
                                gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_desc'];

    }

    function getProductUnit($productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_unit
                                FROM
                                gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_unit'];

    }

    function getConvertProductNameById($productId){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_name From gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        if (empty($res['gy_product_name'])) {
            $result = "product_id";
        } else {
            $result = $res['gy_product_name'];
        }
        
        return $result;

    }

    function getProductNameById($productId){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_name From gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_name'];

    }

    function getProductNameByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_name From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_name'];

    }

    function getProductUnitByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_unit From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_unit'];

    }

    function getProductCategoryByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_cat From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_cat'];

    }

    function getProductSrpByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_price_srp From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_price_srp'];

    }

    function getProductQtyByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_quantity From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_quantity'];

    }

    function getProductLastPriceByCode($productcode){

        include 'conn.php';

        $statement=$link->query("SELECT gy_product_discount_per From gy_products
                                Where
                                gy_product_code = '$productcode'");
        $res=$statement->fetch_array();

        return $res['gy_product_discount_per'];

    }

    function getTransDetailsQtyByCode($productCode, $dateFrom, $dateTo){

        include 'conn.php';

        $statement=$link->query("SELECT SUM(gy_trans_quantity) as transdet_qty 
                                From gy_trans_details
                                Where
                                gy_product_code = '$productCode'
                                AND
                                date(gy_transdet_date)
                                BETWEEN
                                '$dateFrom' AND '$dateTo'");
        $res=$statement->fetch_array();

        if (empty($res['transdet_qty'])) {
            return 0;
        } else {
            return $res['transdet_qty'];
        }

    }

    function selectInventoryBreakdown($productid, $dateFrom, $dateTo){

        include 'conn.php';

        $stmt=$link->query("SELECT gy_trans_code as trans_code, gy_transdet_date as trans_date, gy_trans_quantity as trans_qty, 'SALES' as source_table FROM gy_trans_details Where gy_product_id = '$productid' AND (date(gy_transdet_date) BETWEEN '$dateFrom' AND '$dateTo')
                            UNION
                            SELECT gy_restock_code as trans_code, gy_restock_date as trans_date, gy_restock_quantity as trans_qty, 'RESTOCK' as source_table FROM gy_restock Where gy_product_id = '$productid' AND (date(gy_restock_date) BETWEEN '$dateFrom' AND '$dateTo')                            
                            UNION
                            SELECT gy_pullout_code as trans_code, gy_pullout_date as trans_date, gy_pullout_quantity as trans_qty, 'PULLOUT' as source_table FROM gy_pullout Where gy_product_id = '$productid' AND (date(gy_pullout_date) BETWEEN '$dateFrom' AND '$dateTo')                            
                            UNION 
                            SELECT gy_transfer_code as trans_code, gy_transfer_date as trans_date, gy_transfer_quantity as trans_qty, 'STOCK_TRANSFER' as source_table FROM gy_stock_transfer Where gy_product_id = '$productid' AND (date(gy_transfer_date) BETWEEN '$dateFrom' AND '$dateTo')
                            Order By trans_date ASC
                            ");
        return $stmt;

    }

    function getInventoryArrowType($type){

        switch ($type) {
            case 'RESTOCK':
                return '<span class="text-success"><i style="font-size: 7px;" class="fa fa-chevron-up"></i></span>';
                break;
            default:
                return '<span class="text-danger"><i style="font-size: 7px;" class="fa fa-chevron-down"></i></span>';
                break;
        }

    }

    // users

    function getUserFullnameById($userid){

        include 'conn.php';

        $statement=$link->query("SELECT gy_full_name From gy_user 
                                Where 
                                gy_user_id='$userid'");
        $res=$statement->fetch_array();

        return $res['gy_full_name'];

    }
    
    function getUserType($userId){

        include 'conn.php';

        $statement=$link->query("SELECT gy_user_type 
                                FROM
                                gy_user
                                Where 
                                gy_user_id='$userId'");
        
        $res=$statement->fetch_array();

        return $res['gy_user_type'];
    }

    // transactions

    function selectTransaction($transcode){

        include 'conn.php';

        $statement=$link->query("SELECT * From gy_transaction
                                Where
                                gy_trans_code = '$transcode'");
        return $statement;

    }

    function getSalesDetails($transcode){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                gy_transdet_id,
                                gy_trans_code,
                                gy_transdet_date,
                                gy_product_price,
                                gy_product_id,
                                gy_trans_quantity,
                                gy_trans_ref_rep_quantity
                                From 
                                gy_trans_details 
                                Where 
                                gy_trans_code = '$transcode' 
                                Order By 
                                gy_product_price 
                                DESC");
        return $statement;

    }

    function selectItemsSold($date1, $date2){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                DISTINCT(gy_trans_details.gy_product_id) as item_sold, gy_product_name, gy_product_cat, gy_product_color, gy_product_unit
                                FROM
                                gy_trans_details
                                LEFT JOIN
                                gy_products
                                ON
                                gy_trans_details.gy_product_id = gy_products.gy_product_id
                                Where
                                gy_transdet_date
                                BETWEEN
                                '$date1' AND '$date2'
                                Order By
                                gy_products.gy_product_name
                                ASC");
        return $statement;

    }

    function getItemSoldQty($date1, $date2, $productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                SUM(gy_trans_quantity) as total_sold
                                FROM
                                gy_trans_details
                                Where
                                gy_product_id = '$productId'
                                AND
                                gy_transdet_date
                                BETWEEN
                                '$date1' AND '$date2'");
        $res=$statement->fetch_array();

        return $res['total_sold'];

    }

    // sales

    function getSalesStats($date1, $date2){

        include 'conn.php';

        $statement=$link->query("SELECT DATE_FORMAT(date(gy_trans_date), '%b %e, %Y') as sales_date, SUM(gy_trans_total) AS sales
                                FROM gy_transaction
                                WHERE 
                                gy_user_id != 0 AND
                                date(gy_trans_date) 
                                BETWEEN
                                '$date1' AND '$date2' 
                                GROUP BY date(gy_trans_date)");

        return $statement;

    }

    function getLatestDate(){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                date(gy_trans_date) as latest_date 
                                From 
                                gy_transaction 
                                WHERE 
                                gy_user_id != 0
                                Order By
                                gy_trans_date
                                DESC
                                LIMIT 1");
        $res=$statement->fetch_array();

        return $res['latest_date'];

    }

    //expenses

    function selectExpenses($date1, $date2, $userId){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_expenses 
                                WHERE 
                                gy_user_id = '$userId'
                                AND
                                date(gy_exp_date) 
                                BETWEEN
                                '$date1' AND '$date2'
                                Order By
                                gy_exp_date
                                ASC");

        return $statement;

    }

    function getTotalExpenses($date1, $date2, $userId){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                SUM(gy_exp_amount) as total_exp 
                                From 
                                gy_expenses 
                                WHERE 
                                gy_user_id = '$userId'
                                AND
                                date(gy_exp_date) 
                                BETWEEN
                                '$date1' AND '$date2'");
        $res=$statement->fetch_array();

        return $res['total_exp'];

    }
    
?>