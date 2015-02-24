<?php

/*!
 * Twig Pattern Engine Loader Class - Patterns
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Sets an instance of Twig to deal with patterns. Tries to find
 * files on system first. If not tries to load them as strings.
 *
 */

namespace PatternLab\PatternEngine\Twig\Loaders;

use \PatternLab\Config;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternPartialLoader as Twig_Loader_PatternPartialLoader;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternStringLoader as Twig_Loader_PatternStringLoader;
use \PatternLab\PatternEngine\Loader;

class PatternLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the Pattern Loader
	*/
	public function __construct($options = array()) {
		
		//default var
		$patternSourceDir     = Config::getOption("patternSourceDir");
		$patternPartialLoader = new Twig_Loader_PatternPartialLoader($patternSourceDir,array("patternPaths" => $options["patternPaths"]));
		$patternStringLoader  = new \Twig_Loader_String();
		$twigLoader           = new \Twig_Loader_Chain(array($patternPartialLoader, $patternStringLoader));
		$this->instance       = new \Twig_Environment($twigLoader);
		
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
