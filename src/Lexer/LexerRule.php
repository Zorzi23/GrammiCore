<?php
namespace GrammiCore\Lexer;

class LexerRule {

    /**
     * @var string
     */
    private $sPattern;

    /**
     * @var string
     */
    private $sName;

    /**
     * @var boolean
     */
    private $bSkip = false;

    /**
     * @var string
     */
    private $sType;

    /**
     * LexerRule constructor with named parameters
     *
     * @param string $sPattern - Token regex pattern
     * @param string $sName - Token name
     * @return static
     */
    public static function patternName($sPattern, $sName) {
        return (new static())
            ->setPattern($sPattern)
            ->setName($sName);
    }

    /**
     * LexerRule constructor with named parameters
     *
     * @param string $sPattern - Token regex pattern
     * @param string $sName - Token name
     * @return static
     */
    public static function patternNameType($sPattern, $sName, $sType = '') {
        return (new static())
            ->setPattern($sPattern)
            ->setName($sName)
            ->setType($sType);
    }

    /**
     * LexerRule constructor with named parameters
     *
     * @param string $sPattern - Token regex pattern
     * @param string $sName - Token name
     * @return static
     */
    public static function patternTypeName($sPattern, $sType, $sName = '') {
        return (new static())
            ->setPattern($sPattern)
            ->setType($sType)
            ->setName($sName);
    }
    
    /**
     * LexerRule constructor with named parameters (with skip flag)
     *
     * @param string $sPattern - Token regex pattern
     * @param string $sName - Token name
     * @param boolean $bSkip - Should the token be skipped
     * @param string $sType - The type of the token
     * @return static
     */
    public static function patternNameTypeSkip($sPattern, $sName, $sType, $bSkip = false) {
        return (new static())
            ->setPattern($sPattern)
            ->setName($sName)
            ->setType($sType)
            ->setSkip($bSkip);
    }

    /**
     * Set the pattern of the rule
     *
     * @param $sPattern - Token regex pattern
     * @return $this
     */
    public function setPattern($sPattern) {
        $this->sPattern = $sPattern;
        return $this;
    }

    /**
     * Set the name of the token
     *
     * @param $sName - Token name
     * @return $this
     */
    public function setName($sName) {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Set whether the token should be skipped
     *
     * @param $bSkip - Boolean indicating if the token should be skipped
     * @return $this
     */
    public function setSkip($bSkip) {
        $this->bSkip = $bSkip;
        return $this;
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
     * Get the pattern of the rule
     *
     * @return string
     */
    public function getPattern() {
        return $this->sPattern;
    }

    /**
     * Get the name of the token
     *
     * @return string
     */
    public function getName() {
        return $this->sName;
    }

    /**
     * Check if the token should be skipped
     *
     * @return boolean
     */
    public function shouldSkip() {
        return $this->bSkip;
    }

    /**
     * Get the type of the token
     *
     * @return string
     */
    public function getType() {
        return $this->sType;
    }

}
