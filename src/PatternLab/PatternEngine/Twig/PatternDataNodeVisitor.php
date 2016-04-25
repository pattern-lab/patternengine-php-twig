<?php

namespace PatternLab\PatternEngine\Twig;

use PatternLab\Data;

class PatternDataNodeVisitor extends \Twig_BaseNodeVisitor
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
                if ($node instanceof \Twig_Node_Embed) {
                    $dataNode = new PatternDataEmbedNode($node, $data);
                }
                else {
                    $dataNode = new PatternDataIncludeNode($node, $data);
                }

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
