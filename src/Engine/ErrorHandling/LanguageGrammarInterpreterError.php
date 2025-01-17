<?php
namespace GrammiCore\Engine\ErrorHandling;
use Exception;
use GrammiCore\Engine\ReservedClass\InterpreterThrowable;
use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;
use GrammiCore\GrammarParser\Tree\InstantiableObjectNode;
use GrammiCore\GrammarParser\Tree\ReservedClassNode;
use GrammiCore\Lexer\LexerToken;

class LanguageGrammarInterpreterError extends Exception {

   /**
     * 
     * @var LexerToken 
     */
    private $oOriginToken = null;

    /**
     * 
     * @var AbstractSyntaxTreeNode
     */
    private $oInterpreterThrowable = null;

    public function __construct(AbstractSyntaxTreeNode $oInterpreterThrowable) {
        $this->setInterpreterThrowable($oInterpreterThrowable);
    }

    /**
     * Get the value of oOriginToken
     *
     * @return LexerToken
     */
    public function getOriginToken() {
        return $this->oOriginToken;
    }

    /**
     * Get the value of oInterpreterThrowable
     *
     * @return AbstractSyntaxTreeNode
     */
    public function getInterpreterThrowable() {
        return $this->oInterpreterThrowable;
    }

    /**
     * Set the value of oInterpreterThrowable
     *
     * @param AbstractSyntaxTreeNode $oInterpreterThrowable
     * @return self
     */
    public function setInterpreterThrowable($oInterpreterThrowable) {
        $this->oInterpreterThrowable = $oInterpreterThrowable;
        return $this;
    }

    public function getErrorAsString() {
        $oInterpreterThrowAble = $this->getInterpreterThrowable();
        $oThrowAble = $this->getThrowAbleArgumentObject($oInterpreterThrowAble);
        $sClassName = $oInterpreterThrowAble->getProperty('classDeclaration')->getClassName();
        return strtr(static::errorStringTemplate(), [
            '{errorClass}' => $sClassName,
            '{message}' => $oThrowAble->getMessage(),
            '{errorLine}' => $oInterpreterThrowAble->getProperty('line'),
        ]);
    }

    private static function errorStringTemplate() {
        return '{errorClass}: {message} at line {errorLine}';
    }

    private function getThrowAbleArgumentObject($oArgument) {
        if(!($oArgument instanceof InstantiableObjectNode)) {
            return false;
        }
        $oClassDeclaration = $oArgument->getProperty('classDeclaration'); 
        if(!($oClassDeclaration instanceof ReservedClassNode)) {
            return false;
        }
        return $oClassDeclaration->getProperty('runtimeObject');
    }

}
