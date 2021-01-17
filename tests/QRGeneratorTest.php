<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\QRGenerator;
use PHPUnit\Framework\TestCase;

final class QRGeneratorTest extends TestCase
{
	public function testPrintHelloWorld() {
		$actualClass = new QRGenerator();
        $this->assertEquals("Hola mundo", $actualClass->HelloWorld());
	}
}
