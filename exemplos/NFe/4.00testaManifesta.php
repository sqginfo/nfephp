<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

//210200 - Confirma��o da Opera��o
//210210 - Ci�ncia da Opera��o
//210220 - Desconhecimento da Opera��o
//210240 - Opera��o n�o Realizada ===> � obritatoria uma justificativa para esse caso
$chave = '35150158716523000119550010000000071000000076';
$tpAmb = '2';
$xJust = '';
$tpEvento = '210210'; //ciencia da opera��o
$aResposta = array();
$xml = $nfe->sefazManifesta($chave, $tpAmb, $xJust = '', $tpEvento = '', $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
