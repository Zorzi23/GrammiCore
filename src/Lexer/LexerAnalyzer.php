<?php
namespace GrammiCore\Lexer;

class LexerAnalyzer {

    /**
     * Array of Lexer rules.
     *
     * @var LexerRule[]
     */
    private array $aRules = [];

    /**
     * Get the rules.
     *
     * @return LexerRule[] 
     */
    public function getRules(): array {
        return $this->aRules;
    }

    /**
     * Add a token rule (regex and name).
     *
     * @param LexerRule $oRule - Token regex pattern
     * @return $this
     */
    public function addRule(LexerRule $oRule): self {
        $this->aRules[] = $oRule;
        return $this;
    }

    /**
     * Tokenize an input string.
     *
     * @param string $sSource - Input string to tokenize
     * @return LexerToken[] - Array of LexerToken objects
     */
    public function tokenize(string $sSource): array {
        $aTokens = [];
        $iPosition = 0;
        $iLine = 1;
        $bLineComment = false;
        $bInsideComment = false;
        while ($iPosition < strlen($sSource)) {
            if ($bInsideComment) {
                $this->skipBlockComment($sSource, $iPosition, $bInsideComment, $iLine);
                continue;
            }

            if ($bLineComment) {
                $this->skipLineComment($sSource, $iPosition, $iLine, $bLineComment);
                continue;
            }

            $bMatched = $this->processRules($sSource, $iPosition, $iLine, $bLineComment, $bInsideComment, $aTokens);

            if (!$bMatched) {
                $this->handleInvalidToken($sSource, $iPosition, $iLine);
            }
        }

        return $aTokens;
    }

