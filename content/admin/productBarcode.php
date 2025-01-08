<?php
    require '../../barcode-generator/src/BarcodeGenerator.php';
    require '../../barcode-generator/src/BarcodeGeneratorPNG.php';

    $productCode = @$_GET['productCode'];

    if ($productCode == "" || $productCode === null) {
        header("location: 404");
        exit;
    }

    // PNG-based barcode
    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
    $barcodePNG = $generatorPNG->getBarcode($productCode, $generatorPNG::TYPE_CODE_128);

    // Create an image with GD library
    $barcodeImage = imagecreatefromstring($barcodePNG);
    if (!$barcodeImage) {
        die('Failed to create barcode image.');
    }

    // Get dimensions of the barcode image
    $barcodeWidth = imagesx($barcodeImage);
    $barcodeHeight = imagesy($barcodeImage);

    // Create a new image with extra space for text
    $fontHeight = 20; // Height for the text
    $totalHeight = $barcodeHeight + $fontHeight + 5; // Add padding
    $outputImage = imagecreatetruecolor($barcodeWidth, $totalHeight);

    // Set background to white
    $white = imagecolorallocate($outputImage, 255, 255, 255);
    imagefilledrectangle($outputImage, 0, 0, $barcodeWidth, $totalHeight, $white);

    // Copy barcode onto the new image
    imagecopy($outputImage, $barcodeImage, 0, 0, 0, 0, $barcodeWidth, $barcodeHeight);

    // Add text below the barcode
    $black = imagecolorallocate($outputImage, 0, 0, 0);
    $fontFile = __DIR__ . '/arial.ttf'; // Path to your TrueType font
    $fontSize = 10; // Font size
    $textX = ($barcodeWidth - imagefontwidth($fontSize) * strlen($productCode)) / 2; // Center text
    $textY = $barcodeHeight + 15; // Position text below the barcode

    imagettftext($outputImage, $fontSize, 0, $textX, $textY, $black, $fontFile, $productCode);

    // Output the final image
    header('Content-Type: image/png');
    imagepng($outputImage);

    // Free memory
    imagedestroy($barcodeImage);
    imagedestroy($outputImage);
?>
