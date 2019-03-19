<?php

use Algenza\Fjg\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
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
		'schema_path4' => $filesDir.'schema4.json',
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
		$this->assertTrue(is_file($path['json_target']));
	}

	public function testRunForceValidCount()
	{
		$path = $this->getPathList();

		$schemaPath = $path['schema_path4'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();
		$this->assertTrue(is_file($path['json_target']));
	}

	public function testRunInvalidSchemaType()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$schemaPath = $path['schema_path2'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();	
		
	}

	public function testRunInvalidSchemaNoType()
	{
		$this->expectException(\Exception::class);
		$path = $this->getPathList();

		$schemaPath = $path['schema_path3'];
		$jsonTargetPath = $path['json_target'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();		
	}
}
