<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = @$_GET['search_text'];

    $my_project_header_title = "Order List Search: ".$search_text;

    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'index'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'index'
            </script>
         ";
    }

    $query_one = "Select * From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) like '%$search_text%' AND `gy_trans_status`='1' AND `gy_trans_type`='1' AND `gy_user_id`='0' Order By `gy_trans_code` ASC";

    $query_two = "Select COUNT(`gy_trans_id`) From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) like '%$search_text%' AND `gy_trans_status`='1' AND `gy_user_id`='0' AND `gy_trans_type`='1' Order By `gy_trans_code` ASC";

    $query_three = "Select * From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) like '%$search_text%' AND `gy_trans_status`='1' AND `gy_trans_type`='1' AND `gy_user_id`='0' Order By `gy_trans_code` ASC ";

    $my_num_rows = 50;

    include 'my_pagination_search.php';

    $count_results=$link->query($query_one)->num_rows;
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
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Trans Code/Customer Name ..." name="search_trans" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Buttons -->
                            <a href="cashier"><button type="button" class="btn btn-primary" title="click to open POS ..."><i class="fa fa-desktop fa-fw"></i> OPEN POS</button></a>
                        </div>
                        <hr>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Order Data Table <b><?php echo $count_results; ?> result(s)</b>
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Delete</center></th>
                                            <th><center>Trans. Code</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Date Issued</center></th>
                                            <th><center>Process</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($quotation_row=$query->fetch_array()) {
                                    ?>

                                        <tr>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete quotation form" data-target="#delete_<?php echo $quotation_row['gy_trans_code']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $quotation_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $quotation_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($quotation_row['gy_trans_date'])); ?></center></td>
                                            <td><center><a href="cashier?cd=<?php echo $quotation_row['gy_trans_code']; ?>"><button type="button" class="btn btn-primary" title="click to process form"><i class="fa fa-angle-right fa-fw"></i></button></a></center></td>
                                        </tr>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $quotation_row['gy_trans_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Do You want to remove Transaction ID: <span style="color: cyan;"><?php echo $quotation_row['gy_trans_code']; ?></span> on the list?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_order?cd=<?php echo $quotation_row['gy_trans_code']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
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

</body>

</html>
