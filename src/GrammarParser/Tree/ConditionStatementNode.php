<?php
namespace GrammiCore\GrammarParser\Tree;

class ConditionStatementNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oTest, AbstractSyntaxTreeNode $oConsequent, AbstractSyntaxTreeNode $oAlternate = null) {
        parent::__construct('IfStatement', [
            'test' => $oTest,
            'consequent' => $oConsequent,
            'alternate' => $oAlternate,
        ]);
    }
}