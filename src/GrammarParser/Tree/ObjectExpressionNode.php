<?php
namespace GrammiCore\GrammarParser\Tree;

class ObjectExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct(array $aProperties, $oOriginToken) {
        parent::__construct('ObjectExpression', [
            'properties' => $aProperties,
        ]);
    }
}