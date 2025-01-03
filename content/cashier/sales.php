<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Refund/Replace";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Sale Report is removed";
    }else if ($my_notification == "add_refund_error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Refund is not added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "zero") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Empty Quantity Value";
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Input ...";
    }else if ($my_notification == "refund") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "New Item Refund Record added";
    }else if ($my_notification == "replace") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "New Item Replace Record added";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
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
                    <h3 class="page-header"><i class="fa fa-undo"></i> <?= $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?= @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?= @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= @$message; ?>.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-sm-12" style="padding: 0;">Search customer_name / transaction_code / yyyy-mm-dd number</label>
                                    <input type="text" class="form-control" name="search_value" style="border-radius: 0px;" placeholder="customer_name / transaction_code / yyyy-mm-dd number" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-12">&nbsp;</label>
                                    <button type="submit" name="submit_sales_report_sales" class="btn btn-info" title="click to search"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>                      
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Sales Data Table - <span class="text-bold">0 result(s)</span>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><center>Details</center></th>
                                    <th><center>Date</center></th>
                                    <th><center>TransCode</center></th>
                                    <th><center>Customer</center></th>
                                    <th><center>Qty</center></th>
                                    <th><center>RQty</center></th>
                                    <th><center>Description</center></th>
                                    <th><center>Retail</center></th>
                                    <th style="color: blue;"><center>Discount</center></th>
                                    <th style="color: green;"><center>Total</center></th>
                                    <th><center>Refund</center></th>
                                    <th><center>Replace</center></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
