<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    if (isset($_POST['unitName'])) {
        $unitName = words($_POST['unitName']);

        $request = createUnit($unitName);

        if ($request == 1) {
            header("location: settings?note=unit_added");
            exit;
        } else {
            header("location: settings?note=error");
            exit;
        }
    } else {
        header("location: settings?note=invalid");
        exit;
    }
    

?>