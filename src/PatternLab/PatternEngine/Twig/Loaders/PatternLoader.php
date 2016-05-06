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
use \PatternLab\Dispatcher;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternPartialLoader as Twig_Loader_PatternPartialLoader;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternStringLoader as Twig_Loader_PatternStringLoader;
use \PatternLab\PatternEngine\Loader;
use \PatternLab\PatternEngine\Twig\TwigUtil;

class PatternLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the Pattern Loader
	*/
	public function __construct($options = array()) {
		
		// set-up default vars
		$twigDebug      = Config::getOption("twigDebug");
		$twigAutoescape = Config::getOption("twigAutoescape");
		
		// go through various places where things can exist
		$filesystemLoaderPaths = array();
		
		// see if source/_macros exists
		$macrosPath = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_macros";
		if (is_dir($macrosPath)) {
			$filesystemLoaderPaths[] = $macrosPath;
		}
		
		// see if source/_layouts exists. if so add it to be searchable
		$layoutsPath = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_layouts";
		if (is_dir($layoutsPath)) {
			$filesystemLoaderPaths[] = $layoutsPath;
		}

		// add source/_patterns subdirectories for Drupal theme template compatibility
		$patternSourceDir = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_patterns";
		$patternObjects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($patternSourceDir), \RecursiveIteratorIterator::SELF_FIRST);
		$patternObjects->setFlags(\FilesystemIterator::SKIP_DOTS);

		// sort the returned objects
		$patternObjects = iterator_to_array($patternObjects);
		ksort($patternObjects);

		foreach ($patternObjects as $name => $object) {
			if ($object->isDir()) {
				$filesystemLoaderPaths[] = $object->getPathname();
			}
		}
		
		// set-up the loader list in order that they should be checked
		// 1. Patterns 2. Filesystem 3. String
		$loaders   = array();
		$loaders[] = new Twig_Loader_PatternPartialLoader(Config::getOption("patternSourceDir"),array("patternPaths" => $options["patternPaths"]));

		// add the paths to the filesystem loader if the paths existed
		if (count($filesystemLoaderPaths) > 0) {
			$filesystemLoader = new \Twig_Loader_Filesystem($filesystemLoaderPaths);
			$loaders[] = TwigUtil::addPaths($filesystemLoader, $patternSourceDir);
		}
		$loaders[] = new \Twig_Loader_String();
		
		// set-up Twig
		$twigLoader = new \Twig_Loader_Chain($loaders);
		$instance   = new \Twig_Environment($twigLoader, array("debug" => $twigDebug, "autoescape" => $twigAutoescape));
		
		// customize Twig
		TwigUtil::setInstance($instance);
		TwigUtil::loadFilters();
		TwigUtil::loadFunctions();
		TwigUtil::loadTags();
		TwigUtil::loadTests();
		TwigUtil::loadDateFormats();
		TwigUtil::loadDebug();
		TwigUtil::loadMacros();

		// set-up the dispatcher
		$dispatcherInstance = Dispatcher::getInstance();
		$dispatcherInstance->dispatch("twigLoader.customize");
		$dispatcherInstance->dispatch("twigPatternLoader.customize");

		// get the instance
		$this->instance = TwigUtil::getInstance();
		
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
