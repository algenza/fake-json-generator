<?php

use Algenza\Fjg\Generator;
use PHPUnit\Framework\TestCase;

class FakerTest extends PHPUnit_Framework_TestCase
{
	private function getPathList()
	{
		$filesDir = __DIR__.'/testfile/';

		return [
		'faker' => $filesDir.'faker.json',
		'faker_result' => $filesDir.'faker_created.json'
		];

	}

	public function testFaker()
	{
		$path = $this->getPathList();

		$schemaPath = $path['faker'];
		$jsonTargetPath = $path['faker_result'];

		$generator = new Generator($schemaPath, $jsonTargetPath);

		$generator->run();	
	}
}