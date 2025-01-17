<?php
namespace GrammiCore\GrammarParser\Tree;

class PropertyDeclarationNode extends AbstractSyntaxTreeNode {
    public function __construct(AbstractSyntaxTreeNode $oName, $sModifier, $xValue = null) {
        parent::__construct('PropertyDeclaration', [
            'id' => $oName,
            'modifier' => $sModifier,
            'value' => $xValue,
            'inherited' => false,
            'static' => false
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