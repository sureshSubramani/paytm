<?php

class Eazypay{

    public $merchant_id;
    public $encryption_key;
    public $sub_merchant_id;
    public $reference_no;
    public $paymode;
    public $return_url;

    //const DEFAULT_BASE_URL = 'https://eazypayuat.icicibank.com/EazyPG?'; // #For Testing 
	const DEFAULT_BASE_URL = 'https://eazypay.icicibank.com/EazyPG?'; // #For Live

    public function __construct(){

        if(isset($_REQUEST['COLLEGE_ID']) && $_REQUEST['COLLEGE_ID']!=""){
			
            $merchant_key = $merchant_id = "";
            
            // Create connection
            $conn = new mysqli("localhost", "mahendrg_mahendr", "Kvuglbmp9e4;", "mahendrg_mahendra");
            // Check connection
            if ($conn->connect_error) {
        
              echo '<h3 style="margin: 5% auto; text-align: center; color: #e80a0a">Connection failed. Unable to get merchant details.</h3>'; 
              exit;
        
            }else{
        
                $sql = "SELECT * FROM `gateway_merchent_details` WHERE college='".$_REQUEST['COLLEGE_ID']."' AND gateway='ICICI' AND status=1";
                $result = $conn->query($sql);
        
                if ($result->num_rows > 0) {
                    $val = $result->fetch_assoc();
                    if(!empty($val)){
                        $mkey = (isset($val['merchant_key']) && $val['merchant_key'])?$val['merchant_key']:"";
                        $mid  = (isset($val['merchant_id']) && $val['merchant_id'])?$val['merchant_id']:"";
                        $smid = (isset($val['sub_merchant_id']) && $val['sub_merchant_id'])?$val['sub_merchant_id']:"";
                    }
                }        
                $conn->close();        
            }
        }else{
            echo 'Invalid access or Invalid parameters..';
        }

        $this->merchant_id              =    $mid;
        $this->encryption_key           =    $mkey;
        $this->sub_merchant_id          =    $smid;
        $this->merchant_reference_no    =    '';
        $this->paymode                  =    '9';
        $this->return_url               =    'https://mahendra.org/online_payment/hostelfee/TxnResponse.php';
		
		//$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if(isset($_SESSION['MERCHANT_KEY'])):
            unset($_SESSION['MERCHANT_KEY']);
            $_SESSION['MERCHANT_KEY'] = !empty($mkey)?$mkey:'';
        else:
            $_SESSION['MERCHANT_KEY'] = !empty($mkey)?$mkey:'';
        endif;

        //echo '<strong>Merchant Key:</strong> '.$mkey.'<br/> <strong>Merchant ID :</strong>'.$mid.'<br/> <strong>Sub Merchant ID :</strong>'.$smid; die;
    }

    public function getDefaultUrl($amount, $reference_no, $optionalField=null){
        $paymentUrl = $this->generateUrl($reference_no.'|'.$this->sub_merchant_id.'|'.$amount, $optionalField, $amount, $reference_no);
        return $paymentUrl;
    }

    protected function generateUrl($mandatoryField, $optionalField, $amount, $reference_no){
        $encryptedUrl = self::DEFAULT_BASE_URL."merchantid=".$this->merchant_id."&mandatory fields=".$mandatoryField."&optional fields=".$optionalField."&returnurl=".$this->return_url."&Reference No=".$reference_no."&submerchantid=".$this->getSubMerchantId()."&transaction amount=".$amount."&paymode=".$this->paymode;

        return $encryptedUrl;
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
        $encryptedUrl = self::DEFAULT_BASE_URL."merchantid=".$this->merchant_id."&mandatory fields=".$mandatoryField."&optional fields=".$optionalField."&returnurl=".$this->getReturnUrl()."&Reference No=".$reference_no."&submerchantid=".$this->getSubMerchantId()."&transaction amount=".$amount."&paymode=".$this->getPaymode();

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