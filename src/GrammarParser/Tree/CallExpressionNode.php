<?php
namespace GrammiCore\GrammarParser\Tree;

class CallExpressionNode extends AbstractSyntaxTreeNode {

    public function __construct(AbstractSyntaxTreeNode $oCallee, array $aArguments) {
        parent::__construct('CallExpression', [
            'callee' => $oCallee,
            'arguments' => $aArguments,
        ]);
    }
    
}