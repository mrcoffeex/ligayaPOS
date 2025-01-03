<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $search_text = @$_GET['search_text'];

    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'receipt'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'receipt'
            </script>
         ";
    }else{

        $my_project_header_title = "Search Transaction: ".$search_text;

        //count results
        $count_search_results=$link->query("Select * From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) LIKE '%$search_text%' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id'");
        $search_count=$count_search_results->num_rows;

        $query_one = "Select * From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) LIKE '%$search_text%' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_date` DESC";

        $query_two = "Select COUNT(`gy_trans_id`) From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) LIKE '%$search_text%' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_date` DESC";

        $query_three = "Select * From `gy_transaction` Where CONCAT(`gy_trans_code`,`gy_trans_custname`) LIKE '%$search_text%' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_date` DESC ";

        $my_num_rows = 50;

        include 'my_pagination_search.php';
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
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-search"></i> <?php echo $my_project_header_title; ?></h3>
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
                                            <th><center>Details</center></th>
                                            <!-- <th><center>Void</center></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        while ($sales_row=$query->fetch_array()) {
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i A",strtotime($sales_row['gy_trans_date'])); ?></center></td>
                                            <td class="text-center">
                                                <a 
                                                    href="sales_details?transCode=<?= $sales_row['gy_trans_code'] ?>" 
                                                    onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=1366, height=768, toolbar=1, resizable=0'); return false;"
                                                >
                                                    <button type="button" class="btn btn-info" title="click to see details ..."><i class="fa fa-list fa-fw"></i></button>
                                                </a>
                                            </td>
                                        </tr>

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

    <script type="text/javascript">
        $('#sales_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
