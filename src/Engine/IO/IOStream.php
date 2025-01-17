<?php
namespace GrammiCore\Engine\IO;

use GrammiCore\GrammarParser\Tree\AbstractSyntaxTreeNode;

/**
 * Class representing the standard input, output, and error streams of a code execution environment.
 */
class IOStream {

    /**
     * @var string Standard output stream
     */
    private string $stdOut = '';

    /**
     * @var string Standard error stream
     */
    private string $stdErr = '';

    /**
     * @var string Standard input stream
     */
    private string $stdIn = '';

    /**
     * Get the current content of the standard output.
     *
     * @return string
     */
    public function getStdOut() {
        return $this->stdOut;
    }

    /**
     * Append content to the standard output.
     *
     * @param string $sOutput
     * @return self
     */
    public function appendStdOut($xOutput = '') {
        $this->stdOut .= $xOutput;
        return $this;
    }

    /**
     * Get the current content of the standard error.
     *
     * @return string
     */
    public function getStdErr() {
        return $this->stdErr;
    }

    /**
     * Verify if the stdErro is not empty.
     *
     * @return string
     */
    public function hasError() {
        return !empty($this->stdErr);
    }

    /**
     * Append content to the standard error.
     *
     * @param string $sError
     * @return self
     */
    public function appendStdErr($sError) {
        $this->stdErr .= $sError;
        return $this;
    }

    /**
     * Get the current content of the standard input.
     *
     * @return string
     */
    public function getStdIn() {
        return $this->stdIn;
    }

    /**
     * Set the content of the standard input.
     *
     * @param string $sInput
     * @return self
     */
    public function setStdIn(string $sInput) {
        $this->stdIn = $sInput;
        return $this;
    }

    /**
     * Clear all streams (stdIn, stdOut, stdErr).
     *
     * @return self
     */
    public function clearAll() {
        $this->stdIn = '';
        $this->stdOut = '';
        $this->stdErr = '';
        return $this;
    }

    /**
     * Clear the standard output.
     *
     * @return self
     */
    public function clearStdOut() {
        $this->stdOut = '';
        return $this;
    }

    /**
     * Clear the standard error.
     *
     * @return self
     */
    public function clearStdErr() {
        $this->stdErr = '';
        return $this;
    }

    /**
     * Clear the standard input.
     *
     * @return self
     */
    public function clearStdIn() {
        $this->stdIn = '';
        return $this;
    }
}
