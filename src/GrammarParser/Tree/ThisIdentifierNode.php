<?php
namespace GrammiCore\GrammarParser\Tree;

class ThisIdentifierNode extends AbstractSyntaxTreeNode {
    public function __construct($oId) {
        parent::__construct('ThisIdentifierValue', [
            'id' => $oId
        ]);
    }
}