# Twig PatternEngine for Pattern Lab PHP

The Twig PatternEngine allows you to use [Twig](http://twig.sensiolabs.org) as the template language for Pattern Lab PHP. Once the PatternEngine is installed you can use Twig-based StarterKits and StyleguideKits.

## Installation

Pattern Lab PHP uses [Composer](https://getcomposer.org/) to manage project dependencies. To install the Twig PatternEngine:

    composer require pattern-lab/patternengine-twig

## Using Twig Macros

The Twig PatternEngine will automatically find and load Twig macros making them available to every pattern. To use this feature add your `.macro` files to `source/_macros/`. If your macro file is called `forms.macro` and it has a macro called `input()` you'd access it in your Twig templates as `{{ forms.input() }}`. **Please note:** ensure that there is no overlap between the keys for your macros and the keys for your data attributes.

## Enabling `dump()`

To use `dump()` set `twigDebug` in `configs/config.yml` to `true`.

## Modifying the Default Date and Interval Format

You can modify the default date and interval formats for Twig by editing the `twigDefaultDateFormat` and `twigDefaultIntervalFormat` in `configs/config.yml`. Set them to an empty string to use Twig's default formats. **Please note:** both must be set for this feature to work.

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
