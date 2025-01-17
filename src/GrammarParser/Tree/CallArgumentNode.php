<?php
namespace GrammiCore\GrammarParser\Tree;

class CallArgumentNode extends AbstractSyntaxTreeNode {

    public function __construct(AbstractSyntaxTreeNode $oArgument, &$xValue) {
        parent::__construct('CallArgument', [
            'argument' => $oArgument,
            'value' => $xValue
        ]);
    }
    
}