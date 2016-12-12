<?php

namespace NFePHP\Common\Dom;

/**
 * Classe auxiliar para validar documentos XML com o seu respecitvo XSD
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Dom\ValidXsd
 * @copyright Copyright (c) 2008-2015
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;

class ValidXsd
{
    public static $errors = array();
    
    public static function validar($xml = '', $xsd = '')
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        libxml_clear_errors();
        if (! $dom->schemaValidate($xsd)) {
            $aIntErrors =   libxml_get_errors();
            foreach ($aIntErrors as $intError) {
                self::$errors[] = self::zTranslateError($intError->message) . "\n";
            }
            return false;
        }
        return true;
    }
    
    /**
     * zTranslateError
     *
     * @param  string $msg
     * @return string
     */
    protected static function zTranslateError($msg)
    {
        $enErr = array(
            "{http://www.portalfiscal.inf.br/nfe}",
            "[facet 'pattern']",
            "The value",
            "is not accepted by the pattern",
            "has a length of",
            "[facet 'minLength']",
            "this underruns the allowed minimum length of",
            "[facet 'maxLength']",
            "this exceeds the allowed maximum length of",
            "Element",
            "attribute",
            "is not a valid value of the local atomic type",
            "is not a valid value of the atomic type",
            "Missing child element(s). Expected is",
            "The document has no document element",
            "[facet 'enumeration']",
            "one of",
            "failed to load external entity",
            "Failed to locate the main schema resource at",
            "This element is not expected. Expected is",
            "is not an element of the set"
        );

        $ptErr = array(
            "",
            "[Erro 'Layout']",
            "O valor",
            "n�o � aceito para o padr�o.",
            "tem o tamanho",
            "[Erro 'Tam. Min']",
            "deve ter o tamanho m�nimo de",
            "[Erro 'Tam. Max']",
            "Tamanho m�ximo permitido",
            "Elemento",
            "Atributo",
            "n�o � um valor v�lido",
            "n�o � um valor v�lido",
            "Elemento filho faltando. Era esperado",
            "Falta uma tag no documento",
            "[Erro 'Conte�do']",
            "um de",
            "falha ao carregar entidade externa",
            "Falha ao tentar localizar o schema principal em",
            "Este elemento n�o � esperado. Esperado �",
            "n�o � um dos seguintes possiveis"
        );
        return str_replace($enErr, $ptErr, $msg);
    }
}
