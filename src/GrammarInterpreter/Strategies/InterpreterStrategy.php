<?php
namespace GrammiCore\GrammarInterpreter\Strategies;
use GrammiCore\GrammarInterpreter\LanguageGrammarInterpreter;
use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;

abstract class InterpreterStrategy {

    /**
     * 
     * @var LanguageGrammarInterpreter 
     */
    private $oInterpreter = null;

    /**
     * 
     * @param LanguageGrammarInterpreter $oInterpreter
     * @return static
     */
    public static function interpreter(LanguageGrammarInterpreter $oInterpreter) {
        return (new static())->setInterpreter($oInterpreter);
    }

    /**
     * Set the value of oInterpreter
     *
     * @param LanguageGrammarInterpreter  $oParser
     * @return self
     */ 
    public function setInterpreter(LanguageGrammarInterpreter $oInterpreter){
        $this->oInterpreter = $oInterpreter;
        return $this;
    }

    /**
     *
     * @return LanguageGrammarInterpreter
     */ 
    public function getInterpreter(){
        return $this->oInterpreter;
    }

    /**
     * Interpretate AST Tree.
     *
     * @param AbstractSyntaxTreeNode
     * @return mixed
     */
    abstract public function interpret($oNode);
    
}