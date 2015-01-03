<?php

/*!
 * Mustache Pattern Engine Loader Class - Patterns
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Sets an instance of Mustache to deal with rendering of patterns
 *
 */

namespace PatternLab\PatternEngine\Twig\Loaders;

use \PatternLab\Config;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternLoader as Twig_Loader_PatternLoader;
use \PatternLab\PatternEngine\Loader;

class PatternLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the Pattern Loader
	*/
	public function __construct($options = array()) {
		
		//default var
		$patternSourceDir = Config::getOption("patternSourceDir");
		$twigLoader       = new Twig_Loader_PatternLoader($patternSourceDir,array("patternPaths" => $options["patternPaths"]));
		$this->instance   = new \Twig_Environment($twigLoader);
		
	}
	
	/**
	* Render a pattern
	* @param  {Array}        the options to be rendered by Twig
	*
	* @return {String}       the rendered result
	*/
	public function render($options = array()) {
		
		return $this->instance->render($options["pattern"], $options["data"]);
		
	}
	
}
