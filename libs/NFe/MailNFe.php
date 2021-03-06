<?php

namespace NFePHP\NFe;

/**
 * Classe para envio dos emails aos interessados
 *
 * @category  NFePHP
 * @package   NFePHP\NFe\MailNFe
 * @copyright Copyright (c) 2008-2015
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMail;
use NFePHP\Common\Exception;
use Html2Text\Html2Text;
use \DOMDocument;

class MailNFe extends BaseMail
{
    public $error = '';
    protected $msgHtml = '';
    protected $msgTxt = '';
    protected $aMail = array();
    
    /**
     * envia
     *
     * @param  string $pathFile
     * @param  array  $aMail
     * @param  bool   $comPdf
     * @param  string $pathPdf
     * @return bool
     */
    public function envia($pathFile = '', $aMail = array(), $comPdf = false, $pathPdf = '')
    {
        if ($comPdf && $pathPdf != '') {
            $this->addAttachment($pathPdf, '');
        }
        $assunto = $this->zMontaMessagem($pathFile);
        //cria o anexo do xml
        $this->addAttachment($pathFile, '');
        //constroi a mensagem
        $this->buildMessage($this->msgHtml, $this->msgTxt);
        if (sizeof($aMail)) {
            // Se for informado um ou mais e-mails no $aMail, utiliza eles
            $this->aMail = $aMail;
        } elseif (!sizeof($this->aMail)) {
            // Caso n�o seja informado nenhum e-mail e n�o tenha sido recuperado qualquer e-mail do xml
            throw new Exception\RuntimeException('Nenhum e-mail informado ou recuperado do XML.');
        }
        $err = $this->sendMail($assunto, $this->aMail);
        if ($err === true) {
            return true;
        } else {
            $this->error = $err;
            return false;
        }
        return true;
    }
    
    /**
     * zMontaMessagem
     *
     * @param string $pathFile
     */
    protected function zMontaMessagem($pathFile)
    {
        $dom = new Dom();
        $dom->loadXMLFile($pathFile);
        $infNFe = $dom->getNode('infNFe', 0);
        $ide = $infNFe->getElementsByTagName('ide')->item(0);
        $dest = $infNFe->getElementsByTagName('dest')->item(0);
        $emit = $infNFe->getElementsByTagName('emit')->item(0);
        $icmsTot = $infNFe->getElementsByTagName('ICMSTot')->item(0);
        $razao = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
        $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $xNome = $dest->getElementsByTagName('xNome')->item(0)->nodeValue;
        $dhEmi = ! empty($ide->getElementsByTagName('dhEmi')->item(0)->nodeValue) ?
                $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue :
                $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $data = date('d/m/Y', DateTime::convertSefazTimeToTimestamp($dhEmi));
        $vNF = $icmsTot->getElementsByTagName('vNF')->item(0)->nodeValue;
        $this->aMail[] = !empty($dest->getElementsByTagName('email')->item(0)->nodeValue) ?
                $dest->getElementsByTagName('email')->item(0)->nodeValue :
                '';
        //pega os emails que existirem em obsCont
        $infAdic = $infNFe->getElementsByTagName('infAdic')->item(0);
        if (!empty($infAdic)) {
            $obsConts = $infAdic->getElementsByTagName('obsCont');
            foreach ($obsConts as $obsCont) {
                if (strtoupper($obsCont->getAttribute('xCampo')) === 'EMAIL') {
                    $this->aMail[] = $obsCont->getElementsByTagName('xTexto')->item(0)->nodeValue;
                }
            }
        }
        $this->msgHtml = $this->zRenderTemplate($xNome, $data, $nNF, $serie, $vNF, $razao);
        $this->msgTxt = utf8_encode(Html2Text::convert(utf8_decode($this->msgHtml)));
        return "NFe n. $nNF - $razao";
    }
    
    /**
     * zRenderTemplate
     *
     * @param  string $xNome
     * @param  string $data
     * @param  string $nNF
     * @param  string $serie
     * @param  string $vNF
     * @param  string $razao
     * @return string
     */
    protected function zRenderTemplate($xNome, $data, $nNF, $serie, $vNF, $razao)
    {
        $this->zTemplate();
        $temp = $this->template;
        $aSearch = array(
            '{contato}',
            '{data}',
            '{numero}',
            '{serie}',
            '{valor}',
            '{emitente}'
        );
        $aReplace = array(
          $xNome,
          $data,
          $nNF,
          $serie,
          $vNF,
          $razao
        );
        $temp = str_replace($aSearch, $aReplace, $temp);
        return $temp;
    }

    /**
     * zTemplate
     * Seo template estiver vazio cria o basico
     */
    protected function zTemplate()
    {
        if (empty($this->template)) {
            $this->template = "<p><b>Prezados {contato},</b></p>".
                "<p>Voc� est� recebendo a Nota Fiscal Eletr�nica emitida em {data} com o n�mero ".
                "{numero}, s�rie {serie} de {emitente}, no valor de R$ {valor}. ".
                "Junto com a mercadoria, voc� receber� tamb�m um DANFE (Documento ".
                "Auxiliar da Nota Fiscal Eletr�nica), que acompanha o tr�nsito das mercadorias.</p>".
                "<p><i>Podemos conceituar a Nota Fiscal Eletr�nica como um documento ".
                "de exist�ncia apenas digital, emitido e armazenado eletronicamente, ".
                "com o intuito de documentar, para fins fiscais, uma opera��o de ".
                "circula��o de mercadorias, ocorrida entre as partes. Sua validade ".
                "jur�dica garantida pela assinatura digital do remetente (garantia ".
                "de autoria e de integridade) e recep��o, pelo Fisco, do documento ".
                "eletr�nico, antes da ocorr�ncia do Fato Gerador.</i></p>".
                "<p><i>Os registros fiscais e cont�beis devem ser feitos, a partir ".
                "do pr�prio arquivo da NF-e, anexo neste e-mail, ou utilizando o ".
                "DANFE, que representa graficamente a Nota Fiscal Eletr�nica. ".
                "A validade e autenticidade deste documento eletr�nico pode ser ".
                "verificada no site nacional do projeto (www.nfe.fazenda.gov.br), ".
                "atrav�s da chave de acesso contida no DANFE.</i></p>".
                "<p><i>Para poder utilizar os dados descritos do DANFE na ".
                "escritura��o da NF-e, tanto o contribuinte destinat�rio, ".
                "como o contribuinte emitente, ter�o de verificar a validade da NF-e. ".
                "Esta validade est� vinculada � efetiva exist�ncia da NF-e nos ".
                "arquivos da SEFAZ, e comprovada atrav�s da emiss�o da Autoriza��o de Uso.</i></p>".
                "<p><b>O DANFE n�o � uma nota fiscal, nem substitui uma nota fiscal, ".
                "servindo apenas como instrumento auxiliar para consulta da NF-e no ".
                "Ambiente Nacional.</b></p>".
                "<p>Para mais detalhes, consulte: <a href=\"http://www.nfe.fazenda.gov.br/\">".
                "www.nfe.fazenda.gov.br</a></p>".
                "<br>".
                "<p>Atenciosamente,</p>".
                "<p>{emitente}</p>";
        }
    }
}
