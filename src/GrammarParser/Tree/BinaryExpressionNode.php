<?php
namespace GrammiCore\GrammarParser\Tree;

class BinaryExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(string $sOperator, AbstractSyntaxTreeNode $oLeft, AbstractSyntaxTreeNode $oRight) {
        parent::__construct('BinaryExpression', [
            'operator' => $sOperator,
            'left' => $oLeft,
            'right' => $oRight,
        ]);
    }
}