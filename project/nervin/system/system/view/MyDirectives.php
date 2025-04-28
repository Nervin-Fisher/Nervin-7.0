<?php

namespace system\view;

class MyDirectives
{
	public $array;

	public function __construct($var = [])
	{
		$this->array = $var;
	}
}
