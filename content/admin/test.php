<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    // $info=$link->query("SELECT * From `ms_stockroom`");

    // $update=10000;

    // while ($in=$info->fetch_array()) {

    //     $update++;

    //     $code = words($in['product_id']);
    //     $name = words($in['product_name']);
    //     $desc = words($in['product_desc']);
    //     $srp = words($in['product_price']);
    //     $unit = words($in['product_unit']);
    //     $quantity = words($in['product_quantity']);
    //     $category = words($in['product_category']);
    //     $reg = words($in['product_update']);

    //     $insert_data=$link->query("INSERT INTO `gy_products`(`gy_product_code`, `gy_supplier_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_product_update_date`, `gy_added_by`, `gy_update_code`, `gy_branch_id`) VALUES ('$code','0','$name','$category','$desc','$unit','0','$srp','0','($srp * 0.95)','10','$reg','$reg','$reg','1','$update','1')");
    // }

    // if ($insert_data) {
    //     echo "<h1>DONE</h1>";
    // }else{
    //     echo "<h1>ERROR</h1>";
    // }

?>