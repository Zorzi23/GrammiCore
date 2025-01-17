<?php
namespace GrammiCore\GrammarParser\Strategies;
use GrammiCore\GrammarParser\LanguageGrammarParser;
use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;

abstract class ParserStrategy {

    /**
     * 
     * @var LanguageGrammarParser 
     */
    private $oParser = null;

    /**
     * 
     * @param LanguageGrammarParser $oParser
     * @return ParserStrategy
     */
    public static function parser(LanguageGrammarParser $oParser) {
        return (new static())->setParser($oParser);
    }

    /**
     * Set the value of oParser
     *
     * @param LanguageGrammarParser  $oParser
     * @return self
     */ 
    public function setParser(LanguageGrammarParser $oParser){
        $this->oParser = $oParser;
        return $this;
    }

    /**
     *
     * @return LanguageGrammarParser
     */ 
    public function getParser(){
        return $this->oParser;
    }

    /**
     * Parses the relevant part of the code.
     *
     * @return AbstractSyntaxTreeNode
     */
    abstract public function parse();
    
}
