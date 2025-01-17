<?php
namespace GrammiCore\GrammarParser\Tree;

class BlockStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(array $aBody) {
        parent::__construct('BlockStatement', [
            'body' => $aBody,
        ]);
    }
}