<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $productId = @$_GET['productId'];
    
    $getProduct=selectProduct($productId);
    $product=$getProduct->fetch_array();

    $my_project_header_title = $product['gy_product_name'];

    $getSupplier=selectSupplier($product['gy_supplier_code']);
    $supplier=$getSupplier->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <div id="page-wrapper" style="margin-left: 0px; padding-top: 20px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                <span style="font-size: 20px; font-weight: bold;">
                                    <i class="fa fa-dropbox"></i> <?php echo $my_project_header_title; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-heading text-bold text-uppercase">Product Details</div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tbody>
                                            <tr class="text-bold">
                                                <td class="text-center">Code</td>
                                                <td><?= $product['gy_product_code'] ?></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="text-center">Category</td>
                                                <td><?= $product['gy_product_cat'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Description</td>
                                                <td><?= $product['gy_product_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Details</td>
                                                <td><?= $product['gy_product_desc'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Registered</td>
                                                <td>
                                                    <?= 
                                                    properDateWithDay($product['gy_product_date_reg']) . " " . proper_time($product['gy_product_date_reg'])
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Updated</td>
                                                <td>
                                                    <?= 
                                                    properDateWithDay($product['gy_product_update_date']) . " " . proper_time($product['gy_product_update_date'])
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Restocked</td>
                                                <td>
                                                    <?= 
                                                    properDateWithDay($product['gy_product_date_restock']) . " " . proper_time($product['gy_product_date_restock'])
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Restock Limit</td>
                                                <td>
                                                    <?= 
                                                        $product['gy_product_restock_limit']." ".$product['gy_product_unit'] 
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Unit</td>
                                                <td><?= $product['gy_product_unit'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">ConvertItem</td>
                                                <td><?= $product['gy_convert_item_code'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">ConvertValue</td>
                                                <td><?= $product['gy_convert_value'] ?></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="text-center">CAP</td>
                                                <td><?= toAlpha($product['gy_product_price_cap']) ?></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="text-center">SRP</td>
                                                <td><?= RealNumber($product['gy_product_price_srp'], 2) ?></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="text-center">QTY</td>
                                                <td><?= $product['gy_product_quantity']." ".$product['gy_product_unit'] ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading text-bold text-uppercase">Supplier</div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tbody>
                                            <tr class="text-bold">
                                                <td class="text-center">SupplierCode</td>
                                                <td><?= none_if_empty($supplier['gy_supplier_code']) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Supplier</td>
                                                <td><?= none_if_empty($supplier['gy_supplier_name']) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Description</td>
                                                <td><?= none_if_empty($supplier['gy_supplier_desc']) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Address</td>
                                                <td><?= none_if_empty($supplier['gy_supplier_address']) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Contact #</td>
                                                <td><?= none_if_empty($supplier['gy_supplier_contact']) ?></td>
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