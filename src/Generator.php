<?php
namespace Algenza\Fjg;

use Algenza\Fjg\Validator;
use Algenza\Fjg\Jsonizer;
use \Exception;

class Generator
{
	private $schemaPath;
	private $jsonFile;
	private $validator;
	private $parser;
	private $locale;

	public function __construct($pathToSchema, $pathToJson, $locale = 'en_US')
	{
		$this->schemaPath = $pathToSchema;
		$this->jsonFile = $pathToJson;
		$this->locale = $locale;;
		$this->validator = new Validator;
		
	}

	public function run()
	{
		try{
			$this->validator->validatePath($this->schemaPath);
			$this->validator->validateExtension($this->jsonFile);
			$this->validator->validateJson($this->schemaPath);

			$parsedSchema = (new Parser($this->schemaPath))->parse();
			$jsonizer = new Jsonizer($parsedSchema, $this->jsonFile, $this->locale);
			$jsonizer->run();

		}catch (\Exception $e){
			throw new Exception($e->getMessage(), 1);
			die;
		}
	}
}