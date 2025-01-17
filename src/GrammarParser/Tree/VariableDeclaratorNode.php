<?php
namespace GrammiCore\GrammarParser\Tree;

class VariableDeclaratorNode extends AbstractSyntaxTreeNode {
    
    /**
     * VariableDeclaratorNode constructor
     * 
     * @param IdentifierNode $oId Identifier node representing the variable name
     * @param LiteralNode $oInit Literal node for the assigned value
     */
    public function __construct(IdentifierNode $oId, AbstractSyntaxTreeNode $oInit) {
        parent::__construct('VariableDeclarator', [
            'id' => $oId,
            'init' => $oInit
        ]);
    }
}