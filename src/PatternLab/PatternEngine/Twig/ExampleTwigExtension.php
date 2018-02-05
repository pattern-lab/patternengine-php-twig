<?php

namespace PatternLab\PatternEngine\Twig;

use Twig_Extension;
use Twig_ExtensionInterface;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class ExampleTwigExtension extends Twig_Extension implements Twig_ExtensionInterface {

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   *
   * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
   */
  function getName() {
    return 'Pattern Lab Twig ExampleTwigExtension';
  }

  /**
   * Returns a list of filters to add to the existing list.
   *
   * @return Twig_SimpleFilter[]
   */
  function getFilters() {
    return [];
  }

  /**
   * Returns a list of functions to add to the existing list.
   *
   * @return Twig_SimpleFunction[]
   */
  function getFunctions() {
    return [
        new Twig_SimpleFunction('testPlFunction', function($arg) {
          return 'Thanks for testing out the Pattern Lab Example Twig Extension with this arg: ' . $arg;
        }),
    ];
  }

  /**
   * Returns a list of operators to add to the existing list.
   *
   * @return array<array> First array of unary operators, second array of binary operators
   */
  function getOperators() {
    return [];
  }

  /**
   * Returns a list of global variables to add to the existing list.
   *
   * @return array An array of global variables
   *
   * @deprecated since 1.23 (to be removed in 2.0), implement Twig_Extension_GlobalsInterface instead
   */
  function getGlobals() {
    return [
        'pl' => 'is awesome',
    ];
  }

  /**
   * Returns a list of tests to add to the existing list.
   *
   * @return Twig_SimpleTest[]
   */
  function getTests() {
    return [];
  }
  /**
   * Returns the token parser instances to add to the existing list.
   *
   * @return (Twig_TokenParserInterface|Twig_TokenParserBrokerInterface)[]
   */
  function getTokenParsers() {
    return [];
  }

  /**
   * Returns the node visitor instances to add to the existing list.
   *
   * @return Twig_NodeVisitorInterface[]
   */
  function getNodeVisitors() {
    return [];
  }

}
