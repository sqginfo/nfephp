<?php
/**
 * Este arquivo � parte do projeto NFePHP - Nota Fiscal eletr�nica em PHP.
 *
 * Este programa � um software livre: voc� pode redistribuir e/ou modific�-lo
 * sob os termos da Licen�a P�blica Geral GNU como � publicada pela Funda��o
 * para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior.
 * e/ou
 * sob os termos da Licen�a P�blica Geral Menor GNU (LGPL) como � publicada pela
 * Funda��o para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior.
 *
 * Este programa � distribu�do na esperan�a que ser� �til, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia expl�cita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUA��O PARA UM PROP�SITO EM PARTICULAR,
 * veja a Licen�a P�blica Geral GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a Publica GNU e da
 * Licen�a P�blica Geral Menor GNU (LGPL) junto com este programa.
 * Caso contr�rio consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package   NFePHP
 * @name      DaEventoNFeNFePHP.class.php
 * @version   0.1.4
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Lucas Vaccaro <lucas-vaccaro at outlook dot com>
 *              Roberto Spadim <roberto at spadim dot com dot br>
 */

namespace NFePHP\Extras;

use NFePHP\Extras\NfephpException;
use NFePHP\Extras\PdfNFePHP;
use NFePHP\Extras\CommonNFePHP;
use NFePHP\Extras\DocumentoNFePHP;
use NFePHP\Extras\DomDocumentNFePHP;

//defini��o do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH', 'font/');
}

class Daevento extends CommonNFePHP implements DocumentoNFePHP
{
    public $logoAlign = 'C'; // alinhamento do logo
    public $yDados = 0;
    public $debugMode = 0; // ativa ou desativa o modo de debug
    public $aEnd = array();

    protected $pdf; // objeto fpdf()
    protected $xml; // string XML NFe
    protected $logomarca = ''; // path para logomarca em jpg
    protected $errMsg = ''; // mesagens de erro
    protected $errStatus = false; // status de erro TRUE um erro ocorreu FALSE sem erros
    protected $orientacao = 'P'; // orienta��o da DANFE P-Retrato ou L-Paisagem
    protected $papel = 'A4'; // formato do papel
    protected $destino = 'I'; // destivo do arquivo pdf I-borwser, S-retorna o arquivo, D-for�a download, F-salva em arquivo local
    protected $pdfDir = ''; // diretorio para salvar o pdf com a op��o de destino = F
    protected $fontePadrao = 'Times'; // Nome da Fonte para gerar o DANFE
    protected $version = '0.1.4';
    protected $wPrint; // largura imprimivel
    protected $hPrint; // comprimento imprimivel
    protected $wCanhoto; // largura do canhoto para a formata��o paisagem
    protected $formatoChave = "#### #### #### #### #### #### #### #### #### #### ####";
    // variaveis da carta de corre��o
    public $id;
    public $chNFe;
    public $tpAmb;
    protected $cOrgao;
    protected $xCorrecao;
    protected $xCondUso;
    protected $dhEvento;
    protected $cStat;
    protected $xMotivo;
    protected $xJust;
    protected $CNPJDest = '';
    protected $CPFDest = '';
    protected $dhRegEvento;
    protected $nProt;
    protected $tpEvento;

    // objetos
    private $dom;
    private $procEventoNFe;
    private $evento;
    private $infEvento;
    private $retEvento;
    private $rinfEvento;

