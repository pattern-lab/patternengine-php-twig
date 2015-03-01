<?php

/*!
 * Twig Pattern Engine Loader Class - String
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Sets an instance of Twig to deal with rendering of strings
 *
 */

namespace PatternLab\PatternEngine\Twig\Loaders;

use \PatternLab\PatternEngine\Loader;
use \PatternLab\PatternEngine\Twig\TwigUtil;

class StringLoader extends Loader {
	
	/**
	* Load a new Twig instance that is just a vanilla Twig rendering engine for strings
	*/
	public function __construct($options = array()) {
		
		$twigLoader     = new \Twig_Loader_String();
		$this->instance = new \Twig_Environment($twigLoader);
		$this->instance = TwigUtil::loadMacros($this->instance, "string");
		
	}
	
	/**
	* Render a string
	* @param  {Array}        the options to be rendered by Twig
	*
	* @return {String}       the rendered result
	*/
	public function render($options = array()) {
		
		return $this->instance->render($options["string"], $options["data"]);
		
	}
	
}
