Class DateTime
=============
Esta classe auxiliar prov� tr�s fun��es b�sicas para o tratamento de datas e horas para os sistemas da SEFAZ.

� requerido PHP >= 5.3

Namespace
=============
```php
Common/DateTime;
```

M�todos
==========

tzdBR
--------
```php
string DateTime::tzdBR(string $siglaUF)
```
Esta fun��o est�tica retorna o "Time Zone Designator" de qualquer unidade da federa��o como uma string no formato "-03:00", a $siglaUF pode ser a sigla de qualquer estado brasileiro (ex. SP, MG, PR, etc..), caso n�o seja passado nenhum valor v�lido o retorno ser� uma string vazia.
O Time Zone Default do <b>ambiente PHP ser� modificado tamb�m</b>, caso seja passado um valor v�lido.
Par�metros
--------
<b>siglaUF</b>

string com a sigla da unidade da federa��o em letras maiusculas, caso seja passado uma sigla invalida ou vazia ser� retornado uma string vazia 

convertSefazTimeToTimestamp
--------
```php
int DateTime::convertSefazTimeToTimestamp(string $dataHora)
```
Esta fun��o est�tica retorna um "timestamp" para uma data no formato usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ".
Par�metros
--------
<b>dataHora</b>

string com o par�metro data extra�do do xml da SEFAZ.

convertTimestampToSefazTime
--------
```php
string DateTime::convertTimestampToSefazTime(int $timestamp)
```
Esta fun��o est�tica retorna um string no formato de data usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ", a partir de um "timestamp".
Par�metros
--------
<b>timestamp</b>

integer timestamp UNIX para ser convertido ao padr�o usado pela SEFAZ. Caso nenhum parametro seja passado ser� retornado a data e hora atual no dito padr�o, , incluindo o Time Zone default, portanto antes de usar essa fun��o � recomend�vel que a primeira fun��o "tzdBR" seja usada ou que o Time Zone Default esteja configurado corretamente.
