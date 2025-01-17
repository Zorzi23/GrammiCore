<?php
namespace GrammiCore\GrammarParser\Tree;

class ThrowStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oArgument) {
        parent::__construct('ThrowStatement', [
            'argument' => $oArgument,
        ]);
    }
}