# NFePHP

[![Build Status](https://travis-ci.org/nfephp-org/nfephp.svg)](https://travis-ci.org/nfephp-org/nfephp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/quality-score.png)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/)
[![Code Coverage](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/coverage.png)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/)
[![License](https://poser.pugx.org/nfephp-org/nfephp/license.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Latest Stable Version](https://poser.pugx.org/nfephp-org/nfephp/v/stable.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Latest Unstable Version](https://poser.pugx.org/nfephp-org/nfephp/v/unstable.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Total Downloads](https://poser.pugx.org/nfephp-org/nfephp/downloads)](https://packagist.org/packages/nfephp-org/nfephp)

NFePHP � uma API para gerenciamento das comunica��es entre o emitente de NFe e os servi�os dos SEFAZ estaduais. Inteiramente constru�do em PHP para rodar sob qualquer sistema operacional.
Para come�ar veja [nossas p�ginas Wiki](https://github.com/nfephp-org/nfephp/wiki).

N�o deixe de se cadastrar no [grupo de discuss�o do NFePHP](http://groups.google.com/group/nfephp)!

## PULL REQUESTS
**Srs. neste reposit�rio somente ser�o aceitos "PULL REQUESTS" relativos a BUGS e corre��es derivadas de mudan�as promovidas pelas SEFAZ.**

N�o mais ser�o aceitas altera��es, melhorias no c�digo ou inclus�es de novos recursos ou de novos servi�os, todas essas melhorias dever�o ser encaminhadas para o novo reposit�rio SPED-XXX.

>Em breve (at� meados de 2017), este reposit�rio deixar� de receber qualquer contribui��o e ser� descontinuado, em favor dos novos reposit�rios !!!
>Para manter a integridade da API nessa nova vers�o (4.1.x-dev), est�o sendo mantidos os "namespaces", as chamadas de m�todos e seus parametros, que n�o dever�o ser alterados a n�o ser por motivo de "for�a maior", como mudan�as da SEFAZ que forcem essa situa��o.
>No uso da nova vers�o, aten��o deve ser dedicada a nomenclatura de classes, que foi simplificada, e nos recursos como impress�o que foram deslocados para outro reposit�rio.  


## REESTRUTURA��O DE REPOSIT�RIOS

**As estruturas de CTe, MDFe e outras foram removidas deste reposit�rio e levadas a seus novos reposit�rios veja:**

[SPED NFe](https://github.com/nfephp-org/sped-nfe) Novo reposit�rio da classes de NFe (em fase de testes)

[SPED CTe](https://github.com/nfephp-org/sped-cte) Novo reposit�rio das classes de CTe (em desenvolvimento)

[SPED MDFe](https://github.com/nfephp-org/sped-mdfe) Novo reposit�rio das classes de MDFe (em desenvolvimento)

[SPED NFSe](https://github.com/nfephp-org/sped-nfse) Novo reposit�rio das classes de NFSe (em desenvolvimento)

Os demais componentes tamb�m ter�o reposit�rios novos, mas por ora ainda permanecem neste.

[SPED COMMON](https://github.com/nfephp-org/sped-common) Novo reposit�rio das classes comuns usadas por todos ou v�rios projetos. 

[SPED DA](https://github.com/nfephp-org/sped-da) Novo reposit�rio das classes que geram a impress�o dos documentos. 

[POSPRINT](https://github.com/nfephp-org/posprint) Framework para impress�o com impressoras t�rmicas POS (em desenvolvimento)

[SPED GNRE](https://github.com/nfephp-org/sped-gnre) Reposit�rio da classes de GNRE (vers�o est�vel)

Al�m desses outros reposit�rios est�o em constru��o ou j� disp�em de bibliotecas

[SPED EFD](https://github.com/nfephp-org/sped-efinanceira) Reposit�rio da classes de Sped EFD fiscal  (em desenvolvimento)

[SPED RESTFUL](https://github.com/nfephp-org/sped-restful) Aplicativo RestFul para gera��o de documentos Sped (em desenvolvimento)

[SPED CONSOLE](https://github.com/nfephp-org/sped-console) Conjunto de recursos em linha de comando (em desenvolvimento)

[SPED DOCS](https://github.com/nfephp-org/sped-docs) Conjunto da documenta��o dos pacotes NFePHP (ultrapassado, incompleto e parcial)

[SPED EMISSOR](https://github.com/nfephp-org/sped-emissor) Aplicativo "front-end" para emiss�o de documentos (n�o iniciado)

[SPED eSOCIAL](https://github.com/nfephp-org/sped-esocial) Reposit�rio das classes para eSocial (apenas documenta��o)

[SPED SERIALIZER](https://github.com/nfephp-org/sped-esocial) Repositorio de classes para serializa��o de XML (conceito)

Outros projetos relacionados, mas com finalidade especifica:

[SPED eFINANCEIRA](https://github.com/nfephp-org/sped-efinanceira) Reposit�rio da classes de eFinanceira (vers�o est�vel)

[SPED eSFINGE](https://github.com/nfephp-org/sped-esfinge) Framework para integra��o com o sistema eSfinge do TCE/SC (vers�o est�vel)

# CONTRIBUINDO

Este � um projeto totalmente *OpenSource*, para usa-lo e modifica-lo voc� n�o paga absolutamente nada. Por�m para continuarmos a mante-lo � necess�rio qua alguma contribui��o seja feita, seja auxiliando na codifica��o, na documenta��o ou na realiza��o de testes e identifica��o de falhas e BUGs.

Mas tamb�m, caso voc� ache que qualquer informa��o obtida aqui, lhe foi �til e que isso vale de algum dinheiro e est� disposto a doar algo, sinta-se livre para enviar qualquer quantia atrav�s de :

<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=linux%2erlm%40gmail%2ecom&lc=BR&item_name=NFePHP%20OpenSource%20API&item_number=nfephp&currency_code=BRL&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest">
<img alt="Doar com Paypal" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif"/></a>
[![](https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/209x48-doar-assina.gif)](https://pag.ae/bkXPq4) 


## IMPORTANTE:

A partir desta vers�o o numero das vers�es seguir� uma sequencia pr�pria da API e n�o mais ir� se referir as vers�es de layout da NFe, CTe, etc.

Nesta vers�o (4.0.x) apenas a NFe � funcional, para CTe, e MDFe deve ser usado a TAG 3.10-Obsoleta, pelo menos at� que outros colaboradores se disponham a auxiliar na refatora��o  

## Vers�o de Desenvolvimento

Vers�o 4.0.x-dev (observa a vers�o de layout 3.10 da SEFAZ)

## Vers�o Est�vel

Devido as constantes altera��es, dos schemas, webservices e legisla��es, promovidas pelo Congresso Nacional, pelas SEFAZ estaduais e pelos provedores dos webservices, a vers�o em MASTER e a �ltima TAG s�o as mais est�veis e funcionais.

## Instala��o com Composer

Pelo terminal v� at� a raiz de seu projeto e l� execute :

```
composer require nfephp-org/nfephp
``` 
Isso far� com que o SEU arquivo composer.json seja acrescido da depend�ncia da API.
A API ser� baixada e colocada na pasta "vendor" e o arquivo autoload.php sej� atualizado.


## Condicionantes

Para usar essa API � necess�rio conhecimento em programa��o PHP, bem como conhecer os padr�es atuais da linguagem e ter bases de legisla��o fiscal. � extremanente recomend�vel que seja estudado o conte�do dos seguintes sites.
* Documenta��o do Funcionamento do sistema de NFe [SEFAZ NFe](http://www.nfe.fazenda.gov.br/portal/principal.aspx)
* Documenta��o do Funcionamento do sistema de CTe [SEFAZ CTe](http://www.cte.fazenda.gov.br/listaSubMenu.aspx?Id=tW+YMyk/50s=)
* Documenta��o do Funcionamento do sistema de MDFe [SEFAZ MDfe](https://mdfe-portal.sefaz.rs.gov.br/)
* Composer [Documenta��o](https://getcomposer.org/doc/)  Constru��o do [composer.json](http://composer.json.jolicode.com/)
* IMPORTANTE [PHP do Jeito Certo](http://br.phptherightway.com/)
* Coding Style Guide [PSR-2](http://www.php-fig.org/psr/psr-2/)
* Autoload [PSR-4](http://www.php-fig.org/psr/psr-4/)

>NOTA: A NFSe Nota Fiscal de Servi�os Eletr�nica, n�o tem padr�o �nico, e a API tem somente alguns exemplos de montagem de um sistema para esse fim, mas nenhuma API realmente funcional para esse tipo de documentos fiscais.

## Objetivo

A API permite que um programa emissor de NFe se comunique com a SEFAZ. A API n�o foi criada para ela pr�pria emitir a NFe tendo em vista a enorme quantidade de informa��es necess�rias e as caracter�sticas e especificidades de cada emitente.

## Depend�ncias

* composer <https://getcomposer.org/>
* Apache: <http://httpd.apache.org/>
* PHP 5.4+: <http://php.net>
* Bibliotecas de terceiros
 * FPDF: Provis�riamente usada para gerar os documentos em PDF. Veja <http://www.fpdf.org/>.Dever� ser substituida pela classe ZendPdf (devido ao tendimento dos padr�es PSR e ser mais ativamente mantida e distribuida via composer.
 * zendframework/zend-mail (v.2.x) Usada para envio dos emails aos destin�tarios dos docuemntos fiscais eletr�nicos.
 * zendframework/zend-barcode (v.2.x) Usada para gerar os codigos de barras 128 presente nos documentos fiscais em PDF.
 * soundasleep/html2text (v.0.2) Usada para converter as mensagens Htlm dos emails em seu equivalente em texto puro. Usada na classe de envio dos emails.
 * endroid/qrcode (v.1.x) Usada para gerar o QRCode impresso nas NFCe
* Extens�es PHP
 * cURL: Normalmente j� vem habilitado com o PHP 5.3+. Veja <http://br2.php.net/manual/book.curl.php> e <http://curl.haxx.se/>.
 * OpenSSL: Normalmente j� vem habilitado com o PHP 5.3+. Veja <http://br2.php.net/manual/book.openssl.php> e <http://www.openssl.org/>.
 * mcrypt: Normalmente j� vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.mcrypt.php>.
 * imap: Normalmente j� vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.imap.php>
 * GD: Normalmente j� vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.image.php>
 * ZIP: Necess�rio para o download de NFe da SEFAZ. Veja <http://www.php.net/manual/en/book.zip.php>
 * Zlib: Necess�ria para descompactar NFe ap�s download. Veja <http://www.php.net/manual/en/book.zlib.php>

## Instala��o

Para mais detalhes sobre a instala��o, veja <https://github.com/nfephp-org/nfephp/wiki/Instala��o>.

## Quick start

Clone o reposit�rio com `git clone --branch=develop https://github.com/nfephp-org/nfephp.git` ou [baixe a �ltima vers�o est�vel](https://github.com/nfephp-org/nfephp/downloads).

```sh
$ composer install
$ ./vendor/bin/phpunit
```

## Versionamento

Para fins de transpar�ncia e discernimento sobre nosso ciclo de lan�amento, e procurando manter compatibilidade com vers�es anteriores, o n�mero de vers�o da NFePHP 
ser� mantida, tanto quanto poss�vel, respeitando o padr�o abaixo.

As libera��es ser�o numeradas com o seguinte formato:

`<major>.<minor>.<patch>`

E ser�o constru�das com as seguintes orienta��es:

* Quebra de compatibilidade com vers�es anteriores, avan�a o `<major>`.
* Adi��o de novas funcionalidades sem quebrar compatibilidade com vers�es anteriores, avan�a o `<minor>`.
* Corre��o de bugs e outras altera��es, avan�a `<patch>`.

Para mais informa��es, por favor visite <http://semver.org/>.

## Desenvolvimento

Para todo o desenvolvimento, corre��es de bugs, inclus�es e testes dever� ser usada branch `develop`. 
Na branch `master`estar�o os c�digos considerados como est�veis.
Novas branches poder�o surgir em fun��o das necessidades que se apresentarem, seja para manter versionamentos anteriores seja para estabelecer corre��es de bugs. Mas apenas essas duas branches estabelecidas � que ser�o permanentente mantidas. 

## Bug tracker

Encontrou um bug? Informe-nos aqui no GitHub!

<https://github.com/nfephp-org/nfephp/issues>

## Mantenedores (em revis�o)

* NFe  - `Roberto L. Machado`
* NFCe - `Roberto L. Machado`
* NFSe - `n�o definido`
* CTe  - `n�o definido`
* MDFe - `n�o definido`
* CLe  - `n�o definido`

## Pull Request

Para que seu Pull Request seja aceito ele deve estar seguindo os padr�es descritos neste documento <http://www.walkeralencar.com/PHPCodeStandards.pdf>
