<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $productCode = @$_GET['cd'];

    $itemname = getProductNameByCode($productCode);
    $itemunit = getProductUnitByCode($productCode);
    $productId = getProductId($productCode);

    $my_project_header_title = $itemname." Inventory";

    if (isset($_POST['dateFrom'])) {
        $dateFrom = words($_POST['dateFrom']);
        $dateTo = words($_POST['dateTo']);

        $getHistory=selectInventoryBreakdown($productId, $dateFrom, $dateTo);
        
    } else {
        $dateFrom = date("Y-m-d", strtotime("-30 days"));
        $dateTo = date("Y-m-d");

        $getHistory=selectInventoryBreakdown($productId, $dateFrom, $dateTo);
    }

    $countHistory=$getHistory->num_rows;
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
            font-size: 14px;
        }
    </style>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p style="font-size: 17px;" class="text-center">
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-dropbox"></i> <?= $my_project_header_title; ?><br>
                                <small><?= $countHistory ?> result(s)</small></span><br>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <form action="history?cd=<?= $productCode ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">From</label>
                                            <input type="date" class="form-control" name="dateFrom" id="dateFrom" value="<?= $dateFrom ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">To</label>
                                            <input type="date" class="form-control" name="dateTo" id="dateTo" value="<?= $dateTo ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-sm-12">&nbsp;</label>
                                            <button type="submit" id="searchHistory" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Qty <span class="text-primary"><?= getProductUnitByCode($productCode) ?></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                            while ($history=$getHistory->fetch_array()) {

                                            $dateDisplay = proper_date($history['trans_date']) . " - " . proper_time($history['trans_date']);
                                                
                                        ?>
                                        <tr class="info">
                                            <td><?= $dateDisplay ?></td>
                                            <td><?= $history['trans_code'] ?></td>
                                            <td class="text-center"><?= $history['source_table'] ?></td>
                                            <td class="text-center"><?= $history['trans_qty'] . " " . getInventoryArrowType($history['source_table']) ?></td>
                                        </tr>
                                                    
                                    <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading"><i class="fa fa-dropbox"></i> Inventory</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-12 text-center">
                                                            <div style="font-size: 30px; padding-top: 10px;">
                                                                Current Qty: <?= getProductQtyByCode($productCode) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <?php include '_alerts.php'; ?>

</body>

</html>