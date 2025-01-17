<?php
namespace GrammiCore\GrammarParser;
use GrammiCore\Engine\LanguageGrammarAbstractWrapper;
use GrammiCore\GrammarParser\Strategies\ParserStrategy;
use GrammiCore\GrammarParser\Tree\ProgramStatementNode;
use GrammiCore\Lexer\LexerToken;
use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;

/**
 * Class LanguageGrammarParser
 * Parses a list of tokens into an Abstract Syntax Tree (AST)
 */
class LanguageGrammarParser extends LanguageGrammarAbstractWrapper {

    /**
     * @var array
     */
    private $aTokens;
    
    /**
     * @var int
     */
    private int $iCurrentToken = 0;

    /**
     * @var ParserStrategy[] 
     */
    protected $aStrategies = [];

    /**
     * Parser constructor
     * 
     * @param array $aTokens List of tokens to parse
     */
    public function __construct(array $aTokens) {
        parent::__construct();
        $this->aTokens = $aTokens;
    }

    /**
     * Get the strategy suffix
     * 
     * @return string
     */
    protected static function Strategiesuffix() {
        return 'ParserStrategy';
    }

    /**
     * Peek at the current token without advancing the cursor
     * 
     * @return LexerToken|null
     */
    public function peek(): ?LexerToken {
        return $this->aTokens[$this->iCurrentToken] ?? null;
    }

    /**
     * Get the next token and advance the cursor
     * 
     * @return LexerToken|null
     */
    public function next(): ?LexerToken {
        return $this->aTokens[$this->iCurrentToken++] ?? null;
    }

    /**
     * Get the next token and advance the cursor by a specific number of times
     * 
     * @param int $iTimes Number of times to advance the cursor
     * @return LexerToken|null
     */
    public function nextTimes(int $iTimes): ?LexerToken {
        $this->iCurrentToken += $iTimes;
        return $this->aTokens[$this->iCurrentToken] ?? null;
    }

    /**
     * Get the next token without advancing the cursor
     * 
     * @return LexerToken|null
     */
    public function peekNext(): ?LexerToken {
        $iToken = $this->iCurrentToken + 1;
        return $this->aTokens[$iToken] ?? null;
    }

    /**
     * Get the previous token without advancing the cursor
     * 
     * @return LexerToken|null
     */
    public function peekPrevious(): ?LexerToken {
        $iToken = $this->iCurrentToken - 1;
        return $this->aTokens[$iToken] ?? null;
    }

    /**
     * Get the previous token and move the cursor back
     * 
     * @return LexerToken|null
     */
    public function previous(): ?LexerToken {
        return $this->aTokens[--$this->iCurrentToken] ?? null;
    }

    /**
     * Get the previous token and move the cursor back by a specific number of times
     * 
     * @param int $iTimes Number of times to move the cursor back
     * @return LexerToken|null
     */
    public function previousTimes(int $iTimes): ?LexerToken {
        $this->iCurrentToken -= $iTimes;
        return $this->aTokens[$this->iCurrentToken] ?? null;
    }

    /**
     * Expect the next token to be of a specific type
     * 
     * @param string|array $xType Expected token type(s)
     * @return LexerToken
     * @throws \Exception
     */
    public function expect($xType): LexerToken {
        $aTypesExpect = is_array($xType) ? $xType : [$xType];
        $oToken = $this->next();
        $bMatched = false;
        foreach ($aTypesExpect as $sExpectedType) {
            if ($oToken === null) {
                $this->expectException($sExpectedType, $oToken);
            }
            $bMatched = $oToken->getType() === $sExpectedType;
            if ($bMatched) {
                break;
            }
        }
        if (!$bMatched) {
            $this->expectException($sExpectedType, $oToken);
        }
        return $oToken;
    }

    /**
     * Throw an exception if the token is not of the expected type
     * 
     * @param string $sType Expected token type
     * @param LexerToken|null $oToken The token to check
     * @throws \Exception
     */
    public function expectException(string $sType, ?LexerToken $oToken) {
        throw new \Exception("Expected token type $sType, got " . ($oToken ? $oToken->getType() : 'null'));
    }

    /**
     * Parse the list of tokens into an AST
     * 
     * @return AbstractSyntaxTreeNode|null
     */
    public function parse() {
        try {
            return $this->parseProgram();
        } 
        catch (\Exception $oEx) {
            $this->getIO()->appendStdErr($oEx->getMessage());
        }
    }

    /**
     * Parse the entire program
     * 
     * @return ProgramStatementNode
     */
    private function parseProgram(): ProgramStatementNode {
        $aBody = [];
        while ($this->peek() !== null) {
            $aBody[] = $this->parseStatement();
        }
        return new ProgramStatementNode($aBody);
    }

    /**
     * Parse a single statement
     * 
     * @return AbstractSyntaxTreeNode
     */
    public function parseStatement() {
        $oToken = $this->peek();
        $sAbstractSyntaxTreeNodeType = static::getAbstractSyntaxTreeTypeFromTokenType($oToken->getType());
        $oStrategy = $this->instanceStrategyFromNodeType($sAbstractSyntaxTreeNodeType);
        $oParseResult = $oStrategy->setParser($this)->parse();
        $oParseResult->setProperty('line', $oToken->getLine());
        return $oParseResult;
    }

    /**
     * 
     * @param string $sTokenType Token type
     * @return string
     */
    protected static function getAbstractSyntaxTreeTypeFromTokenType($sTokenType) {
        $aRelations = static::relationBetweenTokenTypeAbstractSyntaxTreeType();
        if (isset($aRelations[$sTokenType])) {
            return $aRelations[$sTokenType];
        }
        return 'ExpressionStatement';
    }

    /**
     * Define the relations between token types and AST node types
     * 
     * @return array
     */
    protected static function relationBetweenTokenTypeAbstractSyntaxTreeType() {
        return [
            'VAR_KEYWORD'      => 'VariableDeclaration',
            'FUNCTION_KEYWORD' => 'FunctionDeclaration',
            'CLASS_KEYWORD'    => 'ClassDeclaration',
            'NEW_KEYWORD'      => 'NewExpression',
            'RETURN_KEYWORD'   => 'ReturnStatement',
            'IF_KEYWORD'       => 'ConditionStatement',
            'FOR_KEYWORD'      => 'ForStatement',
            'BLOCK_START'      => 'BlockStatement',
            'TRY_KEYWORD'      => 'TryCatchStatement',
            'THROW_KEYWORD'    => 'ThrowExpression',
        ];
    }
}