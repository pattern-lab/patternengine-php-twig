# Twig PatternEngine for Pattern Lab PHP

The Twig PatternEngine allows you to use [Twig](http://twig.sensiolabs.org) as the template language for Pattern Lab PHP. Once the PatternEngine is installed you can use Twig-based StarterKits and StyleguideKits.

## Installation

Pattern Lab PHP uses [Composer](https://getcomposer.org/) to manage project dependencies. To install the default static assets run:

    composer require pattern-lab/patternengine-twig

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
$patternPath           = "path/to/pattern";
$patternEngineBasePath = \PatternLab\PatternEngine::getInstance()->getBasePath();
$patternLoaderClass    = $patternEngineBasePath."\Loaders\PatternLoader";
$patternLoader         = new $patternLoaderClass($options);
$code                  = $patternLoader->render(array("pattern" => $patternPath, "data" => $data));
print $output; // outputs the given pattern
