<?php
namespace Algenza\Fjg;

use Faker\Factory;

class Jsonizer
{
	private $schema;
	private $definitions = [];
	private $required;
	private $faker;
	private $jsonFilePath;

	private $defaultMin = 5;
	private $defaultMax = 10;

	private $systemMax = 100;

	function __construct(\stdClass $schema, $jsonFile)
	{
		$this->schema = $schema;
		$this->jsonFilePath = $jsonFile;
		$this->faker = Factory::create();
		$this->jsonContent = new \stdClass();
	}

	public function run()
	{
		if(!isset($this->schema->type) || !isset($this->schema->required) || !isset($this->schema->properties)){
			throw new \Exception("'type', 'required' and 'properties' must be defined in schema", 1);			
		}

		if($this->schema->type !== 'object'){
			throw new \Exception("Invalid schema : 'type' must be 'object'", 1);
		}

		if(isset($this->schema->definitions)){
			$this->definitions = $this->schema->definitions;
		}

		foreach ($this->schema->properties as $key => $value) {
			$this->jsonContent->{$key} = $this->trackdown($value);
		}

		$toJson = json_encode($this->jsonContent,JSON_PRETTY_PRINT);

		file_put_contents($this->jsonFilePath,$toJson);
	}

	private function trackdown($value)
	{
		$generateAmount = $this->decideItemCount($value);

		if($value->type == 'object'){
			$objectCols = [];

			for ($i=0; $i < $generateAmount; $i++) { 
				$objectCols[] = $this->generateObj($value->properties);
			}
			return $objectCols;
		}else if($value->type == 'array'){
			$arrayCols = [];
			for ($i=0; $i < $generateAmount; $i++) { 
				$arrayCols[] = $this->trackdown($value->items);
			}
			return $arrayCols;
		}else{
			$unique = false;
			if(isset($value->unique)){
				if($value->unique){
					$unique = true;
				}
			}
			if(preg_match("/^faker.[a-zA-z]+[0-9a-zA-Z]*$/i",$value->value)){
				return $this->fakerMaker($this->getFakerFormatter($value->value),$unique);
			}
			return $value->value;
		}
	}
	
	private function getFakerFormatter($value)
	{
		$splitter = explode('.',$value);
		return $splitter[1];
	}
	
	private function decideItemCount($value){
		if(!isset($value->minimum)){
			$value->minimum = $this->defaultMin;
		}

		if(!isset($value->maximum)){
			$value->maximum = $this->defaultMax;
		}

		if($value->maximum > $this->systemMax){
			$value->maximum = $this->systemMax;
		}

		if($value->minimum > $value->maximum){
			$value->minimum = $value->maximum;
		}

		if($value->minimum == $value->maximum){
			return $value->maximum;
		}

		return rand($value->minimum, $value->maximum);
	}

	private function generateObj($props)
	{	
		$newObj = new \stdClass();
		foreach ($props as $key => $value) {
			$newObj->{$key} = $this->trackdown($value);
		}
		return $newObj;
	}

	private function fakerMaker($formatter, $isUnique = false)
	{
		if($isUnique){
			return $this->faker->unique()->{$formatter};
		}
		return $this->faker->{$formatter};
	}
}