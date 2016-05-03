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
use \PatternLab\PatternEngine\Twig\PatternDataNodeVisitor;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternPartialLoader as Twig_Loader_PatternPartialLoader;
use \PatternLab\PatternEngine\Twig\Loaders\Twig\PatternStringLoader as Twig_Loader_PatternStringLoader;
use \PatternLab\PatternEngine\Loader;
use \PatternLab\PatternEngine\Twig\TwigUtil;
use \Symfony\Component\Finder\Finder;

class PatternLoader extends Loader {
	
	/**
	* Load a new Twig instance that uses the Pattern Loader
	*/
	public function __construct($options = array()) {
		
		// set-up default vars
		$twigDebug            = Config::getOption("twigDebug");
		$twigAutoescape       = Config::getOption("twigAutoescape");
		
		// set-up the loader list
		$loaders              = array();
		$filesystemLoaderPaths = array();
		$loaders[]            = new Twig_Loader_PatternPartialLoader(Config::getOption("patternSourceDir"),array("patternPaths" => $options["patternPaths"]));
		
		
		// see if source/_macros exists
		$macrosPath     = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_macros";
		if (is_dir($macrosPath)) {
			$filesystemLoaderPaths[] = $macrosPath;
		}
		
		// see if source/_layouts exists. if so add it to be searchable
		$layoutsPath    = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_layouts";
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

		// add the paths to the filesystem loader if the paths existed
		if (count($filesystemLoaderPaths) > 0) {
			$twigFileLoader = new \Twig_Loader_Filesystem($filesystemLoaderPaths);

			// registering each directory under `_patterns/` as a namespace
			$finder = new Finder();
			// get all directories immediately under `_patterns/` (i.e. 00-atoms, 01-molecules, etc)
			$finder->directories()->depth(0)->in($patternSourceDir);
			foreach ($finder as $file) {
				$itemPath = $file->getPathName();
				$itemName = $file->getRelativePathName();
				// removing any numbers followed by dashes (i.e. `00-atoms` => `atoms`)
				$itemNamespace = preg_replace("/^\\d*-/uim", "", $itemName);
				// registering each foldername as a namespace (i.e. can use `{% include "@atoms/forms/submit.twig" %}`)
				$twigFileLoader->addPath($itemPath, $itemNamespace);
			}

			$loaders[] = $twigFileLoader;
		}
		
		$loaders[]            = new \Twig_Loader_String();
		
		// set-up Twig
		$twigLoader           = new \Twig_Loader_Chain($loaders);
		$this->instance       = new \Twig_Environment($twigLoader, array("debug" => $twigDebug, "autoescape" => $twigAutoescape));
		
		// customize Twig
		$this->instance       = TwigUtil::loadFilters($this->instance);
		$this->instance       = TwigUtil::loadFunctions($this->instance);
		$this->instance       = TwigUtil::loadTags($this->instance);
		$this->instance       = TwigUtil::loadTests($this->instance);
		$this->instance       = TwigUtil::loadDateFormats($this->instance);
		$this->instance       = TwigUtil::loadDebug($this->instance);
		$this->instance       = TwigUtil::loadMacros($this->instance);

		// add node visitor
		$this->instance->addNodeVisitor(new PatternDataNodeVisitor());
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
