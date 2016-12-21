<?php

/*!
 * Copyright (c) 2016 Erik Mogensen
 * Licensed under the MIT license
 */

namespace PatternLab\PatternEngine\Twig\Loaders\Twig;

class ConditionalStringLoader extends \Twig_Loader_String {

	/**
	 * Return the source context if and only if it exists.
	 */
	public function getSourceContext($name) {
		if ($this->exists($name)) {
			 return parent::getSourceContext($name);
		}
		return null;
	}

	/**
	 * Return false if $name looks like a simple file name, true otherwise.
	 *
	 * A simple file name is a simple string consisting of alphanumerics,
	 * periods, hyphens, underbars, and so on,  otherwise
	 */
	public function exists($name) {
		return preg_match("/^[-a-zA-Z0-9~\\.\\/_@]+$/", $name) === 0;
	}
}
