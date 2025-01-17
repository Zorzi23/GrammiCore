<?php
namespace GrammiCore\GrammarParser\Tree;

class MemberExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oObject, $oProperty = null) {
        parent::__construct('MemberExpression', [
            'object' => $oObject,
            'property' => $oProperty,
        ]);
    }
}