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
			if(in_array($key, $this->schema->required)){
				$this->jsonContent->{$key} = $this->trackdown($value,false, $this->faker);
			}
		}

		$toJson = json_encode($this->jsonContent,JSON_PRETTY_PRINT);

		file_put_contents($this->jsonFilePath,$toJson);
	}

	private function trackdown($value, $isArray = false, $generator)
	{
		$generateAmount = $this->decideItemCount($value);
		if (isset($value->type)) {
			if($value->type == 'object'){
				$objectCols = [];
				// gc_collect_cycles();
				$faker = Factory::create();

				for ($i=0; $i < $generateAmount; $i++) { 
					$objectCols[] = $this->generateObj($value->properties, $faker);
				}
				return $objectCols;
			}else if($value->type == 'array'){
				$arrayCols = [];
				// gc_collect_cycles();
				$arrayFaker = Factory::create();
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
			}
			$this->faker = $generator;
			
			return $this->fakerMaker($this->getFakerFormatter($value->value), $unique, $optional, $args, $provider);
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

	private function fakerMaker($formatter, $isUnique = false, $optional = false, $param = [], $provider = null)
	{
		try {
			if(isset($provider)){
				$providerClass = '\\Faker\\Provider\\'.$provider['locale'].'\\'.$provider['formatter'];
				$this->faker->addProvider(new $providerClass($this->faker));
			}
			if($isUnique){
				$this->faker = $this->faker->unique(false,100);
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
		} catch (\Exception $e) {
			throw new \Exception($formatter.":".$e->getMessage(), 1);	
		}
	}

}