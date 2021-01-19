<?php
/**
 * Class QRImageWithText
 *
 * example for additional text
 *
 * @link         https://github.com/chillerlan/php-qrcode/issues/35
 *
 * @filesource   QRImageWithText.php
 * @created      22.06.2019
 * @package      chillerlan\QRCodeExamples
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2019 smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace App\Classes;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRImage;
use chillerlan\Settings\SettingsContainerInterface;

use function base64_encode, imagechar, imagecolorallocate, imagecolortransparent, imagecopymerge, imagecreatetruecolor,
	imagedestroy, imagefilledrectangle, imagefontwidth, in_array, round, str_split, strlen;

class QRImageWithText extends QRImage{

    protected $Author;
    
    public function __construct(SettingsContainerInterface $options, QRMatrix $matrix, string $author = '')
    {
        parent::__construct($options, $matrix);
        $this->Author = $author;
    }
	/**
	 * @param string|null $file
	 * @param string|null $text
	 *
	 * @return string
	 */
	public function dump(string $file = null, string $header = null):string{
		// set returnResource to true to skip further processing for now
		$this->options->returnResource = true;

		// there's no need to save the result of dump() into $this->image here
		parent::dump($file);

		// render text output if a string is given
		if($header !== null){
			$this->addAuthorInformation($header);
		}
		$imageData = $this->dumpImage();

		if($file !== null){
			$this->saveToFile($imageData, $file);
		}
		if($this->options->imageBase64){
			$imageData = 'data:image/'.$this->options->outputType.';base64,'.base64_encode($imageData);
		}
		return $imageData;
	}
    /**
	 * @param string $text
	 */
	protected function addAuthorInformation(string $header):void{
		// save the qrcode image
		$qrcode = $this->image;

        $names = Helper::GetNames($this->Author);
        $authorName = Helper::GetMaxSizeOfName($names);
        $textWithMaxSize = strlen($authorName) > strlen($header) ? $authorName : $header;
        // options things
        $posY = 60;
        $textSize = 10; // see imagefontheight() and imagefontwidth()
        $textSizeAuthor = 20;
		$textBG = [255, 255, 255];
        
        $additionalSpace = $this->GetLength($textWithMaxSize, $textSize);
		$Width  = $this->length + $additionalSpace;
		$Height = $this->length;

		// create a new image with additional space
		$this->image = imagecreatetruecolor($Width, $Height);
		$background  = imagecolorallocate($this->image, ...$textBG);

		// fill the background
		imagefilledrectangle($this->image, 0, 0, $Width, $Height, $background);

		// copy over the qrcode
		imagecopymerge($this->image, $qrcode, 0, 0, 0, 0, $this->length, $this->length, 100);
		imagedestroy($qrcode);

		$w = imagefontwidth($textSize);
        $x = $this->length;

        $black = imagecolorallocate($this->image, 0, 0, 0);
        imagettftext($this->image, $textSize, 0, (int)($w + $x), $posY, $black, __DIR__.'/../../Fonts/Caviar-Dreams/CaviarDreams_BoldItalic.ttf', $header);

        $posY += imagefontwidth($textSizeAuthor) * 4;
        
        foreach ($names as $name) {
            $n = array_reduce($name, function($accumulator, $item){
                return $accumulator.$item.' ';
            });
            imagettftext($this->image, $textSizeAuthor, 0, $x, $posY, $black, __DIR__.'/../../Fonts/Caviar-Dreams/CaviarDreams_BoldItalic.ttf', $n);
            $posY += imagefontwidth($textSizeAuthor) * 4;
        }
    }
    private function GetLength(string $text, int $textSize)
    {
        return (int)(strlen($text) * imagefontwidth($textSize));
    }
	/**
	 * @param string $text
	 */
    /*protected function addText(string $text):void
    {
		// save the qrcode image
		$qrcode = $this->image;

		// options things
		$textSize  = 3; // see imagefontheight() and imagefontwidth()
		$textBG    = [255, 255, 255];
		$textColor = [0, 0, 0];

		$bgWidth  = $this->length;// + 60;
		$bgHeight = $bgWidth + 20; // 20px extra space
        
		// create a new image with additional space
		$this->image = imagecreatetruecolor($bgWidth, $bgHeight);
		$background  = imagecolorallocate($this->image, ...$textBG);

        // allow transparency
		if($this->options->imageTransparent && in_array($this->options->outputType, $this::TRANSPARENCY_TYPES, true)){
			imagecolortransparent($this->image, $background);
		}

		// fill the background
		imagefilledrectangle($this->image, 0, 0, $bgWidth, $bgHeight, $background);

		// copy over the qrcode
		imagecopymerge($this->image, $qrcode, 0, 0, 0, 0, $this->length, $this->length, 100);
		imagedestroy($qrcode);

		$fontColor = imagecolorallocate($this->image, ...$textColor);
		$w         = imagefontwidth($textSize);
        $x         = round(($bgWidth - strlen($text) * $w) / 2);
        
		// loop through the string and draw the letters
		foreach(str_split($text) as $i => $chr){
			imagechar($this->image, $textSize, (int)($i * $w + $x), $this->length, $chr, $fontColor);
		}
	}*/
}