<?php

/*!
 * Twig Pattern Engine Loader Class - Filesystem
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Sets an instance of Twig to deal with rendering of templates that aren't patterns
 *
 */

namespace PatternLab\PatternEngine\Twig\Loaders;

use \PatternLab\PatternEngine\Loader;

class FilesystemLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the File System Loader
	*/
	public function __construct($options = array()) {
		
		$twigLoader     = new \Twig_Loader_Filesystem(array($options["templatePath"],$options["partialsPath"]));
		$this->instance = new \Twig_Environment($twigLoader);
		
	}
	
	/**
	* Render a template
	* @param  {Array}        the options to be rendered by Twig
	*
	* @return {String}       the rendered result
	*/
	public function render($options = array()) {
		
		return $this->instance->render($options["template"], $options["data"]);
		
	}
	
}
