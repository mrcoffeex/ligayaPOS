<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $catId = @$_GET['catId'] ?? '';

    if (empty($catId)) {
        header("location: settings?note=invalid");
        exit;
    }

    $selectCategory = selectCategory($catId);

    if (!$selectCategory || $selectCategory->num_rows === 0) {
        header("location: settings?note=invalid");
        exit;
    }

    $category=$selectCategory->fetch_array();

    $currentCategoryName = $category['gy_cat_name'];

    if ($catId == '') {
        header("location: settings?note=invalid");
    }

    if (isset($_POST['category_name'])) {

        $category_name = words($_POST['category_name']);

        $requestCategoryUpdate = updateCategoryName(
                                    $catId, 
                                    $category_name
                                );

        if ($requestCategoryUpdate == 0) {
            header("location: settings?note=requestCategoryUpdate");
        }
        
        $categoryNameChanged = checkCategoryNameIFChanged(
                                    $catId, 
                                    $currentCategoryName
                                );

        if ($categoryNameChanged == 1) {

            $requestProductsCategoryUpdate = updateProductsCategory(
                                                $currentCategoryName, 
                                                $category_name
                                            );

            if ($requestProductsCategoryUpdate == 0) {
                header("location: settings?note=requestProductsCategoryUpdateError");
            }
        }

        header("location: settings?note=updated");
        exit;

    } else {
        header("location: category?note=invalid");
        exit;
    }
    
?>