    /**
     * Process all rules to match a token at the current position.
     *
     * @param string $sSource
     * @param int $iPosition
     * @param int $iLine
     * @param bool $bLineComment
     * @param bool $bInsideComment
     * @param array $aTokens
     * @return bool
     */
    private function processRules(string $sSource, int &$iPosition, int &$iLine, bool &$bLineComment, bool &$bInsideComment, array &$aTokens): bool {
        foreach ($this->aRules as $oRule) {
            if ($this->matchRule($sSource, $iPosition, $oRule, $iLine, $bLineComment, $bInsideComment, $aTokens)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Match a single rule at the current position.
     *
     * @param string $sSource
     * @param int $iPosition
     * @param LexerRule $oRule
     * @param int $iLine
     * @param bool $bLineComment
     * @param bool $bInsideComment
     * @param array $aTokens
     * @return bool
     */
    private function matchRule(string $sSource, int &$iPosition, LexerRule $oRule, int &$iLine, bool &$bLineComment, bool &$bInsideComment, array &$aTokens): bool {
        $sSegment = substr($sSource, $iPosition);
        if (preg_match('/^' . $oRule->getPattern() . '/', $sSegment, $aMatches)) {
            $sValue = $aMatches[0];
            $iPosition += strlen($sValue);
            $iLine += substr_count($sValue, "\n");
            if ($this->handleCommentToken($oRule, $bLineComment, $bInsideComment)) {
                return true;
            }

            if ($oRule->shouldSkip()) {
                return true;
            }

            $aTokens[] = LexerToken::typeNameValueLine($oRule->getType(), $oRule->getName(), $sValue, $iLine);
            return true;
        }
        return false;
    }

    /**
     * Handle comment tokens and update their respective flags.
     *
     * @param LexerRule $oRule
     * @param bool $bLineComment
     * @param bool $bInsideComment
     * @return bool
     */
    private function handleCommentToken(LexerRule $oRule, bool &$bLineComment, bool &$bInsideComment): bool {
        if (static::isTokenCommentType($oRule->getType())) {
            if ($oRule->getType() === 'MULTI_LINE_COMMENT_START') {
                $bInsideComment = true;
            }
            if ($oRule->getType() === 'LINE_COMMENT') {
                $bLineComment = true;
            }
            return true;
        }
        return false;
    }

    /**
     * Skip over block comments.
     *
     * @param string $sSource
     * @param int $iPosition
     * @param bool $bInsideComment
     * @param int $iLine
     */
    private function skipBlockComment(string $sSource, int &$iPosition, bool &$bInsideComment, int &$iLine): void {
        $sSegment = substr($sSource, $iPosition);
        foreach ($this->aRules as $oRule) {
            if ($oRule->getType() === 'MULTI_LINE_COMMENT_END' && preg_match('/^' . $oRule->getPattern() . '/', $sSegment, $aMatches)) {
                $iPosition += strlen($aMatches[0]);
                $bInsideComment = false;
                return;
            }
        }
        $iPosition = strpos($sSource, "\n", $iPosition);
        if ($iPosition !== false) {
            $iPosition++;
            $iLine++;
        }
    }

    /**
     * Skip over line comments.
     *
     * @param string $sSource
     * @param int $iPosition
     * @param int $iLine
     * @param bool $bLineComment
     */
    private function skipLineComment(string $sSource, int &$iPosition, int &$iLine, bool &$bLineComment): void {
        $iPosition = strpos($sSource, "\n", $iPosition);
        if ($iPosition !== false) {
            $iPosition++;
            $iLine++;
        }
        $bLineComment = false;
    }

    private function handleInvalidToken(string $sSource, int $iPosition, int $iLine): void {
        $aErrorData = $this->extractErrorDetails($sSource, $iPosition, $iLine);
        $sErrorMessage = $this->formatErrorMessage($aErrorData);
        $this->generateInvalidTokenException($sErrorMessage);
    }

    /**
     * Extract error details based on the position and line of the invalid token.
     *
     * @param string $sSource
     * @param int $iPosition
     * @param int $iLine
     * @return array - Details of the error
     */
    private function extractErrorDetails(string $sSource, int $iPosition, int $iLine): array {
        return [
            'lineNumber' => $iLine,
            'column' => $iColumn = $this->calculateColumn($sSource, $iPosition),
            'invalidChar' => $this->getInvalidCharacter($sSource, $iPosition),
            'currentLine' => $this->getCurrentLine($sSource, $iLine),
            'highlightIndicator' => str_repeat(' ', $iColumn),
        ];
    }

    /**
     * Get the content of the current line where the error occurred.
     *
     * @param string $sSource
     * @param int $iLine
     * @return string - Current line content
     */
    private function getCurrentLine(string $sSource, int $iLine): string {
        $aLines = explode("\n", $sSource);
        return $aLines[$iLine] ?? '';
    }

    /**
     * Calculate the column of the invalid character in the current line.
     *
     * @param string $sSource
     * @param int $iPosition
     * @return int - Column number
     */
    private function calculateColumn(string $sSource, int $iPosition): int {
        $iLineStart = strrpos(substr($sSource, 0, $iPosition), "\n");
        return $iPosition - ($iLineStart !== false ? $iLineStart : 0) - 1;
    }

    /**
     * Get the invalid character that caused the error.
     *
     * @param string $sSource
     * @param int $iPosition
     * @return string - Invalid character or 'EOF' if end of file
     */
    private function getInvalidCharacter(string $sSource, int $iPosition): string {
        return $sSource[$iPosition] ?? 'EOF';
    }
    
    /**
     * Format the error message based on the extracted data.
     *
     * @param array $aErrorData
     * @return string - The formatted error message
     */
    private function formatErrorMessage(array $aErrorData): string {
        $aErrorDataFormated = [];
        foreach($aErrorData as $sData => $xValue) {
            $aErrorDataFormated["{{$sData}}"] = $xValue;
        }
        return strtr(static::invalidTokenMessageTemplate(), $aErrorDataFormated + ['\n' => PHP_EOL]);
    }
    
    /**
     * Generate an exception for an invalid token.
     *
     * @param string $sErrorMessage
     * @throws \Exception
     */
    private function generateInvalidTokenException(string $sErrorMessage): void {
        throw new \Exception($sErrorMessage);
    }

    /**
     * Check if the token type is a comment type.
     *
     * @param string $sTokenType
     * @return string
     */
    private static function invalidTokenMessageTemplate() {
        return 'Error: Invalid token found at line {lineNumber}, column {column}.\nProblematic character: \'{invalidChar}\'.\nContext:\n{currentLine}\n{highlightIndicator}^';
    }

    /**
     * Check if the token type is a comment type.
     *
     * @param string $sTokenType
     * @return bool
     */
    private static function isTokenCommentType(string $sTokenType): bool {
        return in_array($sTokenType, static::getTokenCommentTypeMap());
    }

    /**
     * Get the token types that indicate comments.
     *
     * @return string[]
     */
    private static function getTokenCommentTypeMap(): array {
        return [
            'LINE_COMMENT',
            'MULTI_LINE_COMMENT_START',
            'MULTI_LINE_COMMENT_END'
        ];
    }
}
