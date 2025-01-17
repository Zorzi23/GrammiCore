<?php
namespace GrammiCore\GrammarParser\Tree;

class LiteralNode extends AbstractSyntaxTreeNode {
    
    /**
     * LiteralNode constructor
     * 
     * @param mixed $xValue The literal value (could be int, string, etc.)
     * @param string $sRaw The raw value (string format)
     */
    public function __construct($xValue, string $sRaw) {
        parent::__construct('Literal', [
            'value' => $xValue,
            'raw' => $sRaw,
        ]);
    }
}