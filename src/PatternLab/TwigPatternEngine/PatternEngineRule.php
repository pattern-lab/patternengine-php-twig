<?php

/*!
 * Twig Pattern Engine Rule Class
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * If the test matches "twig" it will return an instance of the Twig Pattern Engine
 *
 */

namespace PatternLab\TwigPatternEngine;

use \PatternLab\Config;
use \PatternLab\TwigPatternEngine\TwigLoader;
use \PatternLab\PatternEngine\Rule;

class PatternEngineRule extends Rule {
	
	public function __construct($options) {
		
		parent::__construct($options);
		
		$this->engineProp = "twig";
		
	}
		
	public function getInstance($options) {
		
		$options = new TwigLoader(__DIR__."/../..".Config::$options["patternSourceDir"],array("patternPaths" => $options["patternPaths"]));
		
		return new \Twig_Environment($options);
		
	}
	
}
