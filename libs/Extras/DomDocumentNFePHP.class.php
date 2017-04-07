<?php

namespace NFePHP\Extras;

use \DOMDocument;

class DomDocumentNFePHP extends DOMDocument
{

    /**
     * construtor
     * Executa o construtor-pai do DOMDocument e por padr�o define o XML sem espa�os
     * e sem identa��o
     *
     * @param  string $sXml Conte�do XML opcional a ser carregado no DOM Document.
     * @return void
     */
    public function __construct($sXml = null)
    {
        parent::__construct('1.0', 'utf-8');
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
        
        if (is_string($sXml)) {
            $this->loadXML($sXml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
    }
}
