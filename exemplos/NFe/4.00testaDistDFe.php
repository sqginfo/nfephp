<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$ultNSU = 0; // se estiver como zero ir� retornar os dados dos ultimos 15 dias at� o limite de 50 registros
             // se for diferente de zero ir� retornar a partir desse numero os dados dos
             // �ltimos 15 dias at� o limite de 50 registros

$numNSU = 0; // se estiver como zero ir� usar o ultNSU
             // se for diferente de zero n�o importa o que est� contido em ultNSU ser� retornado apenas
             // os dados deste NSU em particular

$tpAmb = '1';// esses dados somente existir�o em ambiente de produ��o pois em ambiente de testes
             // n�o existem dados de eventos, nem de NFe emitidas para o seu CNPJ

$cnpj = ''; // deixando vazio ir� pegar o CNPJ default do config
            // se for colocado um CNPJ tenha certeza que o certificado est� autorizado a
            // baixar os dados desse CNPJ pois se n�o estiver autorizado haver� uma
            // mensagem de erro da SEFAZ

//array que ir� conter os dados de retorno da SEFAZ
$aResposta = array();

//essa rotina deve r� ser repetida a cada hora at� que o maxNSU retornado esteja contido no NSU da mensagem
//se estiver j� foram baixadas todas as referencias a NFe, CTe e outros eventos da NFe e n�o a mais nada a buscar
//outro detalhe � que n�o adianta tentar buscar dados muito antigos o sistema ir� informar que 
//nada foi encontrado, porque a SEFAZ n�o mant�m os NSU em base de dados por muito tempo, em 
//geral s�o mantidos apenas os dados dos �ltimos 15 dias.
//Os dados s�o retornados em formato ZIP dento do xml, mas no array os dados 
//j� s�o retornados descompactados para serem lidos
$xml = $nfe->sefazDistDFe('AN', $tpAmb, $cnpj, $ultNSU, $numNSU, $aResposta);

echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
