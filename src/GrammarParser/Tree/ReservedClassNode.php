<?php
namespace GrammiCore\GrammarParser\Tree;

class ReservedClassNode extends AbstractSyntaxTreeNode {
    public function __construct($sName, $oRuntimeObject) {
        parent::__construct('ReservedClass', [
            'name' => $sName,
            'runtimeObject' => $oRuntimeObject
        ]);
    }

    public function getClassName() {
        return $this->getProperty('name');
    }


}