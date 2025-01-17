<?php
namespace GrammiCore\GrammarParser\Tree;

class ThisExpressionNode extends AbstractSyntaxTreeNode {
    public function __construct() {
        parent::__construct('ThisExpression', []);
    }
}