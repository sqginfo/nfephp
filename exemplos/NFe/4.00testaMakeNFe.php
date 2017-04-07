<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\MakeNFe;
use NFePHP\NFe\ToolsNFe;

$nfe = new MakeNFe();

$nfeTools = new ToolsNFe('../../config/config.json');

//Dados da NFe - infNFe
$cUF = '52'; //codigo numerico do estado
$cNF = '00000010'; //numero aleat�rio da NF
$natOp = 'Venda de Produto'; //natureza da opera��o
$indPag = '1'; //0=Pagamento � vista; 1=Pagamento a prazo; 2=Outros
$mod = '55'; //modelo da NFe 55 ou 65 essa �ltima NFCe
$serie = '1'; //serie da NFe
$nNF = '10'; // numero da NFe
$dhEmi = date("Y-m-d\TH:i:sP");//Formato: "AAAA-MM-DDThh:mm:ssTZD" (UTC - Universal Coordinated Time).
$dhSaiEnt = date("Y-m-d\TH:i:sP");//N�o informar este campo para a NFC-e.
$tpNF = '1';
$idDest = '1'; //1=Opera��o interna; 2=Opera��o interestadual; 3=Opera��o com exterior.
$cMunFG = '5200258';
$tpImp = '1'; //0=Sem gera��o de DANFE; 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem;
              //3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletr�nica
              //(o envio de mensagem eletr�nica pode ser feita de forma simult�nea com a impress�o do DANFE;
              //usar o tpImp=5 quando esta for a �nica forma de disponibiliza��o do DANFE).
$tpEmis = '1'; //1=Emiss�o normal (n�o em conting�ncia);
               //2=Conting�ncia FS-IA, com impress�o do DANFE em formul�rio de seguran�a;
               //3=Conting�ncia SCAN (Sistema de Conting�ncia do Ambiente Nacional);
               //4=Conting�ncia DPEC (Declara��o Pr�via da Emiss�o em Conting�ncia);
               //5=Conting�ncia FS-DA, com impress�o do DANFE em formul�rio de seguran�a;
               //6=Conting�ncia SVC-AN (SEFAZ Virtual de Conting�ncia do AN);
               //7=Conting�ncia SVC-RS (SEFAZ Virtual de Conting�ncia do RS);
               //9=Conting�ncia off-line da NFC-e (as demais op��es de conting�ncia s�o v�lidas tamb�m para a NFC-e);
               //Nota: Para a NFC-e somente est�o dispon�veis e s�o v�lidas as op��es de conting�ncia 5 e 9.
$tpAmb = '2'; //1=Produ��o; 2=Homologa��o
$finNFe = '1'; //1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolu��o/Retorno.
$indFinal = '0'; //0=Normal; 1=Consumidor final;
$indPres = '9'; //0=N�o se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
               //1=Opera��o presencial;
               //2=Opera��o n�o presencial, pela Internet;
               //3=Opera��o n�o presencial, Teleatendimento;
               //4=NFC-e em opera��o com entrega a domic�lio;
               //9=Opera��o n�o presencial, outros.
$procEmi = '0'; //0=Emiss�o de NF-e com aplicativo do contribuinte;
                //1=Emiss�o de NF-e avulsa pelo Fisco;
                //2=Emiss�o de NF-e avulsa, pelo contribuinte com seu certificado digital, atrav�s do site do Fisco;
                //3=Emiss�o NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
$verProc = '4.0.43'; //vers�o do aplicativo emissor
$dhCont = ''; //entrada em conting�ncia AAAA-MM-DDThh:mm:ssTZD
$xJust = ''; //Justificativa da entrada em conting�ncia

//Numero e vers�o da NFe (infNFe)
$ano = date('y', strtotime($dhEmi));
$mes = date('m', strtotime($dhEmi));
$cnpj = $nfeTools->aConfig['cnpj'];
$chave = $nfe->montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);
$versao = '3.10';
$resp = $nfe->taginfNFe($chave, $versao);

$cDV = substr($chave, -1); //Digito Verificador da Chave de Acesso da NF-e, o DV � calculado com a aplica��o do algoritmo m�dulo 11 (base 2,9) da Chave de Acesso.

//tag IDE
$resp = $nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);

//refNFe NFe referenciada  
//$refNFe = '12345678901234567890123456789012345678901234';
//$resp = $nfe->tagrefNFe($refNFe);

