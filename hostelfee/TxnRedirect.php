<?php 

    session_start();

    /*echo '<pre>';
    print_r($_REQUEST);
    die;*/

    if(isset($_REQUEST['ORDER_ID'])){

        if(!empty($_REQUEST['COLLEGE_ID'])){
            $_SESSION['COLLEGE_ID']  = $_REQUEST['COLLEGE_ID'];
        }else if(!empty($_REQUEST['CUST_ID'])){
            $_SESSION['CUST_ID']  = $_REQUEST['CUST_ID'];
        }else if(!empty($_REQUEST['MOBILE_NO'])){
            $_SESSION['MOBILE_NO'] = $_REQUEST['MOBILE_NO'];
        }else if(!empty($_REQUEST['EMAIL'])){
            $_SESSION['EMAIL'] = $_REQUEST['EMAIL'];            
        }

        $NAME   = isset($_REQUEST['COLLEGE_ID'])?$_REQUEST['COLLEGE_ID']:NULL;
        $ROLL   = isset($_REQUEST['CUST_ID'])?$_REQUEST['CUST_ID']:NULL;
        $EMAIL  = isset($_REQUEST['EMAIL'])?$_REQUEST['EMAIL']:NULL;
        $PHONE  = isset($_REQUEST['MOBILE_NO'])?$_REQUEST['MOBILE_NO']:NULL;

        $optionalField = 'UPIVPA|'.$NAME.'|'.$ROLL.'|'.$EMAIL.'|'.$PHONE;

        $amount = $_REQUEST['TXN_AMOUNT'];
        include 'Eazypay.php';
        //$reference_no = rand();
        $reference_no = $_REQUEST['ORDER_ID'];
        // call The method
        $eazypay = new Eazypay();
        $url = $eazypay->getPaymentUrl($amount, $reference_no, $optionalField);

        // $defaultUrl=$eazypay->getDefaultUrl($amount, $reference_no, $optionalField);
        // echo $optionalField.'<br>';
        // echo $defaultUrl.'<br/><br/>'; 
        // echo $url.'<br/>';
        // die;

        header('Location: '.$url);
        exit;

    }else{
        echo 'Invalid access';
    }
    
  ?>