<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Settings";

    $getCategories=selectCategories();
    $countCategories=$getCategories->num_rows;

    $getUnits=selectUnits();
    $countUnits=$getUnits->num_rows;

    $note = @$_GET['note'];

    if ($note == "updated") {

        $the_note_status = "visible";
        $color_note = "success";
        $message = "Changes saved.";

    }else if ($note == "cat_deleted") {

        $the_note_status = "visible";
        $color_note = "success";
        $message = "Category Removed.";

    }else if ($note == "cat_added") {

        $the_note_status = "visible";
        $color_note = "success";
        $message = "Catregory Added.";

    }else if ($note == "cat_restricted") {

        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Catregory Deletion restricted.";

    }else if ($note == "unit_added") {

        $the_note_status = "visible";
        $color_note = "success";
        $message = "Unit Added.";

    }else if ($note == "unit_deleted") {

        $the_note_status = "visible";
        $color_note = "success";
        $message = "Unit Deleted.";

    }else if ($note == "error") {

        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here.";

    }else if ($note == "invalid") {

        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Invalid.";

    }else{

        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";

    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-8">
                    <h3 class="page-header"><i class="fa fa-cogs"></i> <?= $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?= @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?= @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= @$message; ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">App Settings</div>
                        <div class="panel-body">
                            <form action="settingsAppUpdate" method="post" enctype="multipart/form-data" onsubmit="btnLoader(this.appUpdate)">
                                <div class="form-group">
                                    <label for="">App Name</label>
                                    <input type="text" class="form-control" name="appName" id="appName" value="<?= $my_project_name ?>" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <label for="">App Title</label>
                                    <input type="text" class="form-control" name="appTitle" id="appTitle" value="<?= $my_project_title ?>" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="appUpdate" class="btn btn-info">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Category Settings
                            <span class="pull-right text-bold">
                                <?= $countCategories ?> results
                            </span>
                        </div>
                        <div class="panel-body">
                            <form action="category_create" enctype="multipart/form-data" method="post" onsubmit="btnLoader(this.createCategory)">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="col-sm-12" style="padding: 0;">Create Category</label>
                                            <input type="text" name="catName" id="catName" class="form-control" maxlength="50" placeholder="enter category name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-ms-12">&nbsp;</label>
                                            <button type="submit" id="createCategory" class="btn btn-success btn-block">Create</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="text-bold">Note: You can't delete a category if it currently used by a product/s.</p>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="12">
                                                
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Opt</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $num=0;
                                            while ($category=$getCategories->fetch_array()) {

                                                if (countProductByCategory($category['gy_cat_name']) == 0) {
                                                    $rowColor="danger";
                                                } else {
                                                    $rowColor="";
                                                }
                                                

                                                $cat_id = $category['gy_cat_id'];
                                        ?>
                                        <tr class="<?= $rowColor ?>">
                                            <td><?= $category['gy_cat_name'] ?></td>
                                            <td class="text-center text-primary text-bold"><?= countProductByCategory($category['gy_cat_name']) ?></td>
                                            <td class="text-center" style="padding: 1px;">
                                                <button 
                                                    type="button" 
                                                    class="btn btn-info btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#edit_<?= $category['gy_cat_id'] ?>"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                            <td class="text-center" style="padding: 1px;">
                                                <button 
                                                    type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#delete_<?= $category['gy_cat_id'] ?>"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="edit_<?= $category['gy_cat_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> edit <?= $category['gy_cat_name'] ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="category_update?catId=<?= $category['gy_cat_id'] ?>" enctype="multipart/form-data" method="post" onsubmit="btnLoader(this.updateCategory)">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="">Category Name</label>
                                                                        <input type="text" class="form-control" name="category_name" value="<?= $category['gy_cat_name'] ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" name="updateCategory" id="updateCategory" class="btn btn-info btn-block">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="delete_<?= $category['gy_cat_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> edit <?= $category['gy_cat_name'] ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="category_delete?catId=<?= $category['gy_cat_id'] ?>" enctype="multipart/form-data" method="post" onsubmit="btnLoader(this.deleteCategory)">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="">
                                                                        Reminder: <br>
                                                                        <span class="text-danger">You can only delete a category if it has no products connected to it.</span> <br>
                                                                        Are you sure you want to delete this category <span class="text-danger"><?= $category['gy_cat_name'] ?></span>?
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" id="deleteCategory" class="btn btn-danger btn-block">DELETE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Unit Settings
                            <span class="pull-right text-bold">
                                <?= $countUnits ?> results
                            </span>
                        </div>
                        <div class="panel-body">
                            <form action="unit_create" enctype="multipart/form-data" method="post" onsubmit="btnLoader(this.createUnit)">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="col-sm-12" style="padding: 0;">Create Unit</label>
                                            <input type="text" name="unitName" id="unitName" class="form-control" maxlength="50" placeholder="enter unit name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-ms-12">&nbsp;</label>
                                            <button type="submit" id="createUnit" class="btn btn-success btn-block">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $num=0;
                                            while ($unit=$getUnits->fetch_array()) {
                                        ?>
                                        <tr class="info">
                                            <td class="text-center"><?= $unit['gy_unit_name'] ?></td>
                                            <td class="text-center" style="padding: 1px;">
                                                <button 
                                                    type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#delete_<?= $unit['gy_unit_id'] ?>"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="delete_<?= $unit['gy_unit_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> edit <?= $category['gy_cat_name'] ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="unit_delete?unitId=<?= $unit['gy_unit_id'] ?>" enctype="multipart/form-data" method="post" onsubmit="btnLoader(this.deleteCategory)">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="">
                                                                        Reminder: <br>
                                                                        <span class="text-danger">You can only delete a category if it has no products connected to it.</span> <br>
                                                                        Are you sure you want to delete this category <span class="text-danger"><?= $unit['gy_unit_name'] ?></span>?
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" id="deleteCategory" class="btn btn-danger btn-block">DELETE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