//refNF Nota Fiscal 1A referenciada
//$cUF = '35';
//$AAMM = '1312';
//$CNPJ = '12345678901234';
//$mod = '1A';
//$serie = '0';
//$nNF = '1234';
//$resp = $nfe->tagrefNF($cUF, $AAMM, $CNPJ, $mod, $serie, $nNF);

//NFPref Nota Fiscal Produtor Rural referenciada
//$cUF = '35';
//$AAMM = '1312';
//$CNPJ = '12345678901234';
//$CPF = '123456789';
//$IE = '123456';
//$mod = '1';
//$serie = '0';
//$nNF = '1234';
//$resp = $nfe->tagrefNFP($cUF, $AAMM, $CNPJ, $CPF, $IE, $mod, $serie, $nNF);

//CTeref CTe referenciada
//$refCTe = '12345678901234567890123456789012345678901234';
//$resp = $nfe->tagrefCTe($refCTe);

//ECFref ECF referenciada
//$mod = '90';
//$nECF = '12243';
//$nCOO = '111';
//$resp = $nfe->tagrefECF($mod, $nECF, $nCOO);

//Dados do emitente - (Importando dados do config.json)
$CNPJ = $nfeTools->aConfig['cnpj'];
$CPF = ''; // Utilizado para CPF na nota
$xNome = $nfeTools->aConfig['razaosocial'];
$xFant = $nfeTools->aConfig['nomefantasia'];
$IE = $nfeTools->aConfig['ie'];
$IEST = $nfeTools->aConfig['iest'];
$IM = $nfeTools->aConfig['im'];
$CNAE = $nfeTools->aConfig['cnae'];
$CRT = $nfeTools->aConfig['regime'];
$resp = $nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);

//endere�o do emitente
$xLgr = 'Av. Rio de Janeiro';
$nro = 's/n';
$xCpl = 'Qd. 38 Lt. 4,5 e 34';
$xBairro = 'Jardim Pinheiros I';
$cMun = '5200258';
$xMun = '�guas Lindas de Goi�s';
$UF = 'GO';
$CEP = '72910000';
$cPais = '1058';
$xPais = 'Brasil';
$fone = '6239324097';
$resp = $nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
        
//destinat�rio
$CNPJ = '23401454000170';
$CPF = '';
$idEstrangeiro = '';
$xNome = 'Chinnon Santos - Tecnologia e Assessoria em Softwares';
$indIEDest = '1';
$IE = '';
$ISUF = '';
$IM = '4128095';
$email = 'nfe@chinnonsantos.com';
$resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);

//Endere�o do destinat�rio
$xLgr = 'Av. Vila Alpes';
$nro = 's/n';
$xCpl = '';
$xBairro = 'Vila Alpes';
$cMun = '5208707';
$xMun = 'Goi�nia';
$UF = 'GO';
$CEP = '74310010';
$cPais = '1058';
$xPais = 'Brasil';
$fone = '6292779404';
$resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);

//Identifica��o do local de retirada (se diferente do emitente)
//$CNPJ = '12345678901234';
//$CPF = '';
//$xLgr = 'Rua Vanish';
//$nro = '000';
//$xCpl = 'Ghost';
//$xBairro = 'Assombrado';
//$cMun = '3509502';
//$xMun = 'Campinas';
//$UF = 'SP';
//$resp = $nfe->tagretirada($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

//Identifica��o do local de Entrega (se diferente do destinat�rio)
//$CNPJ = '12345678901234';
//$CPF = '';
//$xLgr = 'Viela Mixuruca';
//$nro = '2';
//$xCpl = 'Quabrada do malandro';
//$xBairro = 'Favela Mau Olhado';
//$cMun = '3509502';
//$xMun = 'Campinas';
//$UF = 'SP';
//$resp = $nfe->tagentrega($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

//Identifica��o dos autorizados para fazer o download da NFe (somente vers�o 3.1)
/*$aAut = array('23401454000170');
foreach ($aAut as $aut) {
    if (strlen($aut) == 14) {
        $resp = $nfe->tagautXML($aut);
    } else {
        $resp = $nfe->tagautXML('', $aut);
    }
}*/

