<?php
namespace GrammiCore\GrammarParser\Tree;

class InstantiableObjectNode extends AbstractSyntaxTreeNode {
    
    public function __construct($sId, $oClassDeclaration) {
        parent::__construct('InstantiableObject', [
            'id' => $sId,
            'classDeclaration' => $oClassDeclaration
        ]);
    }
}