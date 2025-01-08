<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $encoder = @$_GET['encoder'];

    $my_project_header_title="QUOTATION #" . $my_dir_value;

    $my_query="SELECT * From gy_rqt Where gy_rqt_code = '$my_dir_value' Order By gy_product_name ASC";
    $getnote=$link->query($my_query);
    $noterow=$getnote->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>

    <style type="text/css">
        img{
            max-width:180px;
        }

        input[type=file]{
            padding:0px;
        }

        @media print{
            .no-print{
                display: none !important;
            }

            .my_hr{
                height: 5px;
                color: #000;
                background-color: #000;
                border: none;
            }

            td{
                background-color: rgba(255,255,255, 0.1);
            }
        }

        .my_hr{
            height: 5px;
            color: #000;
            background-color: #000;
            border: none;
        }

        td{
            background-color: rgba(255,255,255, 0.1);
            font-size: 12px;
        }
    </style>

    <script type="text/javascript">
        window.print();
    </script>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 no-print">
                            <button type="button" onclick="window.print();" class="btn btn-primary" title="click to print ..."><i class="fa fa-print fa-fw"></i> Print Result</button>
                        </div>
                        <div class="col-md-12">
                            <h4 style="font-weight: bold; margin-bottom: -10px; margin-top: 30px;"><center><?= $my_project_name; ?></center>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><?= $my_project_header_title; ?></span><br>
                                <span style="font-size: 13px;">Date Printed: <?= date("F d, Y g:i:s A"); ?></span>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th colspan="7">
                                            <i><small>Note: <?= $noterow['gy_rqt_note'];; ?></small></i>
                                            <span class="pull-right">Customer: <?= $noterow['gy_customer_name'] ? $noterow['gy_customer_name'] : "none" ?></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>No.</th>              
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Price SRP</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        $numrow = 0;
                                        $grandTotal = 0;
                                        $getQuotations=$link->query($my_query);
                                        while ($quotation=$getQuotations->fetch_array()) {
                                            @$numrow++;    
                                            $subTotal = $quotation['gy_product_price_srp'] * $quotation['gy_rqt_quantity'];
                                            $grandTotal += $subTotal;
                                    ?>                 
                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?= $numrow; ?></td>
                                        <td class="pla" style="text-transform: uppercase; padding: 4px;"><?= $quotation['gy_product_code']; ?></td>
                                        <td class="pla" style="text-transform: uppercase; font-weight: bold; font-size: 12px; padding: 4px;"><?= $quotation['gy_product_name']; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?= RealNumber($quotation['gy_product_price_srp'], 2) ?></td>
                                        <td class="pla" style="padding: 4px; font-weight: bold;"><?= $quotation['gy_rqt_quantity']." <span style='color: black; font-weight: normal;'>". getProductUnitByCode($quotation['gy_product_code']) ."</span>"; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?= RealNumber($subTotal, 2) ?></td>
                                    </tr>
                                <?php  
                                    }
                                ?>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="pla text-bold" style="padding: 4px;">Total</td>
                                    <td class="pla text-bold" style="padding: 4px;"><?= RealNumber($grandTotal, 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <p>Encoded By: <?= $encoder; ?></p>
                        </div>
                        <!-- <div class="col-md-4 col-xs-4">
                            <p>Checked By: _______________</p>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <p>Approved By: _______________</p>
                        </div> -->
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>