//produtos 1 (Limite da API � de 56 itens por Nota)
$aP[] = array(
        'nItem' => 1,
        'cProd' => '15',
        'cEAN' => '97899072659522',
        'xProd' => 'Chopp Pilsen - Barril 30 Lts',
        'NCM' => '22030000',
        'EXTIPI' => '',
        'CFOP' => '5101',
        'uCom' => 'Un',
        'qCom' => '4',
        'vUnCom' => '210.00',
        'vProd' => '840.00',
        'cEANTrib' => '',
        'uTrib' => 'Lt',
        'qTrib' => '120',
        'vUnTrib' => '7.00',
        'vFrete' => '',
        'vSeg' => '',
        'vDesc' => '',
        'vOutro' => '',
        'indTot' => '1',
        'xPed' => '16',
        'nItemPed' => '1',
        'nFCI' => '');
//produtos 2        
$aP[] = array(
        'nItem' => 2,
        'cProd' => '56',
        'cEAN' => '7896030801822',
        'xProd' => 'Copo Personalizado Klima 300ml',
        'NCM' => '39241000',
        'EXTIPI' => '',
        'CFOP' => '5102',
        'uCom' => 'Cx',
        'qCom' => '2',
        'vUnCom' => '180.00',
        'vProd' => '360.00',
        'cEANTrib' => '',
        'uTrib' => 'Cx',
        'qTrib' => '2',
        'vUnTrib' => '180.00',
        'vFrete' => '',
        'vSeg' => '',
        'vDesc' => '',
        'vOutro' => '',
        'indTot' => '1',
        'xPed' => '16',
        'nItemPed' => '2',
        'nFCI' => '');

foreach ($aP as $prod) {
    $nItem = $prod['nItem'];
    $cProd = $prod['cProd'];
    $cEAN = $prod['cEAN'];
    $xProd = $prod['xProd'];
    $NCM = $prod['NCM'];
    $EXTIPI = $prod['EXTIPI'];
    $CFOP = $prod['CFOP'];
    $uCom = $prod['uCom'];
    $qCom = $prod['qCom'];
    $vUnCom = $prod['vUnCom'];
    $vProd = $prod['vProd'];
    $cEANTrib = $prod['cEANTrib'];
    $uTrib = $prod['uTrib'];
    $qTrib = $prod['qTrib'];
    $vUnTrib = $prod['vUnTrib'];
    $vFrete = $prod['vFrete'];
    $vSeg = $prod['vSeg'];
    $vDesc = $prod['vDesc'];
    $vOutro = $prod['vOutro'];
    $indTot = $prod['indTot'];
    $xPed = $prod['xPed'];
    $nItemPed = $prod['nItemPed'];
    $nFCI = $prod['nFCI'];
    $resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);
}
$nfe->tagCEST(1, '2345');
$nfe->tagCEST(2, '9999');
// Informa��es adicionais na linha do Produto
/*$nItem = 1; //produtos 1
$vDesc = 'Barril 30 Litros Chopp Tipo Pilsen - Pedido N�15';
$resp = $nfe->taginfAdProd($nItem, $vDesc);*/
$nItem = 2; //produtos 2
$vDesc = 'Caixa com 1000 unidades';
$resp = $nfe->taginfAdProd($nItem, $vDesc);

//DI - Declara��o de Importa��o
/*$nItem = '1';
$nDI = '234556786';
$dDI = date('Y-m-d'); // Formato: "AAAA-MM-DD"
$xLocDesemb = 'SANTOS';
$UFDesemb = 'SP';
$dDesemb = date('Y-m-d'); // Formato: "AAAA-MM-DD"
$tpViaTransp = '1';
$vAFRMM = '1.00';
$tpIntermedio = '1';
$CNPJ = '';
$UFTerceiro = '';
$cExportador = '111';
$resp = $nfe->tagDI($nItem, $nDI, $dDI, $xLocDesemb, $UFDesemb, $dDesemb, $tpViaTransp, $vAFRMM, $tpIntermedio, $CNPJ, $UFTerceiro, $cExportador);*/

//adi - Adi��es
/*$nItem = '1';
$nDI = '234556786';
$nAdicao = '1';
$nSeqAdicC = '123';
$cFabricante = 'Klima Chopp';
$vDescDI = '5.00';
$nDraw = '9393939';
$resp = $nfe->tagadi($nItem, $nDI, $nAdicao, $nSeqAdicC, $cFabricante, $vDescDI, $nDraw);*/

//detExport
//$nItem = '2';
//$nDraw = '9393939';
//$exportInd = '1';
//$nRE = '2222';
//$chNFe = '1234567890123456789012345678901234';
//$qExport = '100';
//$resp = $nfe->tagdetExport($nItem, $nDraw, $exportInd, $nRE, $chNFe, $qExport);

