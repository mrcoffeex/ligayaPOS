<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['submit_pin'])) {
        //elements
        $my_user = words($_POST['my_user']);
        $my_password1 = words($_POST['my_password1']);
        $my_password2 = words($_POST['my_password2']);

        //check if there's duplicate pin in the specific command

        if ($my_password1 != $my_password2) {
            header("location: pins?note=pin_out");
        }else{

            $encryptedPin = encryptIt($my_password2);

            $pinTypes = $_POST['my_pin_type'];

            foreach ($pinTypes as $pinType) {

                $check_code=$link->query("SELECT 
                                        * 
                                        From 
                                        gy_optimum_secure 
                                        Where 
                                        gy_sec_type = '$pinType' AND 
                                        gy_user_id = '$my_user'");
                $count=$check_code->num_rows;

                if (!empty($count)) {
                    //update
                    $updateData=$link->query("UPDATE
                                            gy_optimum_secure
                                            SET
                                            gy_sec_value = '$encryptedPin'
                                            Where
                                            gy_sec_type = '$pinType' AND 
                                            gy_user_id = '$my_user'");
                } else {
                    //insert
                    $updateData=$link->query("INSERT Into 
                                            gy_optimum_secure
                                            (
                                                gy_sec_value, 
                                                gy_sec_type, 
                                                gy_user_id
                                            ) 
                                            Values
                                            (
                                                '$encryptedPin',
                                                '$pinType',
                                                '$my_user'
                                            )");
                }

            }

            if ($updateData) {    
                
                my_notify("Another Password PIN is created", $user_info);
                header("location: pins?note=nice");
                        
            }else{
                header("location: pins?note=error");
            }
        }

        
    }
?>