<?php
namespace GrammiCore\GrammarParser\Tree;

class ReturnStatementValueNode extends AbstractSyntaxTreeNode {
    public function __construct($xValue) {
        parent::__construct('ReturnStatementValue', [
            'value' => $xValue,
        ]);
    }
}