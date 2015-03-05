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

namespace PatternLab\PatternEngine\Twig;

use \PatternLab\Config;
use \PatternLab\Console;
use \Symfony\Component\Finder\Finder;

class TwigUtil {
	
	/**
	* Write a warning if a dir doesn't exist
	* @param  {String}         the dir that doesn't exist
	*/
	protected static function dirNotExist($dir) {
		$dirHR = Console::getHumanReadablePath($dir);
		Console::writeWarning("the path <path>".$dirHR."</path> doesn't exist so filters won't be loaded...");
	}
	
	/**
	* Load custom date formats for Twig
	* @param  {Instance}       an instance of the twig engine
	*
	* @return {Instance}       an instance of the twig engine
	*/
	public static function loadDateFormats($instance) {
		
		$dateFormat     = Config::getOption("twigDefaultDateFormat");
		$intervalFormat = Config::getOption("twigDefaultIntervalFormat");
		
		if ($dateFormat && $intervalFormat && !empty($dateFormat) && !empty($intervalFormat)) {
			$instance->getExtension("core")->setDateFormat($dateFormat, $intervalFormat);
		}
		
		return $instance;
		
	}
	
	/**
	* Enable the debug options for Twig
	* @param  {Instance}       an instance of the twig engine
	*
	* @return {Instance}       an instance of the twig engine
	*/
	public static function loadDebug($instance) {
		
		if (Config::getOption("twigDebug")) {
			$instance->addExtension(new \Twig_Extension_Debug());
		}
		
		return $instance;
		
	}
	
	/**
	* Load filters for the Twig PatternEngine
	* @param  {Instance}       an instance of the twig engine
	*
	* @return {Instance}       an instance of the twig engine
	*/
	public static function loadFilters($instance) {
		
		// load defaults
		$filterDir = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_twig-components/filters";
		$filterExt = Config::getOption("twigFilterExt");
		$filterExt = $filterExt ? $filterExt : "filter.twig";
		
		if (is_dir($filterDir)) {
			
			// loop through the filter dir...
			$finder = new Finder();
			$finder->files()->name("*\.".$filterExt)->in($filterDir);
			$finder->sortByName();
			foreach ($finder as $file) {
				
				include($file->getPathname());
				
				// $filter should be defined in the included file
				if (isset($filter)) {
					$instance->addFilter($filter);
					unset($filter);
				}
				
			}
			
		} else {
			
			self::dirNotExist($filterDir);
			
		}
		
		return $instance;
		
	}
	
	/**
	* Load functions for the Twig PatternEngine
	* @param  {Instance}       an instance of the twig engine
	*
	* @return {Instance}       an instance of the twig engine
	*/
	public static function loadFunctions($instance) {
		
		// load defaults
		$functionDir = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_twig-components/functions";
		$functionExt = Config::getOption("twigFunctionExt");
		$functionExt = $functionExt ? $functionExt : "function.twig";
		
		if (is_dir($functionDir)) {
			
			// loop through the function dir...
			$finder = new Finder();
			$finder->files()->name("*\.".$functionExt)->in($functionDir);
			$finder->sortByName();
			foreach ($finder as $file) {
				
				include($file->getPathname());
				
				// $function should be defined in the included file
				if (isset($function)) {
					$instance->addFunction($function);
					unset($function);
				}
				
			}
			
		} else {
			
			self::dirNotExist($functionDir);
			
		}
		
		return $instance;
		
	}
	
	/**
	* Load macros for the Twig PatternEngine
	* @param  {Instance}       an instance of the twig engine
	*
	* @return {Instance}       an instance of the twig engine
	*/
	public static function loadMacros($instance) {
		
		// load defaults
		$macroDir = Config::getOption("sourceDir").DIRECTORY_SEPARATOR."_macros";
		$macroExt = Config::getOption("twigMacroExt");
		$macroExt = $macroExt ? $macroExt : "macro";
		
		if (is_dir($macroDir)) {
			
			// loop through some macro containing dir and run...
			$finder = new Finder();
			$finder->files()->name("*.".$macroExt)->in($macroDir);
			$finder->sortByName();
			foreach ($finder as $file) {
				$instance->addGlobal($file->getBasename(".".$macroExt), $instance->loadTemplate($file->getBasename()));
			}
			
		} else {
			
			// write warning because the macro dir doesn't exist
			$macroDirHR = Console::getHumanReadablePath($macroDir);
			Console::writeWarning("the path <path>".$macroDirHR."</path> doesn't exist so macros won't be loaded for the ".$instanceType." loader...");
			
		}
		
		return $instance;
		
	}
	
}
