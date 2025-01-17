<?php
namespace GrammiCore\GrammarParser\Tree;

class ForStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oInit, AbstractSyntaxTreeNode $oTest, AbstractSyntaxTreeNode $oUpdate, AbstractSyntaxTreeNode $oBody) {
        parent::__construct('ForStatement', [
            'init' => $oInit,
            'test' => $oTest,
            'update' => $oUpdate,
            'body' => $oBody,
        ]);
    }
}