<?php

use Algenza\Fjg\Generator;
use PHPUnit\Framework\TestCase;

class FakerTest extends TestCase
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

		$generator = new Generator($schemaPath, $jsonTargetPath,'id_ID');

		$generator->run();
		$this->assertTrue(is_file($path['faker_result']));
	}	
}