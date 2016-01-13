<?php

namespace PatternLab\PatternEngine\Twig;

use PatternLab\Data;

class IncludeNodeVisitor extends \Twig_BaseNodeVisitor
{
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        return $node;
    }

    protected function doLeaveNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Include) {
            if ($node->hasNode('expr') && $node->getNode('expr')->hasAttribute('value')) {
                $patternStoreKey = $node->getNode('expr')->getAttribute('value');
                $data = Data::getPatternSpecificData($patternStoreKey);
                $dataNode = new PatternDataIncludeNode($node, $data);

                return $dataNode;
            }
        }

        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
