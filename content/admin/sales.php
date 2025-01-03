<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Transactions";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Sale Report is removed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "error_printer") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "No POS Printer Detected";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Date Input";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`>'0' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_date` DESC";

    $query_two = "Select COUNT(`gy_trans_id`) From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`>'0' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_date` DESC";

    $query_three = "Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`>'0' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_date` DESC ";

    $my_num_rows = 25;

    include 'my_pagination.php';
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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?php echo @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?php echo @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo @$message; ?>.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Trasaction Code/Customer Name ..." name="sales_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="sales_date_search_f" id="sales_date_search1" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="sales_date_search_t" id="sales_date_search2" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" name="sales_btn" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>                   
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Transaction Data Table 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Trans. Code</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center>Details</center></th>
                                            <th><center>Void</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($sales_row=$query->fetch_array()) {

                                            //get payment method
                                            if ($sales_row['gy_trans_pay'] == 0) {
                                                $method = "CASH";
                                                $check_num = "";
                                                $royal_per = "";
                                                $royal_fee = "";
                                                $my_change = $sales_row['gy_trans_change'];
                                                $my_check_amount = "";
                                            }else if ($sales_row['gy_trans_pay'] == 1) {
                                                $method = "CHEQUE";
                                                $check_num = $sales_row['gy_trans_check_num'];
                                                $royal_per = " (".$sales_row['gy_trans_check_per']."%)";

                                                $royal_fee = number_format(0 + $sales_row['gy_trans_royal_fee'],2);
                                                $my_change = $sales_row['gy_trans_change'] - $sales_row['gy_trans_royal_fee'];
                                                $my_check_amount = number_format($sales_row['gy_trans_cash'], 2);
                                            }else{
                                                $method = "CARD";
                                                $check_num = "";
                                                $royal_per = "";
                                                $royal_fee = "";
                                                $my_change = $sales_row['gy_trans_change'];
                                                $my_check_amount = "";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i A",strtotime($sales_row['gy_trans_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo get_branch_name($sales_row['gy_branch_id']); ?></center></td>
                                            <td class="text-center">
                                                <a 
                                                    href="sales_details?transCode=<?= $sales_row['gy_trans_code'] ?>" 
                                                    onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=1366, height=768, toolbar=1, resizable=0'); return false;"
                                                >
                                                    <button type="button" class="btn btn-info" title="click to see details ..."><i class="fa fa-list fa-fw"></i></button>
                                                </a>
                                            </td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to void transaction..." data-target="#delete_<?php echo $sales_row['gy_trans_code']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $sales_row['gy_trans_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void <?php echo $sales_row['gy_trans_code']; ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_sales?cd=<?php echo $sales_row['gy_trans_code']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Void Secure PIN</label>
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
                            <?php echo $paginationCtrls; ?>
                         </ul>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- <script type="text/javascript">
        $('#sales_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
