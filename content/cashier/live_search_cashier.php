<?php
 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
 
	$product_search = $_GET['product_search'];
	$pricing = $_GET['pricing'];

    if ($pricing == 0) {
        $order = "ASC";
    } else {
        $order = "DESC";
    }

	if ($product_search == "") {
		$getProducts = "";
	} else {
        $getProducts = "SELECT * FROM gy_products 
				WHERE 
				CONCAT(gy_product_name, gy_product_code, gy_product_cat) LIKE '%$product_search%' 
				ORDER BY 
				gy_product_price_srp 
				$order 
				LIMIT 30";
	}

    if ($getProducts == "") {
        echo "<option value='start typing'></option>";
    } else {
        $res=$link->query($getProducts);
        $count=$res->num_rows;

        if(!$res){
            echo mysqli_error($db);
        }else if ($count == 0) {
            echo "<option value='item not found'></option>";
        }else{
            while($row=$res->fetch_array()){
                echo "<option value='" . $row['gy_product_code'] . "'>" . $row['gy_product_cat'] . " - " . $row['gy_product_name'] . " - PHP " . $row['gy_product_price_srp'] . "</option>";
            }
        }
    }
 
?>
