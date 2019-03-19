<?php

use Algenza\Fjg\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
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
		'json_target' => $filesDir.'db.json',
		];
	}

	public function testParse()
	{
		$path = $this->getPathList();

		$schemaPath = $path['schema_path'];
		$jsonTargetPath = $path['json_target'];

		$parser = new Parser($schemaPath);
		$parsedSchema = $parser->parse();
		$this->assertInstanceOf(stdClass::class,$parsedSchema);
	}	

}