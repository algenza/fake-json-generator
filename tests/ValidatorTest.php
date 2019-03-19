<?php

use Algenza\Fjg\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
	private function getPathList()
	{
		$filesDir = __DIR__.'/testfile/';

		return [
		'valid' => $filesDir.'valid.json',
		'invalid' => $filesDir.'invalid.json',
		'invalid2' => $filesDir.'invalid2.json',
		'invalid3' => $filesDir.'invalid3.json',
		'invalid_ext' => $filesDir.'invalidExtension.nosj',
		'notfound' => $filesDir.'notfound.json'
		];

	}

	public function testValidPath()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$this->assertEmpty($validator->validatePath($path['valid']));
		$this->assertEmpty($validator->validatePath($path['invalid_ext']));
	}

	public function testValidPathWithException()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validatePath($path['notfound']);
	}

	public function testValidExtension()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$this->assertEmpty($validator->validateExtension($path['valid']));
	}


	public function testValidExtensionWithException()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateExtension($path['invalid_ext']);
	}

	public function testValidJson()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$this->assertEmpty($validator->validateJson($path['valid']));
	}

	public function testValidJsonWithException()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid']);
	}


	public function testValidJsonWithException2()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid2']);
	}

	public function testValidJsonWithException3()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid3']);
	}
}