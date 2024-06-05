<?php

class Eazypay{

    public $merchant_id;
    public $encryption_key;
    public $sub_merchant_id;
    public $reference_no;
    public $paymode;
    public $return_url;

    const DEFAULT_BASE_URL = 'https://eazypayuat.icicibank.com/EazyPG?';

    public function __construct(){
        $this->merchant_id              =    '131475';
        $this->encryption_key           =    '1311141514701518';
        $this->sub_merchant_id          =    '45';
        $this->merchant_reference_no    =    '';
        $this->paymode                  =    '9';
        $this->return_url               =    'https://mahendra.org/eazypay/TxnResponse.php'; 
    }

    public function getDefaultUrl($amount, $reference_no, $optionalField=null){
        $paymentUrl = $this->generatePaymentUrl($reference_no.'|'.$this->sub_merchant_id.'|'.$amount, $optionalField, $amount, $reference_no);
        return $paymentUrl;
    }

    public function getPaymentUrl($amount, $reference_no, $optionalField=null){
        $mandatoryField   =    $this->getMandatoryField($amount, $reference_no);
        $optionalField    =    $this->getOptionalField($optionalField);
        $amount           =    $this->getAmount($amount);
        $reference_no     =    $this->getReferenceNo($reference_no);

        $paymentUrl = $this->generatePaymentUrl($mandatoryField, $optionalField, $amount, $reference_no);
        return $paymentUrl;
        // return redirect()->to($paymentUrl);
    }

    protected function generatePaymentUrl($mandatoryField, $optionalField, $amount, $reference_no){
        $encryptedUrl = self::DEFAULT_BASE_URL."merchantid=".$this->merchant_id."&mandatory fields=".$mandatoryField."&optional fields=".$optionalField."&returnurl=".$this->getReturnUrl()."&ReferenceNo=".$reference_no."&submerchantid=".$this->getSubMerchantId()."&transaction amount=".$amount."&paymode=".$this->getPaymode();

        return $encryptedUrl;
    }

    protected function getMandatoryField($amount, $reference_no){
        return $this->getEncryptValue($reference_no.'|'.$this->sub_merchant_id.'|'.$amount);
    }

    // optional field must be seperated with | eg. (20|20|20|20)
    protected function getOptionalField($optionalField=null){
        if (!is_null($optionalField)) {
            return $this->getEncryptValue($optionalField);
        }
        return null;
    }

    protected function getAmount($amount){
        return $this->getEncryptValue($amount);
    }

    protected function getReturnUrl(){
        return $this->getEncryptValue($this->return_url);
    }

    protected function getReferenceNo($reference_no){
        return $this->getEncryptValue($reference_no);
    }

    protected function getSubMerchantId(){
        return $this->getEncryptValue($this->sub_merchant_id);
    }

    protected function getPaymode(){
        return $this->getEncryptValue($this->paymode);
    }

    // use @ to avoid php warning php 

    protected function getEncryptValue($str){
        /*
        $block = mcrypt_get_block_size("rijndael_128", "ecb");;
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->encryption_key, $str, MCRYPT_MODE_ECB));
        */

        // Generate an initialization vector
        // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 128 encryption in ecb mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($str, 'aes-128-ecb', $this->encryption_key, OPENSSL_RAW_DATA);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted);

    }
    
}


?>