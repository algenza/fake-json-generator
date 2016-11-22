<?php

use Algenza\Fjg\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends PHPUnit_Framework_TestCase
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
		'notfound' => $filesDir.'notfound.json',
		'schema_path' => $filesDir.'schema.json',
		'schema_path2' => $filesDir.'schema2.json',
		'schema_path3' => $filesDir.'schema3.json',
		'json_target' => $filesDir.'db.json',
		];
	}

	public function testRun()
	{
		$path = $this->getPathList();

		$schemaPath = $path['schema_path'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();		
	}
	/**
    * @expectedException \Exception
    */
	public function testRunInvalidSchemaType()
	{
		$path = $this->getPathList();

		$schemaPath = $path['schema_path2'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();		
	}
	/**
    * @expectedException \Exception
    */
	public function testRunInvalidSchemaNoType()
	{
		$path = $this->getPathList();

		$schemaPath = $path['schema_path3'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();		
	}
}