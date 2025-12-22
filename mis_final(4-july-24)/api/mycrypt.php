<?php 
require_once "mycryptconfig.php";

	class EncryptionUtils
	{
		private $first_key;

		function __construct($encKey = 0)
		{
		    $dk = $encKey;
		    if($encKey === 0){
		        $dk = CRYPT_KEY;
		    }
            $this->first_key = hash('sha256', $dk, true);
		}

		function encrypt($data) {
			$iv = openssl_random_pseudo_bytes(16);
			$hashedKey = $this->first_key;
			$encrypted = openssl_encrypt($data, 'AES-128-CBC', $hashedKey, OPENSSL_RAW_DATA, $iv);
			$combined = $iv . $encrypted;
			$encoded = base64_encode($combined);
			return $encoded;     
        }

		function decrypt($code) {
			$decodedMessage = base64_decode($code);
			$iv = substr($decodedMessage, 0, 16);
			$encryptedData = substr($decodedMessage, 16);
			$hashedKey = $this->first_key;
			$decrypted = openssl_decrypt($encryptedData, 'AES-128-CBC', $hashedKey, OPENSSL_RAW_DATA, $iv);
			return $decrypted;
		}

        function isJson($string) {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        }
	}

	
	$mcrypt = new EncryptionUtils();
    #Encrypt
    $encrypted = $mcrypt->encrypt("Text to encrypt");
    #Decrypt
    $decrypted = $mcrypt->decrypt($encrypted);
