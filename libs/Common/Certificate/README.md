Neste diret�rio se encontram um grupo de classes e arquivos para o tratamento e uso de Certificados Digitais modelo A1 (pkcs12) usados pela API para as assinaturas digitais e a comunica��o SOAP (ssl) com as unidades autorizadoras, seja para NFe, NFSe, CTe, MDFe, ou CLe.
� importante relembrar que o PHP usa apenas certificados modelo A1 e n�o existe nenhuma forma est�vel ou confi�vel de utilizar os certificados modelo A3 (token ou smart card), portanto: 
N�O � POSS�VEL USAR CERTIFICADOS MODELO A3 COM O PHP
=========
<b>Para o uso destas classe � impressind�vel que esteja instalado e ativo o m�dulo do OpenSSL do PHP.</b>

Namespace
=========
```php
Common/Certificate;
```

Class Pkcs12
==========
Esta � a classe principal para o uso dos certificados digitais, e prov� os m�todos principais para converter os certificados pfx em PEM (para uso do PHP), realizar as assinaturas digitais dos xml e fazer as verifica��es e valida��es dos certificados e das assinaturas digitais. 

M�todos P�blicos
==========

\__construct()
--------
M�todo contrutor da classe - ao instanciar a classe a mesma executa algumas configura��es b�sicas.
 
```php
use Common\Certificate\Pkcs12;

$oCertificate = new Pkcs12(string $pathCerts,string $cnpj[,string $pubKey[,string $priKey[,string $certKey,boolean $ignoreValidCert]]])
```                                               
Par�metros
--------
<b>pathCerts</b>

string com o caminho completo para o diretorio que contem os certificados digitais. Nesse diretorio ser�o colocados os arquivos .pfx, e .pem
Caso n�o seja passado um caminho v�lido para esse parametro um Exception ser� retornado. Caso seja passado uma string vazia o diretorio "certs" da API ser� utilizado como padr�o.
Esse path deve ser passado como path real. NOTA: Evite passar caminhos relativos. 

<b>cnpj</b>

String com o N�mero do CNPJ do propriet�rio do certificado digital, apenas 14 digitos num�ricos, sem s�mbolos ou espa�os.
Ser� retornado um Exception caso o CNPJ n�o tenha exatos 14 digitos num�ricos. 

<b>pubKey</b>

Opcional string com o conte�do da chave publica em formato PEM. Esse conte�do, ou seja a chave propriamente dita, pode ser armazenada em banco de dados e passada como par�metro na API, n�o havendo necessidade de ficar armazenada como um arquivo.  
 
<b>priKey</b>

Opcional string com o conte�do da chave privada em formato PEM. Esse conte�do, ou seja a chave propriamente dita, pode ser armazenada em banco de dados e passada como par�metro na API, n�o havendo necessidade de ficar armazenada como um arquivo.

<b>certKey</b>

Opcional string com o conte�do do certificado em formato PEM, que � composto das chaves publica e privada, al�m da cadeia de certifica��o (se houver). Caso n�o seja passada como par�metro esse certificado ser� recriado se as chaves publica e privada o forem.

<b>ignoreValidCert</b>

Opcional boolean DEFAULT false, manda a classe ignorar as valida��es do certificado, tanto do seu propriet�rio como sua data de validade.


loadPfxFile
--------
Este m�todo permite o carregamento de um novo arquivo PFX (certificado em formato de transporte) na API atrav�s de um arquivo.                                                       
```php 
boolean $oCertificate->loadPfxFile(string $pathPfx,string $password,[boolean $createFiles,[boolean $ignoreValidity,[boolean $ignoreOwner]]])
```
Par�metros
--------
<b>pathPfx</b>

string esse � o path para o arquivo pfx

<b>password</b>

string senha de decripta��o do arquivo pfx

<b>createFiles</b>

Opcional DEFAULT true - indica se devem ser criados os arquivos PEM na pasta indicada na constru��o da classe

<b>ignoreValidity</b>

Opcional boolean DEFAULT false, manda a classe ignorar a data de validade do certificado.

<b>ignoreOwner</b>

Opcional boolean DEFAULT false, manda a classe ignorar o cnpj do propriet�rio do certificado.


loadPfx
--------
Este m�todo permite carregar um certificado no formato pfx atrav�s de seu conte�do e n�o atrav�s de um path.    
```php
boolean $oCertificate->loadPfx($pfxContent,$password,[boolean $createFiles,[boolean $ignoreValidity,[boolean $ignoreOwner]]])
```
Par�metros
--------

<b>pfxContent</b>

string conte�do do arquivo pfx, esse conteudo pode estar armazenado por exemplo em banco de dados 

<b>password</b>

string senha de decripta��o do arquivo pfx

<b>createFiles</b>

Opcional DEFAULT true - indica se devem ser criados os arquivos PEM na pasta indicada na constru��o da classe

<b>ignoreValidity</b>

Opcional boolean DEFAULT false, manda a classe ignorar a data de validade do certificado.

<b>ignoreOwner</b>

Opcional boolean DEFAULT false, manda a classe ignorar o cnpj do propriet�rio do certificado.


aadChain
--------
Este m�todo permite a adi��o da cadeia de certifica��o ao certificado digital. Alguns estados tem feito altera��es em seus sistemas de forma a exigir que a cadeia completa de certifica��o at� a RAIZ brasileira esteja contida dentro do certificado usado na comunica��o SOAP.
```php
void $oCertificate->aadChain(array $aCerts)
```

Par�metros
--------
<b>aCerts</b>

array com os paths ou os conte�dos dos certificados da cadeia de certifica��o. 


signXML
--------
```php
string $oCertificate->signXML(string $docxml, string $tagid)
```
Esta fun��o executa a assinatura digital de um documento xml de acordo com os par�metros estabelecidos pelas SEFAZ para NFe, CTe, MDFe, e CLe.
Esta fun��o ir� retornar o xml assinado na forma de uma string ou um Exception caso n�o esteja previamente estabelecido o certificado digital, se houver falha no carregamento do chave privada ou se algums dos parametro n�o for passado.
Par�metros
--------
<b>docxml</b>

string com o path para o xml ou o conte�do do pr�prio xml

<b>tagid</b> 

string com o nome da TAG a ser assinada. 

verifySignature
--------
Este m�todo serve para verificar se a assinatura digital contida no documento passado confere.
```php
boolean $oCertificate->verifySignature(string $docxml, string $tagid)
```
Par�metros
--------
<b>docxml</b>

string com o path para o xml ou o conte�do do pr�prio xml

<b>tagid</b> 

string com o nome da TAG assinada.


Class Asn
==========

Esta � uma classe tamb�m relevante, mas � apenas um complemento da classe anterior e � usada unicamente para extrair o numero do CNPJ do propriet�rio do certificado.
Esta classe � usada para verificar se o usu�io � realmente o propriet�rio do certificado pois caso n�o seja a assinatura e a conex�o com a SEFAZ ser� rejeitada. 

M�todos P�blicos
=========

getCNPJCert
--------
Este m�todo extrai do interior do certificado digital A1, a identifica��o do CNPJ do propriet�rio.
```php
use Common\Certificate\Asn;

string Asn::getCNPJCert($certPem)
```
Par�metros
--------
<b>certPem</b>

string com o conte�do do certificado digital em formato PEM (normalmente a chave p�blica)

<i>NOTA: As demais classes e arquivos deste diret�rio s�o complementos auxiliares destas duas classes principais e n�o possuem metodos publicos a serem utilizados.</i>



