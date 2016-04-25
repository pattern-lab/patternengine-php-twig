<?php

namespace PatternLab\PatternEngine\Twig;

class PatternDataEmbedNode extends \Twig_Node_Embed
{
    use PatternDataNodeTrait;

    public function __construct(\Twig_Node_Embed $originalNode, $data)
    {
        parent::__construct(
          $originalNode->getAttribute('filename'),
          $originalNode->getAttribute('index'),
          $originalNode->getNode('variables'),
          $originalNode->getAttribute('only'),
          $originalNode->getAttribute('ignore_missing'),
          $originalNode->getLine(),
          $originalNode->getNodeTag()
        );

        $this->data = $data;
    }
}
