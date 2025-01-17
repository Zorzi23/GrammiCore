<?php
namespace GrammiCore\GrammarParser\Tree;

class FunctionExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(array $aParams, AbstractSyntaxTreeNode $oBody) {
        parent::__construct('FunctionExpression', [
            'params' => $aParams,
            'body' => $oBody,
        ]);
    }
}