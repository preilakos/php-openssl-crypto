<?php
class crypto
{
    /* PHP Titkosítás OpenSSL által generált publikus (encrypt) és privát (decrypt) kulcshoz. */

    static function CheckType($data) {
        $allowedtype = array("string", "integer"); /* elfogadható típusok */
        /* típusellenőrzés */
        for ($i=0; $i <= sizeof($allowedtype); $i++) {
            if(gettype($data) == $allowedtype[$i]) {
                return true;
            } else {
                throw new Exception("Nem kezelhető a megadott adat, mivel a típusa nem elfogadott.");
            }
        }
    }

    static function Encrypt($data, $returnplain) {
        if(self::CheckType($data)) {
            $public_key_path = "keys/crypto_p.key"; /* A Publikus kulcs elérési útja */

            if(file_exists($public_key_path)) {
                $public_key = file_get_contents($public_key_path);
            } else {
                throw new Exception("Nem található publikus kulcs");
            }

            /* Encrypt */
            if(openssl_public_encrypt($data,$crypted, $public_key)) {
                $data_e = base64_encode($crypted);
                return $data_e;
            } elseif($returnplain == true) {
                return $data; /* ha returnplain igaz, akkor hiba esetén az alapértéket adjuk vissza */
            } else {
                throw new Exception("Nem kezelhető a megadott adat, mivel a titkosítás hibába ütközött. Valószínűleg helytelen a publikus kulcs.");
            }
        }
    }

    static function Decrypt($data, $returnplain) {
        if(self::CheckType($data)) {

            $private_key_path = "keys/crypto.key"; /* A Privát kulcs elérési útja */

            if(file_exists($private_key_path)) {
                $private_key = file_get_contents($private_key_path);
            } else {
                throw new Exception("Nem található privát kulcs");
            }

            /* Decrypt */
            if(openssl_private_decrypt(base64_decode($data), $decrypted, $private_key)) {
                $data_e = $decrypted;
                return $data_e;
            } elseif($returnplain == true) {
                return $data; /* ha returnplain igaz, akkor hiba esetén az alapértéket adjuk vissza */
            } else {
                throw new Exception("Nem kezelhető a megadott adat, mivel a titkosítás hibába ütközött. Valószínűleg helytelen a privát kulcs.");
            }
        }
    }

    /* Hibakezelés */
    static function WriteException($operation, $exception) {
        echo "<br>Hiba történt a művelet (".$operation.") végrehajtása közben: ".$exception."<br>";
    }
}