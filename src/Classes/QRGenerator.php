<?php

namespace App\Classes;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

//define('QR_IMG_NAME', '/files/QR.png');

class QRGenerator
{
    public function __construct(){}
    public function HelloWorld()
    {
        return "Hola mundo";
    }
    /**
     * save the QR image as PNG format
     *
     * @param array $info
     */
    public function Generate($info)
    {
        if (!array_key_exists('subject', $info)) throw new \Exception("no existe el campo subject");
        if (!is_array($info['subject'])) throw new \Exception("el campo subject no es un arreglo");
        if (!array_key_exists('CN', $info['subject'])) throw new \Exception("no existe el campo subject");

        $name = $info['subject']['CN'];
        $now = date("c");
        $data = "FIRMADO POR: $name".PHP_EOL;
        $data .= "RAZON:".PHP_EOL;
        $data .= "LOCALIZACION:".PHP_EOL;
        $data .= 'FECHA:'.PHP_EOL.$now.PHP_EOL;
        $data .= 'VALIDAR CON:'.PHP_EOL.'www.firmadigital.gob.ec'.PHP_EOL;
        $data .= '2.7.1';
        $options = new QROptions([
            'version'          => QRCode::VERSION_AUTO,
            'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'         => QRCode::ECC_H,
            'scale'            => 4,
            'imageBase64'      => false,
            'imageTransparent' => false,
        ]);
        
        // invoke a fresh QRCode instance
        $qrcode = new QRCode($options);

        // ...with additional cache filES        
        $qrcode->render($data, __DIR__.'/../../files/QR.png');
    }
}