<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'delete_product');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='delete_product' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
            //get info
            $getitem=$link->query("SELECT `gy_product_name` From `gy_products` Where `gy_product_id`='$my_dir_value'");
            $itemrow=$getitem->fetch_array();

            $dname=words($itemrow['gy_product_name']);

            //delete query
            $delete_product=$link->query("Delete From `gy_products` Where `gy_product_id`='$my_dir_value'");

            if ($delete_product) {
                $my_note_text = $dname." was removed from products";
                my_notify($my_note_text,$user_info);
                header("location: products?note=delete");
            }else{
                header("location: products?note=error");
            }

    		
    	}else{
    		header("location: products?note=pin_out");
    	}
    }
?>