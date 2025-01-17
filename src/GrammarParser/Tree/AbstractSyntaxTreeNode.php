<?php
namespace GrammiCore\GrammarParser\Tree;

class AbstractSyntaxTreeNode {

    protected string $sType;
    protected array $aProperties;

    /**
     * ASTNode constructor
     * 
     * @param string $sType Type of the AST node
     * @param array $aProperties Properties associated with the AST node
     * @param mixed $oOriginToken The origin token associated with the AST node
     */
    public function __construct(string $sType, array $aProperties = []) {
        $this->sType = $sType;
        $this->aProperties = $aProperties;
    }

    /**
     * Get the type of the node
     * 
     * @return string Type of the AST node
     */
    public function getType(): string {
        return $this->sType;
    }

    /**
     * Set or update a property of the node
     * 
     * @param string $sKey Property key
     * @param mixed $xValue Property value
     * @return self
     */
    public function setProperty($sKey, $xValue): self {
        $this->aProperties[$sKey] = $xValue;
        return $this;
    }

    /**
     * Get the properties of the node
     * 
     * @return array
     */
    public function getProperties(): array {
        return $this->aProperties;
    }

    /**
     * Get a specific property from the node
     * 
     * @param string $sProperty Property name
     * @return mixed
     */
    public function getProperty(string $sProperty) {
        return $this->aProperties[$sProperty] ?? null;
    }

    /**
     * Convert node's properties to an array of representations
     * 
     * @return array
     */
    protected function convertPropertiesToArray(): array {
        $aProperties = $this->aProperties;
        array_walk_recursive($aProperties, function(&$xElement) {
            if ($xElement instanceof AbstractSyntaxTreeNode) {
                $xElement = $xElement->toArray();
            }
        });
        return $aProperties;
    }

    /**
     * Convert node to an array representation
     * 
     * @return array
     */
    public function toArray(): array {
        $aArrayRepresentation = [
            'type' => $this->sType,
            'properties' => $this->convertPropertiesToArray(),
        ];
        return array_filter($aArrayRepresentation, function ($xValue) {
            return !empty($xValue);
        });
    }
}