    /**
     * __construct
     *
     * @param string $docXML      Arquivo XML (diret�rio ou string)
     * @param string $sOrientacao (Opcional) Orienta��o da impress�o P-retrato L-Paisagem
     * @param string $sPapel      Tamanho do papel (Ex. A4)
     * @param string $sPathLogo   Caminho para o arquivo do logo
     * @param string $sDestino    Destino do PDF I-browser D-download S-string F-salva
     * @param string $sDirPDF     Caminho para o diretorio de armazenamento dos arquivos PDF
     * @param string $fonteDANFE  Nome da fonte alternativa
     * @param array  $aEnd        array com o endere�o do emitente
     * @param number $mododebug   0-N�o 1-Sim e 2-nada (2 default)
     */
    public function __construct(
        $docXML = '',
        $sOrientacao = '',
        $sPapel = '',
        $sPathLogo = '',
        $sDestino = 'I',
        $sDirPDF = '',
        $fontePDF = '',
        $aEnd = '',
        $mododebug = 0
    ) {
        if (is_numeric($mododebug)) {
            $this->debugMode = (int) $mododebug;
        }
        if ($this->debugMode === 1) {
            // ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } elseif ($this->debugMode === 0) {
            // desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        if (is_array($aEnd)) {
            $this->aEnd = $aEnd;
        }
        $this->orientacao = $sOrientacao;
        $this->papel = $sPapel;
        $this->pdf = '';
        // $this->xml = $xmlfile;
        $this->logomarca = $sPathLogo;
        $this->destino = $sDestino;
        $this->pdfDir = $sDirPDF;
        // verifica se foi passa a fonte a ser usada
        if (empty($fontePDF)) {
            $this->fontePadrao = 'Times';
        } else {
            $this->fontePadrao = $fontePDF;
        }
        // se for passado o xml
        if (!is_file($docXML)) {
            if (empty($docXML)) {
                $this->errMsg = 'Um caminho ou um arquivo XML de evento de NFe deve ser passado.';
                $this->errStatus = true;
                return false;
            }
        } else {
            $docXML = file_get_contents($docXML);
        }
        $this->dom = new DomDocumentNFePHP();
        $this->dom->loadXML($docXML);
        $this->procEventoNFe = $this->dom->getElementsByTagName("procEventoNFe")->item(0);
        $this->evento = $this->dom->getElementsByTagName("evento")->item(0);
        $this->infEvento = $this->evento->getElementsByTagName("infEvento")->item(0);
        $this->retEvento = $this->dom->getElementsByTagName("retEvento")->item(0);
        $this->rinfEvento = $this->retEvento->getElementsByTagName("infEvento")->item(0);
        $this->tpEvento = $this->infEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
        if (!in_array(
            $this->tpEvento,
            array(
                    '110110',
                    '110111'
                )
        )
        ) {
            $this->errMsg = 'Evento n�o implementado ' . $tpEvento . ' !!';
            $this->errStatus = true;
            return false;
        }
        $this->id = str_replace('ID', '', $this->infEvento->getAttribute("Id"));
        $this->chNFe = $this->infEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
        $this->aEnd['CNPJ'] = $this->infEvento->getElementsByTagName("CNPJ")->item(0)->nodeValue;
        $this->tpAmb = $this->infEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
        $this->cOrgao = $this->infEvento->getElementsByTagName("cOrgao")->item(0)->nodeValue;
        $this->xCorrecao = $this->infEvento->getElementsByTagName("xCorrecao")->item(0);
        $this->xCorrecao = (empty($this->xCorrecao) ? '' : $this->xCorrecao->nodeValue);
        $this->xCondUso = $this->infEvento->getElementsByTagName("xCondUso")->item(0);
        $this->xCondUso = (empty($this->xCondUso) ? '' : $this->xCondUso->nodeValue);
        $this->xJust = $this->infEvento->getElementsByTagName("xJust")->item(0);
        $this->xJust = (empty($this->xJust) ? '' : $this->xJust->nodeValue);
        $this->dhEvento = $this->infEvento->getElementsByTagName("dhEvento")->item(0)->nodeValue;
        $this->cStat = $this->rinfEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
        $this->xMotivo = $this->rinfEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
        $this->CNPJDest = !empty($this->rinfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue) ? $this->rinfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue : '';
        $this->CPFDest = !empty($this->rinfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue) ? $this->rinfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue : '';
        $this->dhRegEvento = $this->rinfEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
        $this->nProt = $this->rinfEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
    }

    /**
     * simpleConsistencyCheck
     *
     * @return bool Retorna se o documenento se parece com um Evento ( condicao necessaria porem nao suficiente )
     */
    public function simpleConsistencyCheck()
    {
        if ($this->xml == null || $this->infEvento == null || $this->retEvento == null) {
            return false;
        }
        return true;
    }

