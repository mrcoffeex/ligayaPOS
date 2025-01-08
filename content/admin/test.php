<?php
    require '../../barcode-generator/src/BarcodeGenerator.php';
    require '../../barcode-generator/src/BarcodeGeneratorPNG.php';
    require '../../barcode-generator/src/BarcodeGeneratorHTML.php';

    // Generate a barcode
    $barcodeText = '1234567890'; // The text you want to encode

    // HTML-based barcode
    // $generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
    // $htmlBarcode = $generatorHTML->getBarcode($barcodeText, $generatorHTML::TYPE_CODE_128);

    // echo '<h3>HTML Barcode:</h3>';
    // echo $htmlBarcode;

    // PNG-based barcode
    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
    $barcodePNG = $generatorPNG->getBarcode($barcodeText, $generatorPNG::TYPE_CODE_128);

    // Output as an image
    header('Content-Type: image/png');
    echo $barcodePNG;
?>