<?php
namespace GrammiCore\GrammarParser\Tree;

class ProgramStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(array $aBody) {
        parent::__construct('ProgramStatement', [
            'body' => $aBody,
        ]);
    }
}