<?php
namespace GrammiCore\Engine;

use GrammiCore\Engine\IO\IOStream;
use InvalidArgumentException;

abstract class LanguageGrammarAbstractWrapper {

    /**
     * 
     * @var string
     */
    private $sStrategyNamespaceName = '\GrammiCore';

    /**
     * The IO handler for the grammar step.
     *
     * @var IOStream
     */
    private $oIO = null;

    /**
     * 
     * @inheritDoc
     */
    public function __construct() {
        $this->setIO(new IOStream());
    }

    /**
     * 
     * @return string
     */
    public function getStrategyNamespaceName(){
        return $this->sStrategyNamespaceName;
    }

    /**
     * Get the IO handler.
     *
     * @return IOStream
     */
    public function getIO() {
        return $this->oIO;
    }

    /**
     * 
     * @param string $sStrategyNamespaceName
     */
    public function setStrategyNamespaceName($sStrategyNamespaceName) {
        $this->sStrategyNamespaceName = $sStrategyNamespaceName;
        return $this;
    }

    
    /**
     * Set the IO handler.
     *
     * @param IOStream $oIO
     * @return self
     */
    public function setIO(IOStream $oIO) {
        $this->oIO = $oIO;
        return $this;
    }

    /**
     * 
     * @param mixed $sNodeType
     * @return object|false
     */
    public function instanceStrategyFromNodeType($sNodeType, $bThrowException = true) {
        $sNamespace = $this->createStrategyInstanceNamespaceFromNodeType($sNodeType);
        if(!class_exists($sNamespace)) {
            if(!$bThrowException) { return false; }
            throw new InvalidArgumentException("Strategy {$sNamespace} class does not exists.");
        }
        return new $sNamespace();
    }

    /**
     * 
     * @param mixed $sNodeType
     * @return string
     */
    protected function createStrategyInstanceNamespaceFromNodeType($sNodeType) {
        return strtr('{sNamespaceName}\Strategies\{sNodeType}{sStrategiesuffix}', [
            '{sNamespaceName}' => $this->getStrategyNamespaceName(),
            '{sNodeType}' => $sNodeType,
            '{sStrategiesuffix}' => static::Strategiesuffix()
        ]);
    }

    /**
     * Return strategy pattern sufix
     * @return string
     */
    abstract protected static function Strategiesuffix();

}