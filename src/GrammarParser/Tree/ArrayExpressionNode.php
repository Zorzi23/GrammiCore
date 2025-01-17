<?php
namespace GrammiCore\GrammarParser\Tree;

class ArrayExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(array $aElements) {
        parent::__construct('ArrayExpression', [
            'elements' => $aElements,
        ]);
    }
}