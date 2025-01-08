<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    if (isset($_POST['download_pricing'])) {

        $filequery = "SELECT * FROM gy_products ORDER BY gy_product_id ASC LIMIT 1";

        // Fetch records from database 
        $query = $link->query($filequery); 
     
        if($query->num_rows > 0){ 
            $delimiter = ","; 
            $filename = "product-upload-" . date('Y-m-d H-i-s') . ".csv"; 
             
            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 
             
            // Set column headers 
            $fields = array('Code', 'Name', 'Description', 'Category', 'Unit', 'CAP', 'SRP', 'DISCOUNT LIMIT'); 
            fputcsv($f, $fields, $delimiter); 
             
            // Output each row of the data, format line as csv and write to file pointer 
            while($row = $query->fetch_assoc()){ 
                
                $lineData = array(addCharsAfter($row['gy_product_code'], "_KOPI"), $row['gy_product_name'], $row['gy_product_desc'], $row['gy_product_cat'], $row['gy_product_unit'], $row['gy_product_price_cap'], $row['gy_product_price_srp'], $row['gy_product_discount_per']);

                fputcsv($f, $lineData, $delimiter); 
            } 
             
            // Move back to beginning of file 
            fseek($f, 0); 
             
            // Set headers to download file rather than displayed 
            header('Content-Type: text/csv'); 
            header('Content-Disposition: attachment; filename="' . $filename . '";'); 
             
            //output all remaining data on a file pointer 
            fpassthru($f); 
        } 

        exit; 
    }
 
    

?>

<!DOCTYPE html>
<html>
<head>
    <title>downloading ...</title>
</head>
<body>
    <center><h1>downloading ...</h1></center>
</body>
</html>