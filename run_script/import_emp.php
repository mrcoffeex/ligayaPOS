<?php  
	include '../conf/conn.php';
    include '../conf/function.php';

	if(isset($_POST["submit"])){

		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 1;
		while(($filesop = fgetcsv($handle, 1000, ",")) !== false){
			$desc = $filesop[0];
			$qty = $filesop[1];
			$srp = $filesop[2];
			$unit = $filesop[3];
			$ini_code = $filesop[4];
			$datecurr = date("Y-m-d H:i:s");
			$category = words("Textile/Cloth");
			    
			if($ini_code == ""){
				$code = latest_code("gy_products", "gy_product_code", "100001");
			}else{
				$code = $ini_code;
			}
			
			$sql = "INSERT INTO `gy_products`(`gy_product_code`, 
											`gy_convert_item_code`, 
											`gy_convert_value`, 
											`gy_supplier_code`, 
											`gy_product_name`, 
											`gy_product_cat`, 
											`gy_product_desc`, 
											`gy_product_unit`, 
											`gy_product_price_cap`, 
											`gy_product_price_srp`, 
											`gy_product_quantity`, 
											`gy_product_discount_per`, 
											`gy_product_restock_limit`, 
											`gy_product_date_restock`, 
											`gy_product_date_reg`, 
											`gy_product_update_date`, 
											`gy_added_by`, 
											`gy_update_code`, 
											`gy_branch_id`) 
                            				VALUES('$code',
                            					'',
                            					'0',
                            					'',
                            					'$desc',
                            					'$category',
                            					'',
                            					'$unit',
                            					'0',
                            					'$srp',
                            					'$qty',
                            					'0',
                            					'5',
                            					'$datecurr',
                            					'$datecurr',
                            					'$datecurr',
                            					'1',
                            					'',
                            					'3')";
			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_execute($stmt);

			$c = $c + 1;
		}
        
    	if ($sql) {
    		echo "<p style='color: blue;'>- successful input override</p>";
    	}else{
    		echo "<p style='color: red;'>- something wrong here</p>";
    	}
	}
?>

<!DOCTYPE html>
<html>
	<body>
		<form enctype="multipart/form-data" method="post" role="form">
		    <div class="form-group">
		        <label for="exampleInputFile">File Upload</label>
		        <input type="file" name="file" id="file" size="150">
		        <p class="help-block">Only Excel/CSV File Import.</p>
		    </div>
		    <button type="submit" class="btn btn-default" name="submit" value="submit">Upload</button>
		</form>
	</body>
</html>