//Impostos
$nItem = 1; //produtos 1
$vTotTrib = '449.90'; // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
$resp = $nfe->tagimposto($nItem, $vTotTrib);
$nItem = 2; //produtos 2
$vTotTrib = '74.34'; // 61.20 ICMS + 2.34 PIS + 10.80 CONFIS
$resp = $nfe->tagimposto($nItem, $vTotTrib);

//ICMS - Imposto sobre Circula��o de Mercadorias e Servi�os
$nItem = 1; //produtos 1
$orig = '0';
$cst = '00'; // Tributado Integralmente
$modBC = '3';
$pRedBC = '';
$vBC = '840.00'; // = $qTrib * $vUnTrib
$pICMS = '27.00'; // Al�quota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
$vICMS = '226.80'; // = $vBC * ( $pICMS / 100 )
$vICMSDeson = '';
$motDesICMS = '';
$modBCST = '';
$pMVAST = '';
$pRedBCST = '';
$vBCST = '';
$pICMSST = '';
$vICMSST = '';
$pDif = '';
$vICMSDif = '';
$vICMSOp = '';
$vBCSTRet = '';
$vICMSSTRet = '';
$resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

$nItem = 2; //produtos 2
$orig = '0';
$cst = '00';
$modBC = '3';
$pRedBC = '';
$vBC = '360.00'; // = $qTrib * $vUnTrib
$pICMS = '17.00'; // Al�quota Interna do Estado de GO 
$vICMS = '61.20'; // = $vBC * ( $pICMS / 100 )
$vICMSDeson = '';
$motDesICMS = '';
$modBCST = '';
$pMVAST = '';
$pRedBCST = '';
$vBCST = ''; 
$pICMSST = '';
$vICMSST = '';
$pDif = '';
$vICMSDif = '';
$vICMSOp = '';
$vBCSTRet = '';
$vICMSSTRet = '';
$resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

//ICMS 10
$nItem = 1; //produtos 1
$orig = '0';
$cst = '10'; // Tributada e com cobran�a do ICMS por substitui��o tribut�ria
$modBC = '3';
$pRedBC = '';
$vBC = '840.00';
$pICMS = '27.00'; // Al�quota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
$vICMS = '226.80'; // = $vBC * ( $pICMS / 100 )
$vICMSDeson = '';
$motDesICMS = '';
$modBCST = '5'; // Calculo Por Pauta (valor)
$pMVAST = '';
$pRedBCST = '';
$vBCST = '1030.80'; // Pauta do Chope Claro 1000ml em GO R$ 8,59 x 60 Litros
$pICMSST = '27.00'; // GO para GO
$vICMSST = '51.50'; // = (Valor da Pauta * Al�quota ICMS ST) - Valor ICMS Pr�prio
$pDif = '';
$vICMSDif = '';
$vICMSOp = '';
$vBCSTRet = '';
$vICMSSTRet = '';
$resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

$vST = $vICMSST; // Total de ICMS ST

//ICMSPart - ICMS em Opera��es Interestaduais - CST 10
//$resp = $nfe->tagICMSPart($nItem, $orig, $cst, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pBCOp, $ufST);

//ICMSST - Tributa��o ICMS por Substitui��o Tribut�ria (ST) - CST 40, 41, 50 e 51
//$resp = $nfe->tagICMSST($nItem, $orig, $cst, $vBCSTRet, $vICMSSTRet, $vBCSTDest, $vICMSSTDest);

//ICMSSN - Tributa��o ICMS pelo Simples Nacional - CST 30
//$resp = $nfe->tagICMSSN($nItem, $orig, $csosn, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $pCredSN, $vCredICMSSN, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $vBCSTRet, $vICMSSTRet);

//IPI - Imposto sobre Produto Industrializado
$nItem = 1; //produtos 1
$cst = '50'; // 50 - Sa�da Tributada (C�digo da Situa��o Tribut�ria)
$clEnq = '';
$cnpjProd = '';
$cSelo = '';
$qSelo = '';
$cEnq = '999';
$vBC = '840.00';
$pIPI = '6.00'; //Calculo por al�quota - 6% Al�quota GO.
$qUnid = '';
$vUnid = '';
$vIPI = '50.40'; // = $vBC * ( $pIPI / 100 )
$resp = $nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);

