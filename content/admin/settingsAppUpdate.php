<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    if (isset($_POST['appName'])) {
        $appName = words($_POST['appName']);
        $appTitle = words($_POST['appTitle']);

        $request = updateProject($appName, $appTitle);

        if ($request == 1) {
            header("location: settings?note=updated");
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