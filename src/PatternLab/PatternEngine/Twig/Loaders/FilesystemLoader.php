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

use \PatternLab\Config;
use \PatternLab\PatternEngine\Loader;
use \PatternLab\PatternEngine\Twig\TwigUtil;

class FilesystemLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the File System Loader
	*/
	public function __construct($options = array()) {
		
		$macroPath      = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_macros";
		$twigLoader     = new \Twig_Loader_Filesystem(array($options["templatePath"],$options["partialsPath"],$macroPath));
		$this->instance = new \Twig_Environment($twigLoader);
		$this->instance = TwigUtil::loadMacros($this->instance, "filesystem");
		
	}
	
	/**
	* Render a template
	* @param  {Array}        the options to be rendered by Twig
	*
	* @return {String}       the rendered result
	*/
	public function render($options = array()) {
		
		return $this->instance->render($options["template"].".".Config::getOption("patternExtension"), $options["data"]);
		
	}
	
}
