<?php
namespace GrammiCore\GrammarParser\Tree;

class VariableDeclarationNode extends AbstractSyntaxTreeNode {

    /**
     * VariableDeclarationNode constructor
     * 
     * @param array $aDeclarations Array of variable declarations
     * @param string $sKind Type of declaration (var, let, const)
     */
    public function __construct(array $aDeclarations, string $sKind = 'var') {
        parent::__construct('VariableDeclaration', [
            'declarations' => $aDeclarations,
            'kind' => $sKind,
        ]);
    }
}