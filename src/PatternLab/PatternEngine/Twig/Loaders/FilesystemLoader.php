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
		
		// set-up default vars
		$twigDebug      = Config::getOption("twigDebug");
		
		// set-up the paths to be searched for templates
		$dirPaths       = array();
		$dirPaths[]     = $options["templatePath"];
		$dirPaths[]     = $options["partialsPath"];
		
		// see if source/_macros exists. if so add it to be searchable
		$macrosPath     = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_macros";
		if (is_dir($macrosPath)) {
			$dirPaths[] = $macrosPath;
		}
		
		// see if source/_layouts exists. if so add it to be searchable
		$layoutsPath    = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_layouts";
		if (is_dir($layoutsPath)) {
			$dirPaths[] = $layoutsPath;
		}
		
		// set-up Twig
		$twigLoader     = new \Twig_Loader_Filesystem($dirPaths);
		$this->instance = new \Twig_Environment($twigLoader, array("debug" => $twigDebug));
		
		// customize Twig
		$this->instance = TwigUtil::loadFilters($this->instance);
		$this->instance = TwigUtil::loadFunctions($this->instance);
		$this->instance = TwigUtil::loadTags($this->instance);
		$this->instance = TwigUtil::loadTests($this->instance);
		$this->instance = TwigUtil::loadDateFormats($this->instance);
		$this->instance = TwigUtil::loadDebug($this->instance);
		$this->instance = TwigUtil::loadMacros($this->instance);
		
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