    /**
     * monta
     *
     * @param  string  $orientacao
     * @param  string  $papel
     * @param  string  $logoAlign
     * @param  int     $situacao_externa
     * @param  boolean $classe_pdf
     * @return number
     */
    public function monta($orientacao = '', $papel = 'A4', $logoAlign = 'C', $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE, $classe_pdf = false)
    {
        return $this->montaDaEventoNFe($orientacao, $papel, $logoAlign, $situacao_externa, $classe_pdf);
    }

    /**
     * montaDAEventoNFe
     *
     * Esta fun��o monta a DaEventoNFe conforme as informa��es fornecidas para a classe
     * durante sua constru��o.
     * A defini��o de margens e posi��es iniciais para a impress�o s�o estabelecidas no
     * pelo conte�do da fun�ao e podem ser modificados.
     *
     * @param  string $orientacao (Opcional) Estabelece a orienta��o da impress�o (ex. P-retrato), se nada for fornecido ser� usado o padr�o da NFe
     * @param  string $papel      (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID do evento extraido do arquivo XML
     */
    public function montaDaEventoNFe($orientacao = '', $papel = 'A4', $logoAlign = 'C', $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE, $classe_pdf = false)
    {
        if ($orientacao == '') {
            $orientacao = 'P';
        }
        $this->orientacao = $orientacao;
        $this->pAdicionaLogoPeloCnpj();
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;

        if ($classe_pdf !== false) {
            $this->pdf = $classe_pdf;
        } else {
            $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        }
        if ($this->orientacao == 'P') {
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posi��o inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if ($this->papel == 'A4') { // A4 210x297mm
                $maxW = 210;
                $maxH = 297;
            }
        } else {
            // margens do PDF
            $margSup = 3;
            $margEsq = 3;
            $margDir = 3;
            // posi��o inicial do relatorio
            $xInic = 5;
            $yInic = 5;
            if ($papel == 'A4') { // A4 210x297mm
                $maxH = 210;
                $maxW = 297;
            }
        } // orienta��o

        // largura imprimivel em mm
        $this->wPrint = $maxW - ($margEsq + $xInic);
        // comprimento imprimivel em mm
        $this->hPrint = $maxH - ($margSup + $yInic);
        // estabelece contagem de paginas
        $this->pdf->AliasNbPages();
        // fixa as margens
        $this->pdf->SetMargins($margEsq, $margSup, $margDir);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetFillColor(255, 255, 255);
        // inicia o documento
        $this->pdf->Open();
        // adiciona a primeira p�gina
        $this->pdf->AddPage($this->orientacao, $this->papel);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->SetTextColor(0, 0, 0);
        // montagem da p�gina
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        // coloca o cabe�alho
        $y = $this->pHeader($x, $y, $pag, $situacao_externa);
        // coloca os dados da CCe
        $y = $this->pBody($x, $y + 15);
        // coloca os dados da CCe
        $y = $this->pFooter($x, $y + $this->hPrint - 20);

        // retorna o ID do evento
        if ($classe_pdf !== false) {
            $aR = array(
                'id' => $this->id,
                'classe_PDF' => $this->pdf
            );
            return $aR;
        } else {
            return $this->id;
        }
    }

