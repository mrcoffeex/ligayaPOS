<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = $_GET['search_text'];

    $my_project_header_title = "Search Album: " . $search_text;

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
                    <h3 class="page-header" style="text-transform: uppercase;"><i class="fa fa-image"></i> Album</h3>
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
                <form action="redirect_manager" enctype="multipart/form-data" method="post">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="searchAlbum" id="searchAlbum" placeholder="search here ..." autofocus required>
                    </div>
                </form>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <p><?= countAlbumSearch($search_text, 15) ?> results(s)</p>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <?php  
                            $getAlbum = selectAlbumSearch($search_text, 15);
                            while ($album=$getAlbum->fetch_array()) {
                        ?>
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="<?= displayImage($album['gy_product_image'], '../../img/no_image.jpg', '../../mrcoffeexpicturebox/') ?>" class="img-fluid" style="height: 300px; width: 190px;" alt="">
                                        </div>
                                        <div class="col-md-12">
                                            <?= $album['gy_product_name'] ?> - <span class="text-primary text-bold"><?= $album['gy_product_quantity'] ?></span>
                                        </div>
                                        <div class="col-md-12">
                                            <b><?= $album['gy_product_code'] ?></b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
