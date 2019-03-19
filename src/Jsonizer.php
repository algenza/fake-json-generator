<?php
namespace Algenza\Fjg;

use Faker\Factory;

class Jsonizer
{
	private $schema;
	private $definitions = [];
	private $required;
	private $faker;
	private $arrayFaker;
	private $jsonFilePath;
	private $locale;
	private $formatters = [];
	private $resetList = ["incrementInteger","foreignKey"];

	private $defaultMin = 5;
	private $defaultMax = 10;

	private $systemMax = 100;

	function __construct(\stdClass $schema, $jsonFile, $locale)
	{
		$this->schema = $schema;
		$this->jsonFilePath = $jsonFile;
		$this->locale = $locale;
		$this->faker = Factory::create($locale);
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
			if(in_array($key, $this->schema->required)){
				$this->jsonContent->{$key} = $this->trackdown($value,false, $this->faker);
			}
		}

		$toJson = json_encode($this->jsonContent,JSON_PRETTY_PRINT);

		file_put_contents($this->jsonFilePath,$toJson);
	}
	public function collectFKList($source, $keyId = 'id')
	{
		$keyList = [];
		if(isset($this->jsonContent->{$source})){
			foreach ($this->jsonContent->{$source} as $srcObj) {
				if(isset($srcObj->{$keyId})){
					$keyList[] = $srcObj->{$keyId};
				}else{
					throw new Exception("FK Config Key not exist:".$keyId, 1);						
				}
			}
			return $keyList;		
		}
		throw new \Exception("FK Config source not exist :".$source, 1);
		
	}
	private function trackdown($value, $isArray = false, $generator)
	{
		$generateAmount = $this->decideItemCount($value);
		if (isset($value->type)) {
			if($value->type == 'object'){
				$objectCols = [];
				$faker = Factory::create($this->locale);
				$this->formatters = [];
				for ($i=0; $i < $generateAmount; $i++) { 
					$objectCols[] = $this->generateObj($value->properties, $faker);
				}
				return $objectCols;
			}else if($value->type == 'array'){
				$arrayCols = [];
				$arrayFaker = Factory::create($this->locale);
				for ($i=0; $i < $generateAmount; $i++) { 
					$arrayCols[] = $this->trackdown($value->items,true,$arrayFaker);
				}
				return $arrayCols;
			}
		}

		if(isset($value->definition)){
			if(isset($this->definitions->{$value->definition})){
				$value = $this->definitions->{$value->definition};
			}
		}

		$unique = false;
		if(isset($value->unique)){
			$unique = $value->unique;
		}
		$optional = false;
		if(isset($value->optional)){
			$optional = $value->optional;
		}
		if(preg_match("/^faker.[a-zA-z]+[0-9a-zA-Z]*$/i",$value->value)){
			$args = [];
			if(isset($value->args)){
				$args = $value->args;
			}
			$provider = null;
			if(isset($value->provider)){
				$provider = (array)$value->provider;
				if(isset($value->fkConfig)){
					if(!isset($value->fkConfig->source)){
						throw new \Exception("FK Config needs source schema", 1);						
					}
					if(!isset($value->fkConfig->key)){
						$value->fkConfig->key = 'id';
					}
					$args [] = $this->collectFKList($value->fkConfig->source,$value->fkConfig->key);
				}
			}
			$this->faker = $generator;
			$formatter = $this->getFakerFormatter($value->value);
			$reset = false;
			if(!isset($this->formatters[$formatter])){
				$this->formatters[$formatter] = true;
				if($unique){
					$reset = true;
				}
				if(in_array($formatter, $this->resetList)){
					$args[] = true;
				}
			}
			return $this->fakerMaker($formatter, $unique, $optional, $args, $provider, $reset);
		}

		return $value->value;
		
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

	private function generateObj($props, $generator)
	{	
		$newObj = new \stdClass();
		foreach ($props as $key => $value) {
			$newObj->{$key} = $this->trackdown($value, false, $generator);
		}
		return $newObj;
	}

	private function fakerMaker($formatter, $isUnique = false, $optional = false, $param = [], $provider = null, $reset = false)
	{

		if(isset($provider)){
			$this->faker->addProvider(new $provider['source']($this->faker));
		}
		if($isUnique){
			$this->faker = $this->faker->unique($reset,100);
		}
		if($optional){
			$FakeOption = $this->faker->optional(
				(isset($optional->weight))?$optional->weight:0.5,
				(isset($optional->default))?$optional->default:null
				);
			if($FakeOption instanceof \Faker\DefaultGenerator){
				return $FakeOption->default;
			}
			$this->faker = $FakeOption;
		}
		if(isset($param)){
			return call_user_func_array([$this->faker, $formatter],$param);			
		}
		return call_user_func([$this->faker, $formatter]);			

	}

}