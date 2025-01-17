<?php
namespace GrammiCore\GrammarParser\Tree;

class ClassDeclarationNode extends AbstractSyntaxTreeNode {
    public function __construct(IdentifierNode $oName, $aProperties, $aMethods, $oParentIdentifier = null) {
        parent::__construct('ClassDeclaration', [
            'name' => $oName,
            'properties' => $aProperties,
            'methods' => $aMethods,
            'parent' => $oParentIdentifier,
        ]);
    }

    public function getClassProperty($sName) {
        foreach($this->getProperty('properties') as $oProperty) {
            if($oProperty->getProperty('name') === $sName) {
                return $oProperty;
            }
        }
    }

    public function getPropertyValue($sName) {
        return $this->getProperty($sName)->getProperty('value');
    }

    public function setPropertyValue($sName, $xValue) {
        $this->getProperty($sName)->setProperty('value', $xValue);
        return $this; 
    }

    public function getMethodFromName($sName) {
        foreach($this->getProperty('methods') as $oMethod) {
            if($oMethod->getProperty('name')->getProperty('name') === $sName) {
                return $oMethod;
            }
        }
    }

    public function getMethodFromList($sName, $aList) {
        foreach($aList as $oMethod) {
            if($oMethod->getProperty('name')->getProperty('name') === $sName) {
                return $oMethod;
            }
        }
    }

}