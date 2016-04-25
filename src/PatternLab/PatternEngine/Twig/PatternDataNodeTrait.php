<?php

namespace PatternLab\PatternEngine\Twig;

trait PatternDataNodeTrait
{
    protected $data;

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
