<?php
namespace Algenza\Fjg;

class Parser
{
	private $file;
	public function __construct($filePath)
	{
		$this->file = $filePath;
	}

	public function parse()
	{
		$fileContent = file_get_contents($this->file);
		$parsedData = json_decode($fileContent);

		return $parsedData;
	}
}