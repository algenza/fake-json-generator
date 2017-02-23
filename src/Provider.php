<?php
namespace Algenza\Fjg;

class Provider extends \Faker\Provider\Base
{
	protected static $incremental = null;

	public function incrementInteger($start = 0, $reset = false)
	{
		if($reset){
			static::$incremental = $start;
		}
		if(is_null(static::$incremental)){
			static::$incremental = $start;
		}
		return static::$incremental++;
	}
}