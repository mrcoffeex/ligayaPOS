<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    
    $unitId = @$_GET['unitId'];

    if (empty($unitId)) {
        header("location: settings?note=invalid");
        exit;
    } else {
        
        $request = deleteUnit($unitId);

        if ($request == 1) {
            header("location: settings?note=unit_deleted");
            exit;
        } else {
            header("location: settings?note=error");
            exit;
        }
        
    }
    
?>