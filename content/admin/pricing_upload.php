<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    if(isset($_FILES['file']['tmp_name'])){

        $file = $_FILES['file']['tmp_name'];

        $filename = $_FILES['file']['name'];
        $checkfile = checkfile($filename);
        $datenow = words(date("Y-m-d H:i:s"));

        if ($checkfile == 0) {
            header("location: uploadProducts?note=file_not_allowed");
        }else{

            $handle = fopen($file, "r");
            $c = 0;

            while(($filesop = fgetcsv($handle, ",")) !== false){

                $c++;

                if ($c > 1) {
                    
                    $code = removeCharThatStarts($filesop[0], "_KOPI");
                    $name = words($filesop[1]);
                    $desc = words($filesop[2]);
                    $cat = words($filesop[3]);
                    $unit = words($filesop[4]);
                    $cap = words($filesop[5]);
                    $srp = words($filesop[6]);
                    $discount = words($filesop[7]);
                    
                    //insert query
                    $filequery = "INSERT INTO 
                        gy_products
                            (
                                gy_product_code, 
                                gy_convert_item_code, 
                                gy_convert_value, 
                                gy_supplier_code, 
                                gy_product_name, 
                                gy_product_cat, 
                                gy_product_desc, 
                                gy_product_unit, 
                                gy_product_price_cap, 
                                gy_product_price_srp,  
                                gy_product_discount_per, 
                                gy_product_restock_limit, 
                                gy_product_date_restock, 
                                gy_product_date_reg, 
                                gy_product_update_date, 
                                gy_added_by, 
                                gy_update_code,
                                gy_branch_id
                            ) 
                        VALUES 
                            (
                                '$code',
                                '',
                                0,
                                0,
                                '$name',
                                '$cat',
                                '$desc',
                                '$unit',
                                '$cap',
                                '$srp',
                                '$discount',
                                0,
                                '$datenow',
                                '$datenow',
                                '$datenow',
                                '$user_id',
                                '',
                                1
                            )";
                }

                //update or insert products/items
                @$query_data=$link->query($filequery);

                $c++;
            }

            if($query_data){
                header("location: uploadProducts?note=upload_success");
            }else{
                header("location: uploadProducts?note=error");
            }
        }
        
    }
?>