$nItem = 2; //produtos 2
$cst = '53'; // 53 - Sa�da N�o-Tributada
$clEnq = '';
$cnpjProd = '';
$cSelo = '';
$qSelo = '';
$cEnq = '999';
$vBC = '';
$pIPI = '';
$qUnid = '';
$vUnid = '';
$vIPI = ''; // = $vBC * ( $pIPI / 100 )
$resp = $nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);

//PIS - Programa de Integra��o Social
$nItem = 1; //produtos 1
$cst = '03'; //Opera��o Tribut�vel (base de c�lculo = quantidade vendida x al�quota por unidade de produto)
$vBC = ''; 
$pPIS = '';
$vPIS = '39.36';
$qBCProd = '60.00';
$vAliqProd = '0.3280';
$resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

$nItem = 2; //produtos 2
$cst = '01'; //Opera��o Tribut�vel (base de c�lculo = (valor da opera��o * al�quota normal) / 100
$vBC = '180.00'; 
$pPIS = '0.6500';
$vPIS = '2.34';
$qBCProd = '';
$vAliqProd = '';
$resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

//PISST
//$resp = $nfe->tagPISST($nItem, $vBC, $pPIS, $qBCProd, $vAliqProd, $vPIS);

//COFINS - Contribui��o para o Financiamento da Seguridade Social
$nItem = 1; //produtos 1
$cst = '03'; //Opera��o Tribut�vel (base de c�lculo = quantidade vendida x al�quota por unidade de produto)
$vBC = '';
$pCOFINS = '';
$vCOFINS = '81.84';
$qBCProd = '60.00';
$vAliqProd = '0.682';
$resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);

$nItem = 2; //produtos 2
$cst = '01'; //Opera��o Tribut�vel (base de c�lculo = (valor da opera��o * al�quota normal) / 100
$vBC = '180.00';
$pCOFINS = '3.00';
$vCOFINS = '10.80';
$qBCProd = '';
$vAliqProd = '';
$resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);

//COFINSST
//$resp = $nfe->tagCOFINSST($nItem, $vBC, $pCOFINS, $qBCProd, $vAliqProd, $vCOFINS);

//II
//$resp = $nfe->tagII($nItem, $vBC, $vDespAdu, $vII, $vIOF);

//ICMSTot
//$resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);

//ISSQNTot
//$resp = $nfe->tagISSQNTot($vServ, $vBC, $vISS, $vPIS, $vCOFINS, $dCompet, $vDeducao, $vOutro, $vDescIncond, $vDescCond, $vISSRet, $cRegTrib);

//retTrib
//$resp = $nfe->tagretTrib($vRetPIS, $vRetCOFINS, $vRetCSLL, $vBCIRRF, $vIRRF, $vBCRetPrev, $vRetPrev);

//Inicializa��o de v�riaveis n�o declaradas...
$vII = isset($vII) ? $vII : 0;
$vIPI = isset($vIPI) ? $vIPI : 0;
$vIOF = isset($vIOF) ? $vIOF : 0;
$vPIS = isset($vPIS) ? $vPIS : 0;
$vCOFINS = isset($vCOFINS) ? $vCOFINS : 0;
$vICMS = isset($vICMS) ? $vICMS : 0;
$vBCST = isset($vBCST) ? $vBCST : 0;
$vST = isset($vST) ? $vST : 0;
$vISS = isset($vISS) ? $vISS : 0;

//total
$vBC = '1200.00';
$vICMS = '288.00';
$vICMSDeson = '0.00';
$vBCST = '1030.80';
$vST = '51.50';
$vProd = '1200.00';
$vFrete = '0.00';
$vSeg = '0.00';
$vDesc = '0.00';
$vII = '0.00';
$vIPI = '50.40';
$vPIS = '41.70';
$vCOFINS = '92.64';
$vOutro = '0.00';
$vNF = number_format($vProd-$vDesc-$vICMSDeson+$vST+$vFrete+$vSeg+$vOutro+$vII+$vIPI, 2, '.', '');
$vTotTrib = number_format($vICMS+$vST+$vII+$vIPI+$vPIS+$vCOFINS+$vIOF+$vISS, 2, '.', '');
$resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);

//frete
$modFrete = '0'; //0=Por conta do emitente; 1=Por conta do destinat�rio/remetente; 2=Por conta de terceiros; 9=Sem Frete;
$resp = $nfe->tagtransp($modFrete);

