<?php
namespace GrammiCore\GrammarInterpreter;
use GrammiCore\Engine\ErrorHandling\LanguageGrammarInterpreterError;
use GrammiCore\Engine\IO\IOStream;
use GrammiCore\Engine\LanguageGrammarAbstractWrapper;
use GrammiCore\GrammarInterpreter\Strategies\InterpreterStrategy;
use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;
use GrammiCore\GrammarParser\Tree\InstantiableObjectNode;
use GrammiCore\GrammarParser\Tree\ReservedClassNode;

/**
 * Class LanguageGrammarInterpreter
 * Interprets the Abstract Syntax Tree (AST)
 */
class LanguageGrammarInterpreter extends LanguageGrammarAbstractWrapper {

    const IDENTIFIER_TYPES = [
        'VARIABLE',
        'FUNCTION',
        'CONSTANT',
        'CLASS',
        'METHOD'
    ];

    /**
     * Stack of environments to support nested scopes.
     *
     * @var array[]
     */
    private $aEnvironmentStack = [[]];

    /**
     * Array of strategies for interpretation.
     *
     * @var InterpreterStrategy[]
     */
    protected $aStrategies = [];

    /**
     * Array to hold reserved types, both constants and functions.
     *
     * @var array
     */
    private $aReservedTypes = [
        'function' => [],
        'constant' => [],
    ];

    /**
     * Reference to the current 'this' object.
     *
     * @var mixed
     */
    private $currentThis = null;

    /**
     * Get the strategy suffix.
     *
     * @return string
     */
    protected static function Strategiesuffix(): string {
        return 'InterpreterStrategy';
    }

    /**
     * Get the current environment stack.
     *
     * @return array
     */
    public function getEnvironmentStack(): array {
        return $this->aEnvironmentStack;
    }

    /**
     * Set a value in the current environment.
     *
     * @param string $sKey
     * @param mixed $xValue
     * @return self
     */
    public function setVariableEnvironmentValueOnCurrentScope(string $sKey, $xValue): self {
        $this->setEnvironmentData('VARIABLE', $this->currentScope(), $sKey, $xValue);
        return $this;
    }

    /**
     * Set a value in the current environment.
     *
     * @param string $sEnvType
     * @param string $sKey
     * @param mixed $xValue
     * @return self
     */
    public function setEnvironmentTypeValueOnCurrentScope(string $sEnvType, string $sKey, $xValue): self {
        $this->setEnvironmentData($sEnvType, $this->currentScope(), $sKey, $xValue);
        return $this;
    }

    /**
     * Set a function value in the current environment.
     *
     * @param string $sKey
     * @param AbstractSyntaxTreeNode $oDeclaration
     * @return self
     */
    public function setFunctionEnvironmentValueOnCurrentScope(string $sKey, AbstractSyntaxTreeNode $oDeclaration): self {
        $this->setEnvironmentData('FUNCTION', $this->currentScope(), $sKey, [
            'body' => $oDeclaration->getProperty('body'),
            'params' => $oDeclaration->getProperty('params'),
        ]);
        return $this;
    }

    /**
     * Set a class value in the environment.
     *
     * @param string $sClassName
     * @param mixed $oStruct
     * @return self
     */
    public function setClassEnvironmentValue(string $sClassName, $oStruct): self {
        $this->setEnvironmentData('CLASS', 0, $sClassName, $oStruct);
        return $this;
    }

    /**
     * Get a class value from the environment.
     *
     * @param string $sClassName
     * @param bool $bThrowException
     * @return mixed
     */
    public function getClassEnvironmentValue(string $sClassName, bool $bThrowException = true) {
        return $this->getEnvironmentData('CLASS', 0, $sClassName, $bThrowException);
    }

    /**
     * Set environment data.
     *
     * @param string $sEnvType
     * @param int $iScope
     * @param string $sKey
     * @param mixed $xValue
     * @return self
     */
    public function setEnvironmentData(string $sEnvType, int $iScope, string $sKey, $xValue): self {
        $this->aEnvironmentStack[$iScope][$sKey] = [
            'type' => $sEnvType,
            'value' => $xValue
        ];
        return $this;
    }

