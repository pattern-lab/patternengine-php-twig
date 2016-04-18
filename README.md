# Twig PatternEngine for Pattern Lab

The Twig PatternEngine allows you to use [Twig](http://twig.sensiolabs.org) as the template language for Pattern Lab PHP. Once the PatternEngine is installed you can use Twig-based StarterKits and StyleguideKits.

## Installation

The Twig PatternEngine comes pre-installed with the [Pattern Lab Standard Edition for Twig](https://github.com/pattern-lab/edition-php-twig-standard). Please start there for all your Twig needs.

### Composer

Pattern Lab PHP uses [Composer](https://getcomposer.org/) to manage project dependencies with Pattern Lab Editions. To add the Twig PatternEngine to the dependencies list for your Edition you can type the following in the command line at the base of your project:

    composer require pattern-lab/patternengine-twig

See Packagist for [information on the latest release](https://packagist.org/packages/pattern-lab/patternengine-twig).

## Overview

This document is broken into three parts:

* [Working with Patterns and Twig](#working-with-patterns-and-twig)
* [Extending Twig Further](#extending-twig-further)
* [Available Loaders for Plugin Developers](#available-loaders)

## Working with Patterns and Twig

Twig provides access to two features that may help you extend your patterns, [macros](http://twig.sensiolabs.org/doc/templates.html#macros) and layouts via[template inheritance](http://twig.sensiolabs.org/doc/templates.html#template-inheritance). The Twig PatternEngine also supports the [pattern partial syntax](http://patternlab.io/docs/pattern-including.html) to make including one pattern within another very easy.

* [Pattern includes](#pattern-includes)
* [Macros](#macros)
* [Template inheritance](#template-inheritance)

### Pattern includes

Pattern includes take advantage of the [pattern partial syntax](http://patternlab.io/docs/pattern-including.html) as a shorthand for referencing patterns from across the system without needing to rely on absolute paths. The format:

```
{% include "[patternType]-[patternName]" %}
```

For example, let's say we wanted to include the following pattern in a molecule:

```
source/_patterns/00-atoms/03-images/02-landscape-16x9.twig
```

The **pattern type** is _atoms_ (from `00-atoms`) and the **pattern name** is _landscape-16x9_ from (from `02-landscape-16x9.twig`). Pattern sub-types are never used in this format and any digits for re-ordering are dropped. The shorthand partial syntax for this pattern would be:

```
{% include "atoms-landscape-16x9" %}
```

### Macros

The requirements for using macros with Pattern Lab:

* Files must go in `source/_macros`
* Files must have the extension `.macro.twig` (_this can be modified in the config_)
* The filename will be used as the base variable name in Twig templates

**Please note:** ensure that there is no overlap between the keys for your macros and the keys for your data attributes. A macro with the name `forms.macro.twig` will conflict with a root key with the name `forms` in your JSON/YAML. Both are accessed via `{{ forms }}` in Twig.

An example of a simple macro called `forms.macro.twig` in `source/_macros`:

```twig
{% macro input(name) %}
    <input type="radio" name="{{ name }}" value="Dave" /> {{ name }}
{% endmacro %}
```

Would be used like this in a pattern:

```twig
{{ forms.input("First name") }}
```

### Template inheritance

How to use [Template Inheritance](http://twig.sensiolabs.org/doc/templates.html#template-inheritance) with Pattern Lab:

* Files must have the extension `.twig`.
* Files can be extended either by using Pattern Lab's normal shorthand syntax (e.g, `{% extends 'templates-extended-layout'%}`).
* Files can optionally go in `source/_layouts` in order to hide them from the list of patterns and then you can just use the filename as reference (e.g., `{% extends 'extended-layout'%}`).
* Files that are in the same directory can also just use the file name without the shorthand syntax (however, it must include the extension). So if `file1.twig` and `file2.twig` were in same directory, you could place this code in `file2.twig`: `{% extends 'file1.twig' %}`. 

An example of a simple layout called `base.twig` in `source/_layouts`:

```twig
<!DOCTYPE html>
<html>
    <head>
        {% block head %}
            <link rel="stylesheet" href="style.css" />
            <title>{% block title %}{% endblock %} - My Webpage</title>
        {% endblock %}
    </head>
    <body>
        <div id="content">{% block content %}{% endblock %}</div>
        <div id="footer">
            {% block footer %}
                &copy; Copyright 2011 by <a href="http://domain.invalid/">you</a>.
            {% endblock %}
        </div>
    </body>
</html>
```

Would be used like this in a pattern:

```twig
{% extends "base.twig" %}

{% block title %}Index{% endblock %}
{% block head %}
    {{ parent() }}
    <style type="text/css">
        .important { color: #336699; }
    </style>
{% endblock %}
{% block content %}
    <h1>Index</h1>
    <p class="important">
        Welcome on my awesome homepage.
    </p>
{% endblock %}
```

All uses of `extends` above also work with `includes`, `embed` and most likely many other Twig Tags. Let us know if you run into interesting or unexpected use cases!

## Extending Twig Further

Twig comes with a number of ways to extend the underlying template parser. You can you can add [extra tags](http://twig.sensiolabs.org/doc/advanced.html#tags), [filters](http://twig.sensiolabs.org/doc/advanced.html#filters), [tests](http://twig.sensiolabs.org/doc/advanced.html#tests), and [functions](http://twig.sensiolabs.org/doc/advanced.html#functions). The Twig PatternEngine tries to simplify these extensions by allowing you to create files in specific folders and then auto-load the extensions for you. Learn more about:

* [Filters](#filters)
* [Functions](#functions)
* [Tags](#tags)
* [Tests](#tests)

You can also:

* [Enable `dump()`](#enable-dump)
* [Modify the Default Date and Interval Formats](#modify-the-default-date-and-interval-formats)
* [Quickly disable extensions](#quickly-disable-extensions)

### Filters

The requirements for using filters with Pattern Lab:

* Files must go in `source/_twig-components/filters`
* Files must have the extension `.filter.php` (_this can be modified in the config_)
* The filter **must** set the variable `$filter`
* Only one filter per file (_e.g. can only set `$filter` once per file_)

An example function called `rot13.filter.php` in `source/_twig-components/filters`:

```php
<?php

$filter = new Twig_SimpleFilter('rot13', function ($string) {
	return str_rot13($string);
});

?>
```

This filter would be used like this in a pattern:

```twig
{{ bar|rot13 }}
```

### Functions

The requirements for using functions with Pattern Lab:

* Files must go in `source/_twig-components/functions`
* Files must have the extension `.function.php` (_this can be modified in the config_)
* The function **must** set the variable `$function`
* Only one function per file (_e.g. can only set `$function` once per file_)

An example function called `boo.function.php` in `source/_twig-components/functions`:

```php
<?php

$function = new Twig_SimpleFunction('boo', function ($string) {
	return $string." boo! ";
});

?>
```

This function would be used like this in a pattern:

```twig
{{ boo("ghost says what?") }}
```

### Tests

The requirements for using tests with Pattern Lab:

* Files must go in `source/_twig-components/tests`
* Files must have the extension `.test.php` (_this can be modified in the config_)
* The test **must** set the variable `$test`
* Only one test per file (_e.g. can only set `$test` once per file_)

An example of a simple test called `red.test.php` in `source/_twig-components/tests`:

```php
<?php

$test = new Twig_SimpleTest('red', function ($value) {
	
	if (isset($value["color"]) && $value["color"] == 'red') {
		return true;
	}
	
	return false;
});

?>
```

This test would be used like this in a pattern:

```twig
{% if shirt is red %}
	Why did I ever sign-up with Starfleet?
{% endif %}
```

Where the JSON for the data to set `shirt` would be:

```json
"shirt": {
	"color": "red"
}
```

**Reminder:** all data in Pattern Lab is stored as an array and _not_ as an object. So `$object->attribute` won't work in tests.

### Tags

The requirements for using tags with Pattern Lab:

* Files must go in `source/_twig-components/tags`
* Files must have the extension `.tag.php` (_this can be modified in the config_)
* The filename **must** be reflected in class names. (e.g. `Project_{filename}_Node` and `Project_{filename}_TokenParser`)
* Only one tag per file

Tags are the most complicated extension to set-up with Pattern Lab. Three steps are needed to define a new tag in Twig:

* Defining a Token Parser class (_responsible for parsing the template code_)
* Defining a Node class (_responsible for converting the parsed code to PHP_)
* Registering the tag.

Pattern Lab takes care of the registering for you based on the file name.

An example of a simple tag called `setdupe.tag.php` in `source/_twig-components/tags` that mimics the default `set` tag. Please note all of the locations where class names incorporate the filename, `setdupe`.

```php
<?php

// these files are loaded three times and we can't re-set a class
if (!class_exists("Project_setdupe_Node")) {
	
	class Project_setdupe_Node extends Twig_Node {
		
		public function __construct($name, Twig_Node_Expression $value, $line, $tag = null) {
			parent::__construct(array('value' => $value), array('name' => $name), $line, $tag);
		}
		
		public function compile(Twig_Compiler $compiler) {
			$compiler
				->addDebugInfo($this)
				->write('$context[\''.$this->getAttribute('name').'\'] = ')
				->subcompile($this->getNode('value'))
				->raw(";\n");
		}
		
	}
	
}

// these files are loaded three times and we can't re-set a class
if (!class_exists("Project_setdupe_TokenParser")) {
	
	class Project_setdupe_TokenParser extends Twig_TokenParser {
		
		public function parse(Twig_Token $token) {
			
			$parser = $this->parser;
			$stream = $parser->getStream();
			
			$name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
			$stream->expect(Twig_Token::OPERATOR_TYPE, '=');
			$value = $parser->getExpressionParser()->parseExpression();
			$stream->expect(Twig_Token::BLOCK_END_TYPE);
			
			return new Project_setdupe_Node($name, $value, $token->getLine(), $this->getTag());
		}
		
		public function getTag() {
			return 'setdupe';
		}
		
	}
	
}

?>
```

This tag would be used like this in a pattern:

```
{% setdupe name = "Ziggy" %}
{{ name }}
```

### Enable `dump()`

To use `dump()` set `twigDebug` in `config/config.yml` to `true`.

### Modify the Default Date and Interval Formats

You can modify the default date and interval formats for Twig by editing the `twigDefaultDateFormat` and `twigDefaultIntervalFormat` in `config/config.yml`. Set them to an empty string to use Twig's default formats. **Please note:** both must be set for this feature to work.

### Quickly Disable Extensions

To disable extensions that you're no longer using simply add an underscore to the beginning of a filename and then re-generate your site. For example, the enabled rot13 filter:

    source/_twig-components/filters/rot13.filter.php

And the disabled rot13 filter:

    source/_twig-components/filters/_rot13.filter.php

Then re-generate your Pattern Lab site with:

    php core/console --generate

## Available Loaders

If you're building a plugin that will be parsing Twig files you have access to three loaders. It's recommended that you use these instead of accessing Twig directly as these loaders will work with other PatternEngines.

### The String Loader

The string loader takes a simple string and compiles it. To use:

```php
$data         = array("hello" => "world");
$string       = "If I say hello you say {{ hello }}.";
$stringLoader = \PatternLab\Template::getStringLoader();
$output       = $stringLoader->render(array("string" => $string, "data" => $data));
print $output; // outputs "If I say hello you say world."
```

### The Filesystem Loader

The filesystem loader will look for templates in the configured StyleguideKit directory and compile them. The template location for the filesystem loader can't be modified. To use:

```php
$data             = array(...);
$filesystemLoader = \PatternLab\Template::getFilesystemLoader();
$output           = $filesystemLoader->render(array("template" => "viewall", "data" => $data));
print $output; // outputs the viewall view from the configured styleguidekit
```

### The Pattern Loader

The pattern loader looks for patterns and allows the use of the Pattern Lab-specific partial syntax. To use:

```php
$data                  = array(...);
$patternContent        = file_get_contents("path/to/pattern");
$patternEngineBasePath = \PatternLab\PatternEngine::getInstance()->getBasePath();
$patternLoaderClass    = $patternEngineBasePath."\Loaders\PatternLoader";
$patternLoader         = new $patternLoaderClass($options);
$code                  = $patternLoader->render(array("pattern" => $patternContent, "data" => $data));
print $output; // outputs the given pattern
```