//transportadora
//$CNPJ = '';
//$CPF = '12345678901';
//$xNome = 'Ze da Carroca';
//$IE = '';
//$xEnder = 'Beco Escuro';
//$xMun = 'Campinas';
//$UF = 'SP';
//$resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);

//valores retidos para transporte
//$vServ = '258,69'; //Valor do Servi�o
//$vBCRet = '258,69'; //BC da Reten��o do ICMS
//$pICMSRet = '10,00'; //Al�quota da Reten��o
//$vICMSRet = '25,87'; //Valor do ICMS Retido
//$CFOP = '5352';
//$cMunFG = '3509502'; //C�digo do munic�pio de ocorr�ncia do fato gerador do ICMS do transporte
//$resp = $nfe->tagretTransp($vServ, $vBCRet, $pICMSRet, $vICMSRet, $CFOP, $cMunFG);

//dados dos veiculos de transporte
//$placa = 'AAA1212';
//$UF = 'SP';
//$RNTC = '12345678';
//$resp = $nfe->tagveicTransp($placa, $UF, $RNTC);

//dados dos reboques
//$aReboque = array(
//    array('ZZQ9999', 'SP', '', '', ''),
//    array('QZQ2323', 'SP', '', '', '')
//);
//foreach ($aReboque as $reb) {
//    $placa = $reb[0];
//    $UF = $reb[1];
//    $RNTC = $reb[2];
//    $vagao = $reb[3];
//    $balsa = $reb[4];
//    //$resp = $nfe->tagreboque($placa, $UF, $RNTC, $vagao, $balsa);
//}

//Dados dos Volumes Transportados
$aVol = array(
    array('4','Barris','','','120.000','120.000',''),
    array('2','Volume','','','10.000','10.000','')
);
foreach ($aVol as $vol) {
    $qVol = $vol[0]; //Quantidade de volumes transportados
    $esp = $vol[1]; //Esp�cie dos volumes transportados
    $marca = $vol[2]; //Marca dos volumes transportados
    $nVol = $vol[3]; //Numera��o dos volume
    $pesoL = intval($vol[4]); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
    $pesoB = intval($vol[5]); //...se colocar Float n�o vai passar na express�o regular do Schema. =\
    $aLacres = $vol[6];
    $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);
}

//dados da fatura
$nFat = '000035342';
$vOrig = '1200.00';
$vDesc = '';
$vLiq = '1200.00';
$resp = $nfe->tagfat($nFat, $vOrig, $vDesc, $vLiq);

//dados das duplicatas (Pagamentos)
$aDup = array(
    array('35342-1','2016-06-20','300.00'),
    array('35342-2','2016-07-20','300.00'),
    array('35342-3','2016-08-20','300.00'),
    array('35342-4','2016-09-20','300.00')
);
foreach ($aDup as $dup) {
    $nDup = $dup[0]; //C�digo da Duplicata
    $dVenc = $dup[1]; //Vencimento
    $vDup = $dup[2]; // Valor
    $resp = $nfe->tagdup($nDup, $dVenc, $vDup);
}


//*************************************************************
//Grupo obrigat�rio para a NFC-e. N�o informar para a NF-e.
//$tPag = '03'; //01=Dinheiro 02=Cheque 03=Cart�o de Cr�dito 04=Cart�o de D�bito 05=Cr�dito Loja 10=Vale Alimenta��o 11=Vale Refei��o 12=Vale Presente 13=Vale Combust�vel 99=Outros
//$vPag = '1452,33';
//$resp = $nfe->tagpag($tPag, $vPag);

//se a opera��o for com cart�o de cr�dito essa informa��o � obrigat�ria
//$CNPJ = '31551765000143'; //CNPJ da operadora de cart�o
//$tBand = '01'; //01=Visa 02=Mastercard 03=American Express 04=Sorocred 99=Outros
//$cAut = 'AB254FC79001'; //n�mero da autoriza��o da tranza��o
//$resp = $nfe->tagcard($CNPJ, $tBand, $cAut);
//**************************************************************

// Calculo de carga tribut�ria similar ao IBPT - Lei 12.741/12
$federal = number_format($vII+$vIPI+$vIOF+$vPIS+$vCOFINS, 2, ',', '.');
$estadual = number_format($vICMS+$vST, 2, ',', '.');
$municipal = number_format($vISS, 2, ',', '.');
$totalT = number_format($federal+$estadual+$municipal, 2, ',', '.');
$textoIBPT = "Valor Aprox. Tributos R$ {$totalT} - {$federal} Federal, {$estadual} Estadual e {$municipal} Municipal.";