    /**
     * Extract the value from environment data.
     *
     * @param array $aEnvironmentData
     * @return mixed
     */
    public function extractValueOfEnvironmentData(array $aEnvironmentData = []) {
        return $aEnvironmentData['value'];
    }

    /**
     * Get a function from the environment.
     *
     * @param int $iScope
     * @param string $sKey
     * @return array|null
     */
    public function getEnvironmentFunction(int $iScope, string $sKey): ?array {
        return $this->getEnvironmentData('FUNCTION', $iScope, $sKey);
    }

    /**
     * Get a function from the current scope.
     *
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentFunctionFromCurrentScope(string $sKey, bool $bThrowException = true): ?array {
        return $this->getEnvironmentDataFromCurrentScope('FUNCTION', $sKey, $bThrowException);
    }

    /**
     * Get a variable from the environment.
     *
     * @param int $iScope
     * @param string $sKey
     * @return array|null
     */
    public function getEnvironmentVariable(int $iScope, string $sKey): ?array {
        return $this->getEnvironmentData('VARIABLE', $iScope, $sKey);
    }

    /**
     * Get a variable from the current scope.
     *
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentVariableFromCurrentScope(string $sKey, bool $bThrowException = true): ?array {
        return $this->getEnvironmentDataFromCurrentScope('VARIABLE', $sKey, $bThrowException);
    }

    /**
     * Get environment data from the current scope.
     *
     * @param string $sType
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentDataFromCurrentScope(string $sType, string $sKey, bool $bThrowException = true): ?array {
        return $this->getEnvironmentData($sType, $this->currentScope(), $sKey, $bThrowException);
    }

    /**
     * Remove environment data from the current scope and any type.
     *
     * @param string $sKey
     * @param bool $bThrowException
     * @return bool
     */
    public function removeEnvironmentDataFromCurrentScopeAndAnyType(string $sKey, bool $bThrowException = true): bool {
        foreach (static::IDENTIFIER_TYPES as $sIdType) {
            $bSuccess = $this->removeEnvironmentDataFromCurrentScope($sIdType, $sKey, $bThrowException);
            if ($bSuccess) {
                return true;
            }
            if ($bThrowException && !$bSuccess) {
                return false;
            }
        }
        return false;
    }

    /**
     * Remove environment data from the current scope.
     *
     * @param string $sType
     * @param string $sKey
     * @param bool $bThrowException
     * @return bool
     */
    public function removeEnvironmentDataFromCurrentScope(string $sType, string $sKey, bool $bThrowException = true): bool {
        return $this->removeEnvironmentData($sType, $this->currentScope(), $sKey, $bThrowException);
    }

    /**
     * Get environment data from any type in the current scope.
     *
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentDataFromAnyTypeCurrentScope(string $sKey, bool $bThrowException = true): ?array {
        return $this->getEnvironmentDataFromAnyType($this->currentScope(), $sKey, $bThrowException);
    }

    /**
     * Get environment data from any type.
     *
     * @param int $iScope
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentDataFromAnyType(int $iScope, string $sKey, bool $bThrowException = true): ?array {
        foreach (self::IDENTIFIER_TYPES as $sType) {
            $aData = $this->getEnvironmentData($sType, $iScope, $sKey, $bThrowException);
            if (!empty($aData)) {
                return $aData;
            }
        }
        $this->throwError("Undefined Identifier: {$sKey}");
    }

    /**
     * Get environment data.
     *
     * @param string $sEnvType
     * @param int $iScope
     * @param string $sKey
     * @param bool $bThrowException
     * @return array|null
     */
    public function getEnvironmentData(string $sEnvType, int $iScope, string $sKey, bool $bThrowException = true): ?array {
        if (!isset($this->aEnvironmentStack[$iScope][$sKey])) {
            if ($bThrowException) {
                $this->throwError("Undefined {$sEnvType}: {$sKey}");
            }
            return null;
        }
        return $this->aEnvironmentStack[$iScope][$sKey];
    }

