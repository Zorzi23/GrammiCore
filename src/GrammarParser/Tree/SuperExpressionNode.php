<?php
namespace GrammiCore\GrammarParser\Tree;

class SuperExpressionNode extends AbstractSyntaxTreeNode {
    
    public function __construct() {
        parent::__construct('SuperExpression', []);
    }
    
}