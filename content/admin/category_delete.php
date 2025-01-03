<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $catId = @$_GET['catId'] ?? '';

    if (empty($catId)) {
        header("location: settings?note=invalid");
    }

    $selectCategory = selectCategory($catId);

    if (!$selectCategory || $selectCategory->num_rows === 0) {
        header("location: settings?note=invalid");
        exit;
    }

    $category=$selectCategory->fetch_array();
    $catName = $category['gy_cat_name'];

    $countProducts = countProductByCategory($catName);

    if ($countProducts > 0) {
        header("location: settings?note=cat_restricted");
        exit;
    }

    $request = deleteCategory($catId);

    if ($request == 1) {
        header("location: settings?note=cat_deleted");
        exit;
    } else {
        header("location: settings?note=error");
        exit;
    }
    
?>