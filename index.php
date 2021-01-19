<?php
// Autoload files using the Composer autoloader.
//php -S localhost:8000
use App\Classes\QRGenerator;
use App\Classes\QRImageWithText;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require_once __DIR__ . '/vendor/autoload.php';

/*$cert = file_get_contents('files/cert.crt');
$info = openssl_x509_parse($cert);

$generator = new QRGenerator();
try
{
    $generator->Generate($info);
} catch (\Exception $e) {
    exit($e);
}*/

$cert = file_get_contents('files/cert.crt');
$info = openssl_x509_parse($cert);
$name = $info['subject']['CN'];
$now = date("c");

$options = new QROptions([
	'version'      => 7,
	'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
	'scale'        => 5,
    'imageBase64'  => true,
    'imageTransparent' => false,
]);
$data = "FIRMADO POR: $name".PHP_EOL;
        $data .= "RAZON:".PHP_EOL;
        $data .= "LOCALIZACION:".PHP_EOL;
        $data .= 'FECHA:'.PHP_EOL.$now.PHP_EOL;
        $data .= 'VALIDAR CON:'.PHP_EOL.'www.firmadigital.gob.ec'.PHP_EOL;
        $data .= '2.7.1';
//header('Content-type: image/png');

$qrOutputInterface = new QRImageWithText($options, (new QRCode($options))->getMatrix($data), $name);
echo '<img src="'.$qrOutputInterface->dump(__DIR__.'/files/QR.png', 'Firmado electrónicamente por:').'"/>';