//Informa��es Adicionais
//$infAdFisco = "SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
$infAdFisco = "";
$infCpl = "Pedido N�16 - {$textoIBPT} ";
$resp = $nfe->taginfAdic($infAdFisco, $infCpl);

//observa��es emitente
//$aObsC = array(
//    array('email','roberto@x.com.br'),
//    array('email','rodrigo@y.com.br'),
//    array('email','rogerio@w.com.br'));
//foreach ($aObsC as $obs) {
//    $xCampo = $obs[0];
//    $xTexto = $obs[1];
//    $resp = $nfe->tagobsCont($xCampo, $xTexto);
//}

//observa��es fisco
//$aObsF = array(
//    array('email','roberto@x.com.br'),
//    array('email','rodrigo@y.com.br'),
//    array('email','rogerio@w.com.br'));
//foreach ($aObsF as $obs) {
//    $xCampo = $obs[0];
//    $xTexto = $obs[1];
//    //$resp = $nfe->tagobsFisco($xCampo, $xTexto);
//}

//Dados do processo
//0=SEFAZ; 1=Justi�a Federal; 2=Justi�a Estadual; 3=Secex/RFB; 9=Outros
//$aProcRef = array(
//    array('nProc1','0'),
//    array('nProc2','1'),
//    array('nProc3','2'),
//    array('nProc4','3'),
//    array('nProc5','9')
//);
//foreach ($aProcRef as $proc) {
//    $nProc = $proc[0];
//    $indProc = $proc[1];
//    //$resp = $nfe->tagprocRef($nProc, $indProc);
//}

//dados exporta��o
//$UFSaidaPais = 'SP';
//$xLocExporta = 'Maritimo';
//$xLocDespacho = 'Porto Santos';
//$resp = $nfe->tagexporta($UFSaidaPais, $xLocExporta, $xLocDespacho);

//dados de compras
//$xNEmp = '';
//$xPed = '12345';
//$xCont = 'A342212';
//$resp = $nfe->tagcompra($xNEmp, $xPed, $xCont);

//dados da colheita de cana
//$safra = '2014';
//$ref = '01/2014';
//$resp = $nfe->tagcana($safra, $ref);
//$aForDia = array(
//    array('1', '100', '1400', '1000', '1400'),
//    array('2', '100', '1400', '1000', '1400'),
//    array('3', '100', '1400', '1000', '1400'),
//    array('4', '100', '1400', '1000', '1400'),
//    array('5', '100', '1400', '1000', '1400'),
//    array('6', '100', '1400', '1000', '1400'),
//    array('7', '100', '1400', '1000', '1400'),
//    array('8', '100', '1400', '1000', '1400'),
//    array('9', '100', '1400', '1000', '1400'),
//    array('10', '100', '1400', '1000', '1400'),
//    array('11', '100', '1400', '1000', '1400'),
//    array('12', '100', '1400', '1000', '1400'),
//    array('13', '100', '1400', '1000', '1400'),
///    array('14', '100', '1400', '1000', '1400')
//);
//foreach ($aForDia as $forDia) {
//    $dia = $forDia[0];
//    $qtde = $forDia[1];
//    $qTotMes = $forDia[2];
//    $qTotAnt = $forDia[3];
//    $qTotGer = $forDia[4];
//    //$resp = $nfe->tagforDia($dia, $qtde, $qTotMes, $qTotAnt, $qTotGer);
//}

//monta a NFe e retorna na tela
$resp = $nfe->montaNFe();
if ($resp) {
    header('Content-type: text/xml; charset=UTF-8');
    $xml = $nfe->getXML();
    // $filename = "/var/www/nfe/homologacao/entradas/{$chave}-nfe.xml"; // Ambiente Linux
    $filename = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/entradas/{$chave}-nfe.xml"; // Ambiente Windows
    file_put_contents($filename, $xml);
    chmod($filename, 0777);
    echo $xml;
} else {
    header('Content-type: text/html; charset=UTF-8');
    foreach ($nfe->erros as $err) {
        echo 'tag: &lt;'.$err['tag'].'&gt; ---- '.$err['desc'].'<br>';
    }
}