    /**
     * Remove environment data.
     *
     * @param string $sEnvType
     * @param int $iScope
     * @param string $sKey
     * @param bool $bThrowException
     * @return bool
     */
    public function removeEnvironmentData(string $sEnvType, int $iScope, string $sKey, bool $bThrowException = true): bool {
        if (!isset($this->aEnvironmentStack[$iScope][$sKey])) {
            if ($bThrowException) {
                $this->throwError("Undefined {$sEnvType}: {$sKey}");
            }
            return false;
        }
        unset($this->aEnvironmentStack[$iScope][$sKey]);
        return true;
    }

    /**
     * Get the current scope.
     *
     * @return int
     */
    public function currentScope(): int {
        return count($this->aEnvironmentStack) - 1;
    }

    /**
     * Push a new environment onto the stack.
     *
     * @return self
     */
    public function pushEnvironment(): self {
        $this->aEnvironmentStack[] = [];
        return $this;
    }

    /**
     * Pop the current environment off the stack.
     *
     * @return self
     */
    public function popEnvironment(): self {
        array_pop($this->aEnvironmentStack);
        return $this;
    }

    /**
     * Add a reserved type (constant or function) to the list.
     *
     * @param string $sKey
     * @param mixed $xValue
     * @return self
     */
    private function addReservedType(string $sKey, $xValue): self {
        $this->aReservedTypes[$sKey][] = $xValue;
        return $this;
    }

    /**
     * Add a reserved function to the list.
     * For method use array [0:class|object, 1:functionName]
     * 
     * @param string $sName
     * @param mixed $xFunction
     * @return self
     */
    public function addReservedFunction(string $sName, $xFunction): self {
        return $this->addReservedType('function', [
            'name' => $sName,
            'function' => $xFunction,
        ]);
    }

    /**
     * Remove a reserved function from the list.
     * 
     * @param string $sName
     * @return bool
     */
    public function removeReservedFunction(string $sName): bool {
        return $this->removeReservedTypeValueFromName('function', $sName);
    }

    /**
     * Get all reserved functions.
     * 
     * @return array|null
     */
    public function getReservedFunctions(): ?array {
        return $this->getReservedType('function');
    }

    /**
     * Get a reserved function by name.
     * 
     * @param string $sName
     * @return array|null
     */
    public function getReservedFunction(string $sName): ?array {
        return $this->getReservedTypeValueFromName('function', $sName);
    }

    /**
     * Verify if a function is reserved by name.
     * 
     * @param string $sName
     * @return bool
     */
    public function isReservedFunction(string $sName): bool {
        return !is_null($this->getReservedFunction($sName));
    }
    
    /**
     * Add a reserved class to the list.
     * 
     * @param string $sName
     * @param mixed $oRuntimeObject
     * @return self
     */
    public function addReservedClass(string $sName, $oRuntimeObject): self {
        return $this->addReservedType('class', [
            'name' => $sName,
            'runtimeObject' => $oRuntimeObject
        ]);
    }

    /**
     * Get a reserved class by name.
     * 
     * @param string $sName
     * @return array|null
     */
    public function getReservedClass(string $sName): ?array {
        return $this->getReservedTypeValueFromName('class', $sName);
    }
    
    /**
     * Verify if a class is reserved by name.
     * 
     * @param string $sName
     * @return bool
     */
    public function isReservedClass(string $sName): bool {
        return !is_null($this->getReservedClass($sName));
    }

    /**
     * Add a reserved constant to the list.
     * Needs to be literal
     * 
     * @param string $sName
     * @param mixed $xValue
     * @return self
     */
    public function addReservedConstant(string $sName, $xValue): self {
        if (!is_scalar($xValue)) {
            throw new \Exception('Constant values must be literal.');
        }
        return $this->addReservedType('constant', [
            'name' => $sName,
            'value' => $xValue,
        ]);
    }

    /**
     * Get a reserved constant by name.
     * 
     * @param string $sName
     * @return array|null
     */
    public function getReservedConstant(string $sName): ?array {
        return $this->getReservedTypeValueFromName('constant', $sName);
    }

