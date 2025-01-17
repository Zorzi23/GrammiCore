<?php
namespace GrammiCore\GrammarParser\Tree;

class IdentifierNode extends AbstractSyntaxTreeNode {
    
    /**
     * IdentifierNode constructor
     * 
     * @param string $sName Name of the Identifier
     */
    public function __construct(string $sName, $sType = 'IDENTIFIER') {
        parent::__construct('Identifier', [
            'name' => $sName,
            'type' => $sType
        ]);
    }
    
}