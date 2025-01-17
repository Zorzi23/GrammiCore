<?php
namespace GrammiCore\GrammarParser\Tree;

class FunctionDeclarationNode extends AbstractSyntaxTreeNode {
    public function __construct(IdentifierNode $oName, array $aParams, AbstractSyntaxTreeNode $oBody) {
        parent::__construct('FunctionDeclaration', [
            'name' => $oName,
            'params' => $aParams,
            'body' => $oBody,
        ]);
    }
}