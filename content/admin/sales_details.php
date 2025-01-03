<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $transCode = @$_GET['transCode'];

    $my_project_header_title = "Transaction ".$transCode;

    $getTransaction = selectTransaction($transCode);
    $transaction=$getTransaction->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p class="text-center">
                                <span style="font-size: 20px; font-weight: bold;">
                                    <i class="fa fa-file-text-o"></i> <?= $my_project_header_title; ?>
                                </span>
                                <br>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 0px;">

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center text-bold text-uppercase">Transaction Details</div>
                                        <div class="panel-body">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">Code</td>
                                                        <td><?= $transaction['gy_trans_code'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Customer</td>
                                                        <td><?= $transaction['gy_trans_custname'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Date</td>
                                                        <td><?= properDateWithDay($transaction['gy_trans_date'])." ".proper_time($transaction['gy_trans_date']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PreparedBy</td>
                                                        <td><?= getUserFullnameById($transaction['gy_prepared_by']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Cashier</td>
                                                        <td><?= getUserFullnameById($transaction['gy_user_id']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Payment Method</td>
                                                        <td><?= paymentMethod($transaction['gy_trans_pay']) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                        <thead>
                                            <tr class="info">
                                                <th>QTY</th>
                                                <th>Item</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-right">SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $itemTotal=0;
                                                $getItems = getSalesDetails($transCode);
                                                while ($item=$getItems->fetch_array()) {

                                                    $subTotal = $item['gy_product_price'] * $item['gy_trans_quantity'];
                                                    $itemTotal += $subTotal;
                                            ?>                  
                                            <tr>
                                                <td>
                                                    <span class="text-bold text-primary"><?= $item['gy_trans_quantity'] ?></span> 
                                                    <span class="pull-right"><?= getProductUnit($item['gy_product_id']) ?></span>
                                                </td>
                                                <td><?= getProductName($item['gy_product_id']) ?></td>
                                                <td class="text-center"><?= RealNumber($item['gy_product_price'], 2) ?></td>
                                                <td class="text-right"><?= RealNumber($subTotal, 2) ?></td>
                                            </tr>
                                                        
                                        <?php } ?>

                                            <?php  
                                                $grand_total = $itemTotal;
                                            ?>
                                                    
                                            <tr>
                                                <td class="text-center text-bold text-uppercase"></td>
                                                <td class="text-center text-bold text-uppercase" colspan="2">Total</td>
                                                <td class="text-right text-bold"><?= RealNumber($grand_total, 2) ?></td>
                                            </tr>
                                                    
                                            <tr>
                                                <td class="text-center text-bold text-uppercase"></td>
                                                <td class="text-center text-bold text-uppercase" colspan="2">Cash</td>
                                                <td class="text-right text-bold"><?= RealNumber($transaction['gy_trans_cash'], 2) ?></td>
                                            </tr>
                                                    
                                            <tr>
                                                <td class="text-center text-bold text-uppercase"></td>
                                                <td class="text-center text-bold text-uppercase" colspan="2">Change</td>
                                                <td class="text-right text-bold"><?= RealNumber($transaction['gy_trans_change'], 2) ?></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>