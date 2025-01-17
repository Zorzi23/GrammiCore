<?php
namespace GrammiCore\GrammarParser\Tree;

/**
 * Class TryCatchStatementNode
 * Represents a try/catch/finally statement in the AST
 */
class TryCatchStatementNode extends AbstractSyntaxTreeNode {

    /**
     * Constructor for TryCatchStatementNode
     * 
     * @param AbstractSyntaxTreeNode $oTryBlock The try block
     * @param AbstractSyntaxTreeNode $oCatchBlock The catch block
     * @param IdentifierNode|null $oCatchArgument The catch argument
     * @param AbstractSyntaxTreeNode|null $oFinallyBlock The finally block
     */
    public function __construct(
        AbstractSyntaxTreeNode $oTryBlock, 
        AbstractSyntaxTreeNode $oCatchBlock, 
        ?IdentifierNode $oCatchArgument = null, 
        ?AbstractSyntaxTreeNode $oFinallyBlock = null
    ) {
        parent::__construct('TryCatchStatement', [
            'try' => $oTryBlock,
            'catch' => $oCatchBlock,
            'catchArgument' => $oCatchArgument,
            'finally' => $oFinallyBlock
        ]);
    }
}