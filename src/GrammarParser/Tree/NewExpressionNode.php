<?php
namespace GrammiCore\GrammarParser\Tree;

class NewExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(IdentifierNode $oClassName, array $aArguments) {
        parent::__construct('NewExpression', [
            'class' => $oClassName,
            'arguments' => $aArguments,
        ]);
    }
}