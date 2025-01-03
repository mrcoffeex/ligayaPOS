<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    if (isset($_POST['catName'])) {
        $catName = words($_POST['catName']);

        $request=createCategory($catName);

        if ($request == true) {
            header("location: settings?note=cat_added");
        } else {
            header("location: settings?note=error");
        }
        
    } else {
        header("location: settings?note=invalid");
    }
    
?>