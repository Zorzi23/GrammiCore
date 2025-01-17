<?php
namespace GrammiCore\Engine\ReservedClass;
use GrammiCore\GrammarInterpreter\Strategies\CallExpressionInterpreterStrategy;

class InterpreterThrowable {
 
    /**
     * Throwable Message
     * @var string
     */
    private $sMessage = '';

    /**
     * Throwable Severity
     * @var int
     */
    private $iSeverity = 0;

    public function __construct($aArgs, $oInterpreter) {
        list($oMessage) = $aArgs;
        $this->setMessage([$oMessage], $oInterpreter);
    }

    /**
     * Get throwable Message
     *
     * @return string
     */ 
    public function getMessage() {
        return $this->sMessage;
    }

    /**
     * Set throwable Message
     *
     * @param string $sMessage Throwable Message
     * @return self
     */ 
    public function setMessage($aArgs, $oInterpreter) {
        list($sMessage) = CallExpressionInterpreterStrategy::interpreter($oInterpreter)->extractArgumentsValues($aArgs);
        $this->sMessage = $sMessage;
        return $this;
    }

    /**
     * Get throwable Severity
     *
     * @return int
     */
    public function getSeverity() {
        return $this->iSeverity;
    }

    /**
     * Set throwable Severity
     *
     * @param int $iSeverity Throwable Severity
     * @return self
     */
    public function setSeverity(int $iSeverity) {
        $this->iSeverity = $iSeverity;
        return $this;
    }
}
