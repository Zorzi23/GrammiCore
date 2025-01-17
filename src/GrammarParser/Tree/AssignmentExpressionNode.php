<?php
namespace GrammiCore\GrammarParser\Tree;

class AssignmentExpressionNode extends AbstractSyntaxTreeNode {

    public function __construct(AbstractSyntaxTreeNode $oLeft, $oRight) {
        parent::__construct('AssignmentExpression', [
            'left' => $oLeft,
            'right' => $oRight
        ]);
    }

}