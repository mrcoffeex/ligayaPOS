<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Password Pins";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Password is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Password Info is Updated";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "pin_outs") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "code_duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate PIN type is not allowed in every single user";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Password successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "SELECT * From gy_optimum_secure Order By gy_user_id ASC";

    $query_two = "SELECT COUNT(gy_sec_id) From gy_optimum_secure Order By gy_user_id ASC";

    $query_three = "SELECT * From gy_optimum_secure Order By gy_user_id ASC ";

    $my_num_rows = 30;

    include 'my_pagination.php';

    $count_results=$link->query($query_one)->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <?php include 'nav.php'; ?>

    <!-- Modals -->
    <?php include('modal.php');?>
    <?php include('modal_password.php');?> 

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header"><i class="fa fa-lock"></i> <?= $my_project_header_title; ?></h3>
            </div>
            <div class="col-lg-4">
                <div class="alert alert-<?= @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?= @$the_note_status; ?>">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?= @$message; ?>.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Buttons -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_pin"><i class="fa fa-plus fa-fw"></i> Add New Pin</button>
                    </div>
                    <hr>
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">
                        Password Pin Data Table
                        <span class="pull-right"><?= $count_results ?> result(s)</span> 
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">PIN commands</th>
                                        <th class="text-center text-primary">User</th>
                                        <th class="text-center">Show PIN</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php

                                    while ($securityPin=$query->fetch_array()) {

                                        if ($securityPin['gy_sec_type'] == 'delete_pin') {
                                            $btn_status = "disabled";
                                        } else {
                                            $btn_status = "";
                                        }
                                ?>

                                    <tr class="info">
                                        <td class="text-center text-bold"><?= getPinType($securityPin['gy_sec_type']) ?></td>
                                        <td class="text-center text-primary"><?= getUserFullnameById($securityPin['gy_user_id']) ?></td>
                                        <td class="text-center">
                                            <button 
                                            type="button" 
                                            class="btn btn-warning" 
                                            title="click to show your pin ..." 
                                            data-toggle="modal" 
                                            data-target="#show_<?= $securityPin['gy_sec_id']; ?>">
                                                <i class="fa fa-lock fa-fw"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button 
                                            type="button" 
                                            class="btn btn-info" 
                                            title="click to edit pin details ..." 
                                            data-toggle="modal" 
                                            data-target="#edit_<?= $securityPin['gy_sec_id']; ?>">
                                                <i class="fa fa-edit fa-fw"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button 
                                            type="button" 
                                            class="btn btn-danger" 
                                            title="click to delete pin ..." 
                                            data-target="#delete_<?= $securityPin['gy_sec_id']; ?>" 
                                            data-toggle="modal" 
                                            <?= $btn_status; ?> >
                                                <i class="fa fa-trash-o fa-fw"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit -->

                                    <div class="modal fade" id="edit_<?= $securityPin['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit fa-fw"></i> Edit Password PIN Details</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="edit_pin?cd=<?= $securityPin['gy_sec_id']; ?>">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>User</label>
                                                                    <select name="my_user" class="form-control" onchange="check_password_<?= $securityPin['gy_sec_id'] ?>()" required>
                                                                        <option value="<?= $securityPin['gy_user_id']; ?>">
                                                                            <?= getUserRole(getUserType($securityPin['gy_user_id'])) . "  - " . getUserFullnameById($securityPin['gy_user_id']) ?>
                                                                        </option>
                                                                        <?php  
                                                                            //get users
                                                                            $getUserInfo=$link->query("SELECT 
                                                                                                    gy_user_id, 
                                                                                                    gy_full_name, 
                                                                                                    gy_user_type 
                                                                                                    From 
                                                                                                    gy_user 
                                                                                                    Where 
                                                                                                    gy_user_type = 0 OR gy_user_type = 3 
                                                                                                    Order By 
                                                                                                    gy_user_type ASC");
                                                                            while ($userInfo=$getUserInfo->fetch_array()) {
                                                                        ?>
                                                                        <option value="<?= $userInfo['gy_user_id']; ?>">
                                                                            <?= getUserRole($userInfo['gy_user_type'])." - ".$userInfo['gy_full_name']; ?>
                                                                        </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Permission</label>
                                                                    <select name="my_pin_type" class="form-control" onchange="check_password_<?= $securityPin['gy_sec_id']; ?>()" required>
                                                                        <option value="<?= $securityPin['gy_sec_type']; ?>"><?= getPinType($securityPin['gy_sec_type']) ?></option>
                                                                        <option value="delete_pin">Delete PIN </option>
                                                                        <option value="delete_product">Delete Product/Item </option>
                                                                        <option value="add_discount">Add Discount </option>
                                                                        <option value="delete_sales">Void Sale/Transaction </option>
                                                                        <option value="update_cash">Update Beginning Balance </option>
                                                                        <option value="delete_trans">Void Order List </option>
                                                                        <option value="remittance">Add Remittance </option>
                                                                        <option value="cash_breakdown">Cash Breakdown </option>
                                                                        <option value="void_remittance">Void Remittance </option>
                                                                        <option value="custom_breakdown">Custom Breakdown </option>
                                                                        <option value="expenses">All Expenses Permission</option>
                                                                        <option value="ref_rep">Refund/Replace </option>
                                                                        <option value="print">Duplicate Thermal Print </option>
                                                                        <option value="restock_pullout_stock_transfer">Re-Stock/Pull-Out/Stock Transfer </option>
                                                                        <option value="users">System Users </option>
                                                                        <option value="delete_supplier">Delete Supplier </option>
                                                                        <option value="void_tra">TRA Void </option>
                                                                        <option value="void_ro">Request Order Void </option>
                                                                        <option value="bodega">Bodega Permissions </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Create PIN</label>
                                                                    <input type="password" name="my_password1" id="my_password1_<?= $securityPin['gy_sec_id']; ?>" onkeyup="check_password_<?= $securityPin['gy_sec_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?= decryptIt($securityPin['gy_sec_value']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>ReType PIN</label>
                                                                    <input type="password" name="my_password2" id="my_password2_<?= $securityPin['gy_sec_id']; ?>" onkeyup="check_password_<?= $securityPin['gy_sec_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?= decryptIt($securityPin['gy_sec_value']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label id="warning_<?= $securityPin['gy_sec_id']; ?>"></label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <button type="submit" name="submit_pin_edit" id="submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>" class="btn btn-info btn-block" title="click to add user ..." disabled="false">Update PIN <i class="fa fa-angle-right fa-fw"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        function check_password_<?= $securityPin['gy_sec_id']; ?>(){
                                            var pass1 = document.getElementById('my_password1_<?= $securityPin['gy_sec_id']; ?>').value;
                                            var pass2 = document.getElementById('my_password2_<?= $securityPin['gy_sec_id']; ?>').value;

                                            if (pass1 == "") {
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = true;
                                            }else if (pass2 == "") {
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = true;
                                            }else if (pass1 == "" && pass2 == "") {
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = true;
                                            }else if (pass1 != pass2) {
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = true;
                                                document.getElementById('warning_<?= $securityPin['gy_sec_id']; ?>').innerHTML = "Password Mismatch";
                                            }else if (pass1 == pass2) {
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = false;
                                                document.getElementById('warning_<?= $securityPin['gy_sec_id']; ?>').innerHTML = "";
                                            }else{
                                                document.getElementById('submit_pin_edit_<?= $securityPin['gy_sec_id']; ?>').disabled = false;
                                                document.getElementById('warning_<?= $securityPin['gy_sec_id']; ?>').innerHTML = "";
                                            }
                                        }
                                    </script>

                                    <!-- Show PIN -->

                                    <div class="modal fade" id="show_<?= $securityPin['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock fa-fw"></i> Show PIN</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <h1 style="color: #fff;"><?= decryptIt($securityPin['gy_sec_value']); ?></h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete -->

                                    <div class="modal fade" id="delete_<?= $securityPin['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete PIN</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="delete_pin?cd=<?= $securityPin['gy_sec_id']; ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Delete Secure PIN</label>
                                                                    <input type="password" name="my_secure_pin" class="form-control" autofocus required>
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

        <div class="row">
            <div class="col-md-12">
                <div class="text-center"> 
                    <ul class="pagination">
                        <?= $paginationCtrls; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- modal -->

    <div class="modal fade" id="add_pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user fa-fw"></i> Add Password Pin</h4>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="add_pin">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>User</label>
                                    <select name="my_user" class="form-control" required>
                                        <option></option>
                                        <?php  
                                            //get users
                                            $getUserInfo=$link->query("SELECT 
                                                                        gy_user_id, 
                                                                        gy_full_name, 
                                                                        gy_user_type 
                                                                        From 
                                                                        gy_user 
                                                                        Where 
                                                                        gy_user_type = 0 OR gy_user_type = 3 
                                                                        Order By 
                                                                        gy_user_type ASC");
                                            while ($userInfo=$getUserInfo->fetch_array()) {
                                        ?>
                                        <option value="<?= $userInfo['gy_user_id']; ?>">
                                            <?= getUserRole($userInfo['gy_user_type'])." - ".$userInfo['gy_full_name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permissions</label>
                                    <div class="checkbox text-bold">
                                        <label>
                                            <input type="checkbox" id="check_all"> Check All
                                        </label>
                                        <br><br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="delete_pin" value="delete_pin"> Delete PIN
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="delete_product" value="delete_product"> Delete Product
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="add_discount" value="add_discount"> Add Discount
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="delete_sales" value="delete_sales"> Void Transaction
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="update_cash" value="update_cash"> Update Beginning Balance
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="delete_trans" value="delete_trans"> Void Order List
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="remittance" value="remittance"> Add Remittance
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="void_remittance" value="void_remittance"> Void Remittance
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="cash_breakdown" value="cash_breakdown"> Cash Breakdown
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="custom_breakdown" value="custom_breakdown"> Custom Cash Breakdown
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="expenses" value="expenses"> Expenses Permissions
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="ref_rep" value="ref_rep"> Refund/Replace Permissions
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="print" value="print"> Duplicate Thermal Print
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="restock_pullout_stock_transfer" value="restock_pullout_stock_transfer"> Restock/Pullout/StockTransfer
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="users" value="users"> System Users
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="delete_supplier" value="delete_supplier"> Delete Supplier
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="void_ro" value="void_ro"> Request Order Void
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="my_pin_type[]" id="bodega" value="bodega"> Bodega Permissions
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Create PIN</label>
                                    <input type="password" name="my_password1" id="my_password1" onkeyup="check_password('my_password1', 'my_password2', 'submit_pin', 'warning')" minlength="6" maxlength="16" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ReType PIN</label>
                                    <input type="password" name="my_password2" id="my_password2" onkeyup="check_password('my_password1', 'my_password2', 'submit_pin', 'warning')" minlength="6" maxlength="16" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label id="warning"></label>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" name="submit_pin" id="submit_pin" class="btn btn-primary btn-block" title="click to add user ..." disabled="false">Add PIN <i class="fa fa-angle-right fa-fw"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

        // JavaScript to handle Check All functionality
        document.getElementById('check_all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="my_pin_type[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

    </script>

</body>

</html>