    /**
     * pHeader
     *
     * @param  number $x
     * @param  number $y
     * @param  number $pag
     * @return number
     */
    private function pHeader($x, $y, $pag, $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE)
    {
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;

        // ####################################################################################
        // coluna esquerda identifica��o do emitente
        $w = round($maxW * 0.41, 0); // 80;
        if ($this->orientacao == 'P') {
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 6,
                'style' => 'I'
            );
        } else {
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => 'B'
            );
        }
        $w1 = $w;
        $h = 32;
        $oldY += $h;
        $this->pTextBox($x, $y, $w, $h);
        $texto = 'IDENTIFICA��O DO EMITENTE';
        $this->pTextBox($x, $y, $w, 5, $texto, $aFont, 'T', 'C', 0, '');
        if (is_file($this->logomarca)) {
            $logoInfo = getimagesize($this->logomarca);
            // largura da imagem em mm
            $logoWmm = ($logoInfo[0] / 72) * 25.4;
            // altura da imagem em mm
            $logoHmm = ($logoInfo[1] / 72) * 25.4;
            if ($this->logoAlign == 'L') {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = $x + 1;
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                // estabelecer posi��es do texto
                $x1 = round($xImg + $nImgW + 1, 0);
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            if ($this->logoAlign == 'C') {
                $nImgH = round($h / 3, 0);
                $nImgW = round($logoWmm * ($nImgH / $logoHmm), 0);
                $xImg = round(($w - $nImgW) / 2 + $x, 0);
                $yImg = $y + 3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1, 0);
                $tw = $w;
            }
            if ($this->logoAlign == 'R') {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = round($x + ($w - (1 + $nImgW)), 0);
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                $x1 = $x;
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH, 'jpeg');
        } else {
            $x1 = $x;
            $y1 = round($h / 3 + $y, 0);
            $tw = $w;
        }

        // Nome emitente
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $texto = (isset($this->aEnd['razao']) ? $this->aEnd['razao'] : '');
        $this->pTextBox($x1, $y1, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');

        // endere�o
        $y1 = $y1 + 6;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => ''
        );
        $lgr = (isset($this->aEnd['logradouro']) ? $this->aEnd['logradouro'] : '');
        $nro = (isset($this->aEnd['numero']) ? $this->aEnd['numero'] : '');
        $cpl = (isset($this->aEnd['complemento']) ? $this->aEnd['complemento'] : '');
        $bairro = (isset($this->aEnd['bairro']) ? $this->aEnd['bairro'] : '');
        $CEP = (isset($this->aEnd['CEP']) ? $this->aEnd['CEP'] : '');
        $CEP = $this->pFormat($CEP, "#####-###");
        $mun = (isset($this->aEnd['municipio']) ? $this->aEnd['municipio'] : '');
        $UF = (isset($this->aEnd['UF']) ? $this->aEnd['UF'] : '');
        $fone = (isset($this->aEnd['telefone']) ? $this->aEnd['telefone'] : '');
        $email = (isset($this->aEnd['email']) ? $this->aEnd['email'] : '');
        $foneLen = strlen($fone);
        if ($foneLen > 0) {
            $fone2 = substr($fone, 0, $foneLen - 4);
            $fone1 = substr($fone, 0, $foneLen - 8);
            $fone = '(' . $fone1 . ') ' . substr($fone2, - 4) . '-' . substr($fone, - 4);
        } else {
            $fone = '';
        }
        if ($email != '') {
            $email = 'Email: ' . $email;
        }
        $texto = "";
        $tmp_txt = trim(($lgr != '' ? "$lgr, " : '') . ($nro != 0 ? $nro : "SN") . ($cpl != '' ? " - $cpl" : ''));
        $tmp_txt = ($tmp_txt == 'SN' ? '' : $tmp_txt);
        $texto .= ($texto != '' && $tmp_txt != '' ? "\n" : '') . $tmp_txt;
        $tmp_txt = trim($bairro . ($bairro != '' && $CEP != '' ? " - " : '') . $CEP);
        $texto .= ($texto != '' && $tmp_txt != '' ? "\n" : '') . $tmp_txt;
        $tmp_txt = $mun;
        $tmp_txt .= ($tmp_txt != '' && $UF != '' ? " - " : '') . $UF;
        $tmp_txt .= ($tmp_txt != '' && $fone != '' ? " " : '') . $fone;
        $texto .= ($texto != '' && $tmp_txt != '' ? "\n" : '') . $tmp_txt;
        $tmp_txt = $email;
        $texto .= ($texto != '' && $tmp_txt != '' ? "\n" : '') . $tmp_txt;
        $this->pTextBox($x1, $y1 - 2, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');

        // ##################################################

        $w2 = round($maxW - $w, 0);
        $x += $w;
        $this->pTextBox($x, $y, $w2, $h);

        $y1 = $y + $h;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 16,
            'style' => 'B'
        );
        if ($this->tpEvento == '110110') {
            $texto = 'Representa��o Gr�fica de CC-e';
        } else {
            $texto = 'Representa��o Gr�fica de Evento';
        }
        $this->pTextBox($x, $y + 2, $w2, 8, $texto, $aFont, 'T', 'C', 0, '');

        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'I'
        );
        if ($this->tpEvento == '110110') {
            $texto = '(Carta de Corre��o Eletr�nica)';
        } elseif ($this->tpEvento == '110111') {
            $texto = '(Cancelamento de NFe)';
        }
        $this->pTextBox($x, $y + 7, $w2, 8, $texto, $aFont, 'T', 'C', 0, '');

        $texto = 'ID do Evento: ' . $this->id;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $this->pTextBox($x, $y + 15, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $tsHora = $this->pConvertTime($this->dhEvento);
        $texto = 'Criado em : ' . date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y + 20, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $tsHora = $this->pConvertTime($this->dhRegEvento);
        $texto = 'Prococolo: ' . $this->nProt . '  -  Registrado na SEFAZ em: ' . date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y + 25, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        // $cStat;
        // $tpAmb;
        // ####################################################

        $x = $oldX;
        $this->pTextBox($x, $y1, $maxW, 40);
        $sY = $y1 + 40;
        if ($this->tpEvento == '110110') {
            $texto = 'De acordo com as determina��es legais vigentes, vimos por meio desta comunicar-lhe que a Nota Fiscal, abaixo referenciada, cont�m irregularidades que est�o destacadas e suas respectivas corre��es, solicitamos que sejam aplicadas essas corre��es ao executar seus lan�amentos fiscais.';
        } elseif ($this->tpEvento == '110111') {
            $texto = 'De acordo com as determina��es legais vigentes, vimos por meio desta comunicar-lhe que a Nota Fiscal, abaixo referenciada, est� cancelada, solicitamos que sejam aplicadas essas corre��es ao executar seus lan�amentos fiscais.';
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $this->pTextBox($x + 5, $y1, $maxW - 5, 20, $texto, $aFont, 'T', 'L', 0, '', false);

        // ############################################
        $x = $oldX;
        $y = $y1;
        if ($this->CNPJDest != '') {
            $texto = 'CNPJ do Destinat�rio: ' . $this->pFormat($this->CNPJDest, "##.###.###/####-##");
        }
        if ($this->CPFDest != '') {
            $texto = 'CPF do Destinat�rio: ' . $this->pFormat($this->CPFDest, "###.###.###-##");
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $this->pTextBox($x + 2, $y + 13, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $numNF = substr($this->chNFe, 25, 9);
        $serie = substr($this->chNFe, 22, 3);
        $numNF = $this->pFormat($numNF, "###.###.###");
        $texto = "Nota Fiscal: " . $numNF . '  -   S�rie: ' . $serie;
        $this->pTextBox($x + 2, $y + 19, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $bW = 87;
        $bH = 15;
        $x = 55;
        $y = $y1 + 13;
        $w = $maxW;
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x + (($w - $bW) / 2), $y + 2, $this->chNFe, $bW, $bH);
        $this->pdf->SetFillColor(255, 255, 255);
        $y1 = $y + 2 + $bH;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $texto = $this->pFormat($this->chNFe, $this->formatoChave);
        $this->pTextBox($x, $y1, $w - 2, $h, $texto, $aFont, 'T', 'C', 0, '');

        $RETURN_VALUE = $sY + 2;
        // $sY += 1;
        if ($this->tpEvento == '110110') {
            $x = $oldX;
            $this->pTextBox($x, $sY, $maxW, 15);
            $texto = $this->xCondUso;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => 'I'
            );
            $this->pTextBox($x + 2, $sY + 2, $maxW - 2, 15, $texto, $aFont, 'T', 'L', 0, '', false);
            $RETURN_VALUE = $sY + 2;
        }
        // ///////////////
        if ($situacao_externa == NFEPHP_SITUACAO_EXTERNA_CANCELADA && $this->tpEvento != '110111') { // n�o tem sentido colcoar cancelado no cancelamento...
                                     // 101 Cancelamento
            $x = 10;
            $y = $this->hPrint - 130;
            $h = 25;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "NFe CANCELADA";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B'
            );
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        if ($situacao_externa == NFEPHP_SITUACAO_EXTERNA_DENEGADA) {
            // no denegado tem sentido, pois n�o deveria haver este evento...
            // 110 301 302 Denegada
            $x = 10;
            $y = $this->hPrint - 130;
            $h = 25;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "NFe USO DENEGADO";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B'
            );
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $y += $h;
            $h = 5;
            $w = $maxW - (2 * $x);
            $texto = "SEM VALOR FISCAL";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B'
            );
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        // indicar sem valor
        if ($this->tpAmb != 1) {
            $x = 10;
            if ($this->orientacao == 'P') {
                $y = round($this->hPrint * 2 / 3, 0);
            } else {
                $y = round($this->hPrint / 2, 0);
            }
            $h = 5;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B'
            );
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 30,
                'style' => 'B'
            );
            $texto = "AMBIENTE DE HOMOLOGA��O";
            $this->pTextBox($x, $y + 14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        return $RETURN_VALUE;
    }

    /**
     * pBody
     *
     * @param number $x
     * @param number $y
     */
    private function pBody($x, $y)
    {
        $maxW = $this->wPrint;
        if ($this->tpEvento == '110110') {
            $texto = 'CORRE��ES A SEREM CONSIDERADAS';
        } else {
            $texto = 'JUSTIFICATIVA DO CANCELAMENTO';
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'B'
        );
        $this->pTextBox($x, $y, $maxW, 5, $texto, $aFont, 'T', 'L', 0, '', false);

        $y += 5;
        $this->pTextBox($x, $y, $maxW, 190);
        if ($this->tpEvento == '110110') {
            $texto = $this->xCorrecao;
        } elseif ($this->tpEvento == '110111') {
            $texto = $this->xJust;
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $this->pTextBox($x + 2, $y + 2, $maxW - 2, 150, $texto, $aFont, 'T', 'L', 0, '', false);
    }

    /**
     * pFooter
     *
     * @param number $x
     * @param number $y
     */
    private function pFooter($x, $y)
    {
        $w = $this->wPrint;
        if ($this->tpEvento == '110110') {
            $texto = "Este documento � uma representa��o gr�fica da CC-e e foi impresso apenas para sua informa��o e n�o possui validade fiscal.\n A CC-e deve ser recebida e mantida em arquivo eletr�nico XML e pode ser consultada atrav�s dos Portais das SEFAZ.";
        } elseif ($this->tpEvento == '110111') {
            $texto = "Este documento � uma representa��o gr�fica do evento de NFe e foi impresso apenas para sua informa��o e n�o possui validade fiscal.\n O Evento deve ser recebido e mantido em arquivo eletr�nico XML e pode ser consultada atrav�s dos Portais das SEFAZ.";
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 20, $texto, $aFont, 'T', 'C', 0, '', false);

        $y = $this->hPrint - 4;
        $texto = "Impresso em  " . date('d/m/Y   H:i:s');
        $w = $this->wPrint - 4;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'L', 0, '');

        $texto = "Daevento ver. " . $this->version . "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) � www.nfephp.org";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'R', 0, 'http://www.nfephp.org');
    }

    /**
     * printDocument
     *
     * @param  string $nome
     * @param  string $destino
     * @param  string $printer
     * @return mixed
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDaEventoNFe($nome, $destino, $printer);
    }

    /**
     * printDaEventoNFe
     *
     * @param  string $nome
     * @param  string $destino
     * @param  string $printer
     * @return mixed
     */
    public function printDaEventoNFe($nome = '', $destino = 'I', $printer = '')
    {
        if ($this->pdf == null) {
            $this->montaDaEventoNFe();
        }
        return $this->pdf->Output($nome, $destino);
    }
}
