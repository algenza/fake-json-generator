<?php
namespace Algenza\Fjg;

use \Exception;

class Validator
{
	public function validatePath($path)
	{
		if(!file_exists($path)){
			throw new \Exception("File Not Found: ".$path, 1);			
		}
	}

	public function validateExtension($path)
	{
		$jsonPathParts = pathinfo($path);
		if($jsonPathParts['extension'] !== 'json'){
			throw new \Exception("File must use '.json' extension: ".$path, 1);			
		}
	}

	public function validateJson($path)
	{
		$fileContent = file_get_contents($path);
		
		if(!$this->checkJsonFirstChar(substr($fileContent,0,1))){
			throw new \Exception("Invalid Json File:".$path, 1);
		}

		if(!$this->isJson($fileContent)){
			throw new \Exception("Invalid Json File:".$path, 1);
		}
	}

	private function isJson($fileContent)
	{
		$result = json_decode($fileContent);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	private function checkJsonFirstChar($char)
	{
		if($char === '{'){
			return true;
		}
		return false;
	}
}