<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_project_header_title = "Upload Products";

    $note=@$_GET['note'];
    $select=@$_GET['select'];

    if ($note == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Error";
    } else if ($note == "upload_success") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Upload Successful";
    } else if ($note == "file_not_allowed") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "File not found";
    } else {
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }
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

        .files input {
            outline: 2px dashed #92b0b3;
            outline-offset: -10px;
            -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
            transition: outline-offset .15s ease-in-out, background-color .15s linear;
            padding: 120px 0px 85px 35%;
            text-align: center !important;
            margin: 0;
            width: 100% !important;
        }

        .files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
            -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
            transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
         }

        .files{ position:relative}

        .files:after {  pointer-events: none;
            position: absolute;
            top: 60px;
            left: 0;
            width: 50px;
            right: 0;
            height: 56px;
            content: "";
            background-image: url(../../img/upload.png);
            display: block;
            margin: 0 auto;
            background-size: 100%;
            background-repeat: no-repeat;
        }

        .color input{ background-color:#f1f1f1;}

        .files:before {
            position: absolute;
            bottom: 10px;
            left: 0;  pointer-events: none;
            width: 100%;
            right: 0;
            height: 57px;
            content: " or drag it here. ";
            display: block;
            margin: 0 auto;
            color: #2ea591;
            font-weight: 600;
            text-transform: capitalize;
            text-align: center;
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
                        <div class="col-md-8">
                            <br>
                            <p>
                                <a href="./">
                                    <button type="button" class="btn btn-primary btn-sm">Home</button>
                                </a>
                                <span style="font-size: 17px; font-weight: bold;">
                                    <i class="fa fa-dropbox"></i> <?= $my_project_header_title; ?>
                                </span>
                                <br>
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <div class="alert alert-<?= @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?= @$the_note_status; ?>">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?= @$message; ?>.
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <center><b><i class="fa fa-money"></i> Upload Products</b></center>
                                        </div>
                                        <div class="panel-body">
                                            <form method="post" enctype="multipart/form-data" action="pricing_upload" onsubmit="return upload_comeon(this);">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group files">
                                                        <input type="file" name="file" class="form-control" accept=".csv" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit" name="upload_pricing" id="upload_pricing" class="btn btn-success btn-block">Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                        <thead>
                                            <tr class="info">
                                                <th><center>Category</center></th>
                                                <th><center>Products</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $totals=0;
                                                $category=$link->query("SELECT * From `gy_category`");
                                                while ($categoryrow=$category->fetch_array()) {
                                                    $totals += count_cat_qty($categoryrow['gy_cat_name'], "");
                                            ?>                  
                                            <tr>
                                                <td style="font-weight: bold;"><center><?= $categoryrow['gy_cat_name']; ?></center></td>
                                                <td style="font-weight: bold;"><center><?= count_cat_qty($categoryrow['gy_cat_name'], "") ?></center></td>
                                            </tr>
                                                        
                                        <?php } ?>    

                                            <tr>
                                                <td style="font-weight: bold; color: blue;"><center>Total</center></td>
                                                <td style="font-weight: bold; color: blue;"><center><?= $totals ?></center></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <center><b><i class="fa fa-download"></i> Download CSV File</b></center>
                                        </div>
                                        <div class="panel-body">
                                            <form method="post" enctype="multipart/form-data" action="pricing_download">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" name="download_pricing" class="btn btn-info btn-block"><i class="fa fa-download"></i> Download File</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

    <script type="text/javascript">
        function upload_comeon(formObj){
            formObj.upload_pricing.disabled = true;
            formObj.upload_pricing.innerHTML = "Uploading data ...";
            return true;  
        }
    </script>

</body>

</html>