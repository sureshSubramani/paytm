<?php 

    session_start();

    $message[0]['success'] = 0;

    if(isset($_REQUEST['ORDER_ID'])){

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
        $url=$eazypay->getPaymentUrl($amount, $reference_no, $optionalField);
        // $defaultUrl=$eazypay->getDefaultUrl($amount, $reference_no, $optionalField);
        // echo $optionalField.'<br>';
        // echo $defaultUrl.'<br/><br/>'; die;
        // echo $url.'<br/>';
        // die;

        $message[0]['success'] = 1;
        $message[] = array('url'=>$url);

    }else{
        $message[] = array('message'=>'Invalid Parameters');
    }

    echo json_encode($message,JSON_UNESCAPED_SLASHES);

  ?>