<?php

namespace PatternLab\PatternEngine\Twig;

class PatternDataIncludeNode extends \Twig_Node_Include
{
    protected $data;

    public function __construct(\Twig_Node_Include $originalNode, $data)
    {
        \Twig_Node::__construct($originalNode->nodes, $originalNode->attributes, $originalNode->lineno, $originalNode->tag);
        $this->data = $data;
    }

    protected function addTemplateArguments(\Twig_Compiler $compiler)
    {
        if (null === $this->getNode('variables')) {
            if (false === $this->getAttribute('only')) {
                $compiler
                    ->raw('array_merge($context, ')
                    ->repr($this->data)
                    ->raw(')')
                ;
            }
            else {
                $compiler->raw('array()');
            }
        } elseif (false === $this->getAttribute('only')) {
            $compiler
                ->raw('array_merge($context, ')
                ->repr($this->data)
                ->raw(', ')
                ->subcompile($this->getNode('variables'))
                ->raw(')')
            ;
        } else {
            $compiler->subcompile($this->getNode('variables'));
        }
    }
}
