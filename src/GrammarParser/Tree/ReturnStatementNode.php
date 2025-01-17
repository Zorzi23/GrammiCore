<?php
namespace GrammiCore\GrammarParser\Tree;

class ReturnStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oArgument) {
        parent::__construct('ReturnStatement', [
            'argument' => $oArgument,
        ]);
    }
}