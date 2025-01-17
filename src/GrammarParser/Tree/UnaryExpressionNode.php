<?php
namespace GrammiCore\GrammarParser\Tree;

class UnaryExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct($sOperator, AbstractSyntaxTreeNode $oArgument, $bPrefix, $bChangeIdentifierValue = false) {
        parent::__construct('UnaryExpression', [
            'operator' => $sOperator,
            'argument' => $oArgument,
            'prefix' => $bPrefix,
            'changeIdValue' => $bChangeIdentifierValue
        ]);
    }
}