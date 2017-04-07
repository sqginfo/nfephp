<?php

namespace NFePHP\Common\Files;

/**
 * Classe auxiliar para criar, listar e testar os diret�rios utilizados pela API
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Files
 * @copyright Copyright (c) 2008-2014
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Exception;

class FilesZip
{
    /**
     * unZipTmpFile
     * Descompacta strings GZIP usando arquivo tempor�rio e SO
     *
     * @param  string $datazip Dados compactados com gzip
     * @return string arquivo descompactado
     * @throws Exception
     */
    public static function unZipTmpFile($datazip = '')
    {
        if (is_file($datazip)) {
            $data = file_get_contents($datazip);
        } else {
            $data = $datazip;
        }
        $uncompressed = '';
        //cria um nome para o arquivo temporario
        do {
            $tempName = uniqid('temp_');
        } while (file_exists($tempName));
        //grava a string compactada no arquivo tempor�rio
        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                //efetua a leitura do arquivo descompactando e jogando o resultado
                //bo cache
                @readgzfile($tempName);
                //descarrega o cache na vari�vel
                $uncompressed = ob_get_clean();
            } catch (\Exception $e) {
                $ex = $e;
            }
            //remove o arquivo tempor�rio
            if (file_exists($tempName)) {
                unlink($tempName);
            }
            if (isset($ex)) {
                throw new Exception\RuntimeException(
                    $ex
                );
            }
            //retorna a string descomprimida
            return $uncompressed;
        }
    }

    /**
     * unGZip
     * Descompacta dados compactados GZIP via PHP
     *
     * @param  string $data Dados compactados com gzip em uma string
     * @return mixed
     */
    public static function unGZip($data = '')
    {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            throw new Exception\RuntimeException(
                "N�o est� no formato GZIP."
            );
        }
        $method = ord(substr($data, 2, 1));  // metodo de compress�o
        $flags  = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            throw new Exception\RuntimeException(
                "N�o s�o permitidos bits reservados."
            );
        }
        // NOTA: $mtime pode ser negativo (limita��es nos inteiros do PHP)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabe�alho
            if ($len - $headerlen - 2 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inv�lidos."
                );
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                throw new Exception\RuntimeException(
                    "Dados inv�lidos."
                );
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inv�lidos."
                );
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabe�alho
            if ($len - $headerlen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inv�lidos."
                );
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Formato de cabe�alho inv�lido."
                );
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabe�alho
            if ($len - $headerlen - 2 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inv�lidos."
                );
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                throw new Exception\RuntimeException(
                    "Checksum do cabe�alho falhou."
                );
            }
            $headerlen += 2;
        }
        // Rodap� GZIP
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompress�o
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            throw new Exception\RuntimeException(
                "BUG da implementa��o."
            );
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Por hora somente � suportado esse metodo de compress�o
                    $data = gzinflate($body, null);
                    break;
                default:
                    throw new Exception\RuntimeException(
                        "M�todo de compress�o desconhecido (n�o suportado)."
                    );
            }
        }
        // conteudo zero-byte � permitido
        // Verificar CRC32
        $crc   = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ( $lenOK ? '' : 'Verifica��o do comprimento FALHOU. ').( $crcOK ? '' : 'Checksum FALHOU.');
            throw new Exception\RuntimeException(
                $msg
            );
        }
        return $data;
    }
    
    /**
     * compacta uma string usando Gzip
     *
     * @param  string $data
     * @return string
     */
    public static function gZipString($data = '')
    {
        return gzencode($data, 9, FORCE_GZIP);
    }
    
    /**
     * descompacta uma string usando Gzip
     *
     * @param  string $data
     * @return string
     */
    public static function unGZipString($data = '')
    {
        return gzdecode($data);
    }
    
    /**
     * compacta uma string usando ZLIB
     *
     * @param  string $data
     * @return string
     */
    public static function zipString($data = '')
    {
        return gzcompress($data, 9);
    }
}
