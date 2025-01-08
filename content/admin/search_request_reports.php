<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    if ($datef == $datet) {
        $my_project_header_title = "Request Order Reports: ".date("M d, Y", strtotime($datef));
    }else{
        $my_project_header_title = "Request Order Reports: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
    }

    $getQuotations=$link->query("SELECT DISTINCT gy_rqt_code From gy_rqt Where gy_rqt_status='1' AND date(gy_rqt_date) BETWEEN '$datef' AND '$datet' Order By gy_rqt_date DESC");
    $count_results=$getQuotations->num_rows;
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
                <div class="col-md-12">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="request_date_search_f" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="request_date_search_t" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="request_btn" class="btn btn-success" title="click to search"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </form>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="request_reports">
                                <button type="button" class="btn btn-primary btn-sm">go back</button>
                            </a>
                            Quotation Summary Data Table <b><?= 0+$count_results; ?></b> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Print</center></th>
                                            <th style="color: blue;">Code</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Customer</th>
                                            <th>Encoder</th>
                                            <th><center>Note</center></th>
                                            <th><center>Delete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  

                                        while ($quotation=$getQuotations->fetch_array()) {

                                            $rcode=words($quotation['gy_rqt_code']);
                                            $getQuotes=$link->query("SELECT * From gy_rqt Where gy_rqt_code = '$rcode' Order By gy_rqt_date DESC");
                                            $countQuotes=$getQuotes->num_rows;
                                            $quote=$getQuotes->fetch_array();

                                            $encoder = getUserFullnameById($quote['gy_rqt_by']);
                                    ?>

                                        <tr class="success">
                                            <td>
                                                <center>
                                                    <a 
                                                        href="print_rqts?cd=<?= $rcode; ?>&encoder=<?= $encoder; ?>" 
                                                        onclick="window.open(this.href, 'mywin','left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"
                                                    >
                                                        <button type="button" class="btn btn-success" title="click to void the restock summary ..."><i class="fa fa-print fa-fw"></i></button>
                                                    </a>
                                                </center>
                                            </td>
                                            <td style="font-weight: bold; color: blue;"><?= $quote['gy_rqt_code']; ?></td>
                                            <td style="font-weight: bold;"><?= date("M-d-Y g:i A", strtotime($quote['gy_rqt_date'])); ?></td>
                                            <td style="font-weight: bold;"><?= $countQuotes ?></td>
                                            <td style="font-weight: bold;"><?= $quote['gy_customer_name']; ?></td>
                                            <td style="font-weight: bold;"><?= $encoder; ?></td>
                                            <td><center><button type="button" class="btn btn-success" title="click to see view the note ..." data-target="#details_<?= $quote['gy_rqt_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete the restock summary ..." data-target="#void_<?= $quote['gy_rqt_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?= $quote['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">NOTE</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p style="text-align: justify;">
                                                                        <?= $quote['gy_rqt_note']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?= $quote['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete Quotation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_request_summ?cd=<?= $quote['gy_rqt_code']; ?>&sd=request_reports">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Delete Secure PIN</label>
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
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- <script type="text/javascript">
        $('#restock_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
