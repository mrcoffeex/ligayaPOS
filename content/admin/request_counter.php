<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Quotation Counter " . $user_info;

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Item Added";
    }else if ($my_notification == "not_found") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Item not found";
    }else if ($my_notification == "duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate Item";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "No Items Found";
    }else if ($my_notification == "item_remove") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Item Removed";
    }else if ($my_notification == "stocks_added") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Stocks Added";
    }else if ($my_notification == "item_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Item Updated";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    //my scripts
    $check_transaction=$link->query("SELECT * From gy_rqt Where gy_rqt_by='$user_id' AND gy_rqt_status='0'");
    $count_trans=$check_transaction->num_rows;

    if ($count_trans > 0) {
        $get_trans=$link->query("SELECT * From gy_rqt Where gy_rqt_by='$user_id' AND gy_rqt_status='0' Order By gy_rqt_code DESC");
        $get_trans_row=$get_trans->fetch_array();

        $my_trans_code = $get_trans_row['gy_rqt_code'];
    }else{
        $get_latest_trans=$link->query("SELECT * From gy_rqt Order By gy_rqt_code DESC LIMIT 1");
        $trans_row=$get_latest_trans->fetch_array();

        if ($trans_row['gy_rqt_code'] == 0) {
            $my_trans_code = "10000001";
        }else{
            $my_trans_code = $trans_row['gy_rqt_code'] + 1;
        }
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
                    <h3 class="page-header"><i class="fa fa-check-square-o"></i> <?= $my_project_header_title; ?></h3>
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
                <form method="post" enctype="multipart/form-data" action="add_request_item?cd=<?= $my_trans_code; ?>">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">barcode / product name / category</label>
                            <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name/Category ...  (alt + 1)" accesskey="1" list="myProducts" name="product_search" id="product_search" style="border-radius: 0px;" autocomplete="off" autofocus required>
                            <datalist id="myProducts"></datalist>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Pricing</label>
                            <select name="pricing" id="pricing" class="form-control">
                                <option value="0">low to high</option>
                                <option value="1">high to low</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-ms-12">&nbsp;</label>
                            <button type="submit" class="btn btn-success btn-block">Search</button>
                        </div>
                    </div>
                </form>

                <?php  
                    $total=0;
                    $get_items=$link->query("SELECT * From gy_rqt 
                                            Where 
                                            gy_rqt_code = '$my_trans_code' 
                                            AND 
                                            gy_rqt_status= 0 
                                            AND 
                                            gy_rqt_by = '$user_id' 
                                            Order By gy_rqt_id DESC");
                    $count_items=$get_items->num_rows;
                ?>
                <div class="col-md-9">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Data Table: <span class="text-bold"><?= $count_items; ?> items(s)</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Code</th>
                                            <th>Description</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">SubTotal</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            //get items
                                            $grandTotal=0;
                                            while ($item_row=$get_items->fetch_array()) {

                                                $getProduct=selectProductByCode($item_row['gy_product_code']);
                                                $product=$getProduct->fetch_array();

                                                if ($product['gy_product_quantity'] <= $product['gy_product_restock_limit']) {
                                                    $rowColor = "danger";
                                                } else {
                                                    $rowColor = "success";
                                                }

                                                $subTotal = $item_row['gy_product_price_srp'] * $item_row['gy_rqt_quantity'];
                                                $grandTotal += $subTotal;
                                                
                                        ?>
                                            <tr class="<?= $rowColor ?>">
                                                <td class="text-center text-bold"><?= $item_row['gy_product_code']; ?></td>
                                                <td>
                                                    <?= $item_row['gy_product_name']; ?> 
                                                    (<span style="color: blue; font-weight: bold;" title="current quantity ..."><?= $product['gy_product_quantity']." ".$product['gy_product_unit']; ?></span>)
                                                </td>
                                                <td class="text-center text-bold"><?= RealNumber($item_row['gy_product_price_srp'], 2) ?> <br></td>
                                                <td class="text-center text-bold"><?= $item_row['gy_rqt_quantity']." ".$product['gy_product_unit']; ?></td>
                                                <td class="text-center text-bold"><?= RealNumber($subTotal, 2) ?> <br></td>
                                                <td class="text-center text-bold">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_<?= $item_row['gy_rqt_id']; ?>" title="click to edit quantity ..."><i class="fa fa-edit fa-fw"></i></button>
                                                </td>
                                                <td class="text-center text-bold">
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_<?= $item_row['gy_rqt_id']; ?>" title="click to remove ..."><i class="fa fa-times fa-fw"></i></button>
                                                </td>
                                            </tr>

                                            <!-- Edit -->

                                            <div class="modal fade" id="edit_<?= $item_row['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit fa-fw"></i> Edit Item <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data" action="edit_request_quantity?cd=<?= $item_row['gy_rqt_id']; ?>">

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="">CAP: <span class="text-primary" title="<?= $product['gy_product_price_cap'] ?>"><?= toAlpha($product['gy_product_price_cap']) ?></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Quantity <span class="text-success"><?= $product['gy_product_unit']; ?></span></label>
                                                                            <input type="number" name="my_quantity" min="1" value="<?= $item_row['gy_rqt_quantity']; ?>" class="form-control" autofocus required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label><span style="color: green;">Price SRP</span></label>
                                                                            <input type="number" name="my_srp" step="0.01" min="0" value="<?= $item_row['gy_product_price_srp']; ?>" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <button type="submit" name="submit_edit" class="btn btn-warning"><i class="fa fa-angle-right fa-fw"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete -->

                                            <div class="modal fade" id="delete_<?= $item_row['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Remove Item</small></h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="delete_request_item?cd=<?= $item_row['gy_rqt_id']; ?>">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p>Do You want to remove <span style="color: blue;"><?= $item_row['gy_product_name']; ?></span> on the list?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="delete_item" class="btn btn-success">Remove</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="pull-right text-bold" style="color: blue; font-size: 17px;">Total</td>
                                            <td class="text-bold text-center" style="color: blue; font-size: 17px;"><?= RealNumber($grandTotal, 2); ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Request Order Details
                        </div>

                        <div class="panel-body">
                            <form method="post" enctype="multipart/form-data" action="submit_request_transaction" onsubmit="return validateForm(this);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Request Order Code</label>
                                            <input type="text" name="my_trans_code" class="form-control" value="<?= $my_trans_code; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input type="text" name="my_customer" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea name="my_note" class="form-control" rows="2" placeholder="type your note here ..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Prepared By:</label>
                                            <input type="text" name="my_prepared_by" class="form-control" value="<?= $user_info; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" name="submit_trans" id="submit_trans" class="btn btn-success" style="width: 100%;"><i class="fa fa-print fa-fw"></i> Print Quotation</button><br>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.submit_trans.disabled = true; 
            return true;  
      
        }  
        
        var timer;
        
        $(document).ready(function(){
            $("#product_search").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search_quotation", {product_search: $(this).val(), pricing: $('#pricing').val()}, function(data){
                    timer = setTimeout(function() {
                        $("datalist").empty();
                        $("datalist").html(data);
                    }, ms);

                    console.log(data);
                });
            });
        });
    </script>

</body>

</html>
