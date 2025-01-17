<?php
namespace GrammiCore\GrammarParser\Tree;

class MethodDeclarationNode extends AbstractSyntaxTreeNode {
    public function __construct(IdentifierNode $oName, array $aParams, AbstractSyntaxTreeNode $oBody, $sModifier, $bStatic = false) {
        parent::__construct('MethodDeclaration', [
            'name' => $oName,
            'params' => $aParams,
            'body' => $oBody,
            'modifier' => $sModifier,
            'inherited' => false,
            'static' => $bStatic
        ]);
    }

    public function isInherited() {
        return $this->getProperty('inherited') === true;
    }

    public function setInherited($bInherited) {
        $this->setProperty('inherited', $bInherited);
        return $this;
    }

}