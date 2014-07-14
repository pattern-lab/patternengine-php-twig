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

namespace PatternLab\PatternEngine\Twig;

use \PatternLab\Config;
use \PatternLab\PatternEngine\Twig\PatternLoader;
use \PatternLab\PatternEngine\Rule;

class PatternEngineRule extends Rule {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->engineProp = "twig";
		
	}
	
	/**
	* Load a new Twig instance that uses the Pattern Loader
	*
	* @return {Object}       an instance of the Twig engine
	*/
	public function getPatternLoader($options = array()) {
		
		$twigLoader = new PatternLoader(Config::$options["patternSourceDir"],array("patternPaths" => $options["patternPaths"]));
		
		return new \Twig_Environment($twigLoader);
		
	}
	
	/**
	* Load a new Twig instance that uses the File System Loader
	*
	* @return {Object}       an instance of the Twig engine
	*/
	public function getFileSystemLoader($options = array()) {
		
		$twigLoader = new Twig_Loader_Filesystem(array($options["templatePath"],$options["partialsPath"]));
		
		return new \Twig_Environment($twigLoader);
		
	}
	
	/**
	* Load a new Twig instance that is just a vanilla Twig string rendering engine
	*
	* @return {Object}       an instance of the Twig engine
	*/
	public function getVanillaLoader($options = array()) {
		
		$twigLoader = new \Twig_Loader_String();
		
		return new \Twig_Environment($twigLoader);
		
	}
}
