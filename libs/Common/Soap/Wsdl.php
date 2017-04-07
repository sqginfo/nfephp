<?php

namespace NFePHP\Common\Soap;

/**
 * Classe usada para obter os arquivos WSDL, que s�o as especifica��es
 * da comunica��o SOAP com os webservices da SEFAZ e das Prefeituras;
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Soap
 * @copyright Copyright (c) 2008-2015
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm@gamil.com>
 * @link      http://github.com/nfephp-org/spedphp for the canonical source repository
 */

use NFePHP\Common\Soap;

class Wsdl
{
    /**
     * soapDebug
     *
     * @var string
     */
    public $soapDebug = '';
    
    /**
     * downloadWsdl
     * Baixa o arquivo wsdl necess�rio para a comunica��o SOAP nativa
     * O WSDL pode tamb�m ser usado para verificar a mensagem SOAP com o
     * uso do SOAPUI um recurso muito importante para testes off-line.
     *
     * @param  string $url
     * @param  string $priKeyPath
     * @param  string $pubKeyPath
     * @param  string $certKeyPath
     * @return string
     */
    public function downLoadWsdl($url, $priKeyPath, $pubKeyPath, $certKeyPath)
    {
        $soap = new Soap\CurlSoap($priKeyPath, $pubKeyPath, $certKeyPath);
        $resposta = $soap->getWsdl($url);
        if (!$resposta) {
            $this->soapDebug = $soap->soapDebug;
            return '';
        }
        return $resposta;
    }
}
