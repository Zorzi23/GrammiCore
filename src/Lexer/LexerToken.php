<?php
namespace GrammiCore\Lexer;

class LexerToken {

    /**
     * @var string
     */
    private $sType;

    /**
     * @var string
     */
    private $sName;

    /**
     * @var mixed
     */
    private $xValue;

    /**
     * @var int
     */
    private $iLine;

    /**
     * LexerToken constructor with named parameters
     *
     * @param string $sType - Token type
     * @param string $sName - Token name
     * @param mixed $xValue - Token value
     * @return static
     */
    public static function typeNameValue($sType, $sName, $xValue) {
        return (new static())
            ->setType($sType)
            ->setName($sName)
            ->setValue($xValue);
    }

    /**
     * LexerToken constructor with named parameters
     *
     * @param string $sType - Token type
     * @param string $sName - Token name
     * @param mixed $xValue - Token value
     * @param int $iLine - Line number
     * @return static
     */
    public static function typeNameValueLine($sType, $sName, $xValue, $iLine) {
        return (new static())
            ->setType($sType)
            ->setName($sName)
            ->setValue($xValue)
            ->setLine($iLine);
    }

    /**
     * Set the type of the token
     *
     * @param string $sType - Token type
     * @return $this
     */
    public function setType($sType) {
        $this->sType = $sType;
        return $this;
    }

    /**
     * Set the name of the token
     *
     * @param string $sName - Token name
     * @return $this
     */
    public function setName($sName) {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Set the value of the token
     *
     * @param mixed $xValue - Token value
     * @return $this
     */
    public function setValue($xValue) {
        $this->xValue = $xValue;
        return $this;
    }

    /**
     * Set the line number of the token
     *
     * @param int $iLine - Line number
     * @return $this
     */
    public function setLine($iLine) {
        $this->iLine = $iLine;
        return $this;
    }

    /**
     * Get the type of the token
     *
     * @return string - Token type
     */
    public function getType() {
        return $this->sType;
    }

    /**
     * Get the name of the token
     *
     * @return string - Token name
     */
    public function getName() {
        return $this->sName;
    }

    /**
     * Get the value of the token
     *
     * @return mixed - Token value
     */
    public function getValue() {
        return $this->xValue;
    }

    /**
     * Get the line number of the token
     *
     * @return int - Line number
     */
    public function getLine() {
        return $this->iLine;
    }

    /**
     * Check if the token type matches the given type
     *
     * @param string $sType - The type to compare with
     * @return bool - True if the types match, otherwise false
     */
    public function isType($xTypes): bool {
        $aTypes = is_array($xTypes) ? $xTypes : [$xTypes];
        return in_array($this->sType, $aTypes);
    }

    /**
     * Serialize token to json format
     *
     * @return array
     */
    public function toArray() {
        return [
            'type' => $this->sType,
            'name' => $this->sName,
            'value' => $this->xValue,
            'line' => $this->iLine,
        ];
    }
}