    /**
     * Verify if a constant is reserved by name.
     * 
     * @param string $sName
     * @return bool
     */
    public function isReservedConstant(string $sName): bool {
        return !is_null($this->getReservedConstant($sName));
    }

    /**
     * Get a reserved type value by name.
     * 
     * @param string $sReservedType
     * @param string $sName
     * @return array|null
     */
    private function getReservedTypeValueFromName(string $sReservedType, string $sName): ?array {
        foreach ($this->getReservedType($sReservedType) ?: [] as $aFunction) {
            if (!array_key_exists('name', $aFunction)) { 
                continue;
            }
            if ($aFunction['name'] === $sName) {
                return $aFunction;
            }
        }
        return null;
    }

    /**
     * Remove a reserved type value by name.
     * 
     * @param string $sReservedType
     * @param string $sName
     * @return bool
     */
    private function removeReservedTypeValueFromName(string $sReservedType, string $sName): bool {
        foreach ($this->getReservedType($sReservedType) ?: [] as $iFunction => &$aFunction) {
            if (!array_key_exists('name', $aFunction)) { 
                continue;
            }
            if ($aFunction['name'] === $sName) {
                unset($this->aReservedTypes[$sReservedType][$iFunction]);
                return true;
            }
        }
        return false;
    }

    /**
     * Get the current 'this' object.
     *
     * @return mixed
     */
    public function getCurrentThis() {
        return $this->currentThis;
    }

    /**
     * Set the current 'this' object.
     *
     * @param mixed $xCurrentThis
     * @return self
     */
    public function setCurrentThis($xCurrentThis): self {
        $this->currentThis = $xCurrentThis;
        return $this;
    }

    /**
     * Get a reserved type by key.
     *
     * @param string $sKey
     * @return mixed|null
     */
    public function getReservedType(string $sKey) {
        return isset($this->aReservedTypes[$sKey]) ? $this->aReservedTypes[$sKey] : null;
    }

    /**
     * Run the interpreter on the provided AST node.
     *
     * @param AbstractSyntaxTreeNode $oNode
     * @return mixed
     */
    public function run(AbstractSyntaxTreeNode $oNode) {
        if ($this->getIO()->hasError()) {
            return;
        }
        $sType = $oNode->getType();
        $oStrategy = $this->instanceStrategyFromNodeType($sType, false);
        if (!$oStrategy) {
            throw new \Exception("Unknown node type: {$oNode->getType()}");
        }
        $oStrategy->setInterpreter($this);
        try {
            return $oStrategy->interpret($oNode);
        } 
        catch (LanguageGrammarInterpreterError $oError) {
            if($oError->getInterpreterThrowable()->getProperty('computed') === false) {
                $oError->getInterpreterThrowable()->setProperty('computed', true);
                $oError->getInterpreterThrowable()->setProperty('line', $oNode->getProperty('line'));
            }
            $this->getIO()->clearAll();
            $this->getIO()->appendStdErr($oError->getErrorAsString());
            throw $oError;
        }
    }

    /**
     * Throw an error with the specified message.
     *
     * @param string $sMessage
     * @return self
     */
    public function throwError(string $sMessage) {
        throw new \ErrorException($sMessage);
    }

    /**
     * Throw an error with the specified message.
     *
     * @param AbstractSyntaxTreeNode $oException
     * @return self
     */
    public function throwException(AbstractSyntaxTreeNode $oException): self {
        throw new LanguageGrammarInterpreterError($oException);
    }

    /**
     * Get the throwable argument object.
     *
     * @param mixed $oArgument
     * @return bool|mixed
     */
    private function getThrowAbleArgumentObject($oArgument) {
        if (!($oArgument instanceof InstantiableObjectNode)) {
            return false;
        }
        $oClassDeclaration = $oArgument->getProperty('classDeclaration');
        if (!($oClassDeclaration instanceof ReservedClassNode)) {
            return false;
        }
        return $oClassDeclaration->getProperty('runtimeObject');
    }
}
