<?php
namespace GrammiCore\GrammarParser\Tree;

class UnsetAssignmentNode extends AbstractSyntaxTreeNode {
    public function __construct() {
        parent::__construct('UnsetAssignment', []);
    }
}