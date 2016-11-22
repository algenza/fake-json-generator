<?php

use Algenza\Fjg\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends PHPUnit_Framework_TestCase
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
	/**
    * @expectedException \Exception
    */
	public function testValidPathWithException()
	{
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

	/**
    * @expectedException \Exception
    */
	public function testValidExtensionWithException()
	{
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
	/**
    * @expectedException \Exception
    */
	public function testValidJsonWithException()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid']);
	}

	/**
    * @expectedException \Exception
    */
	public function testValidJsonWithException2()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid2']);
	}
	/**
    * @expectedException \Exception
    */
	public function testValidJsonWithException3()
	{
		$path = $this->getPathList();

		$validator = new Validator;

		$validator->validateJson($path['invalid3']);
	}
}