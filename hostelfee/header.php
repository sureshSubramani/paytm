<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>

    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="description" content="LearnPress | Education & Courses HTML Template" />
    <meta name="keywords" content="academy, course, education, education html theme, elearning, learning," />
    <meta name="author" content="ThemeMascot" />
    <!-- Page Title -->
    <title>Online Hostel Fee Payment</title>
    <!-- Favicon and Touch Icons  -->
    <link rel="icon" href="https://mahendra.org/wp-content/uploads/2021/05/cropped-Mahendra-Favicon-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://mahendra.org/wp-content/uploads/2021/05/cropped-Mahendra-Favicon-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://mahendra.org/wp-content/uploads/2021/05/cropped-Mahendra-Favicon-180x180.png" />

    <!-- Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href="css/animate.css" rel="stylesheet" type="text/css">
    <link href="css/css-plugin-collections.css" rel="stylesheet" />
    <!-- CSS | Main style file -->
    <link href="css/style-main.css" rel="stylesheet" type="text/css">
    <!-- CSS | Custom Margin Padding Collection -->
    <link href="css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
    <!-- CSS | Responsive media queries -->
    <link href="css/responsive.css" rel="stylesheet" type="text/css">
    <!-- CSS | Responsive media queries -->
    <link href="css/custom.css" rel="stylesheet" type="text/css">

    <!-- external javascripts -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollbox.js"></script>

<style>
     @import url('https://fonts.googleapis.com/css2?family=Comfortaa&family=Josefin+Sans&display=swap');

    .boxshadow {
        -moz-box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }
    
    h1.header-title{
        color:#fff;
		font-size: 3.4rem;        
    }
   
    body {
        font-family: 'Josefin Sans', sans-serif;
        background: transparent;
        height: 100%;
        background-image: url("./images/payment-bg.jpg");
        background-position: 50% 50%;
        background-attachment: fixed;
        background-size: cover;
    }

    .m-top {
        margin-top: 20px !important
    }

    .table {
        width: 100%;
        max-width: 500px;
        border: none;
        margin: 0px auto;
        margin-top: 0px;
        margin-bottom: 0px;
        border: 0px solid #fff;
    }

    .payment_form {
        background: #ffffff;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 5px 5px 15px #2f305b;
    }

    .table th,
    .table td {
        border-top: none !important;
    }

    .otp-title {
        top: -15px;
        position: relative;
    }

    .modal {
        top: 35% !important;
    }

    #mob-verify i {
        color: green;
    }

    table {
        font-family: 'Josefin Sans', sans-serif;
    }

    h1.header-title {
        color: #ffffff;
        font-family: 'Josefin Sans', sans-serif;
        font-size: 2.5rem;
    }

    .header {
        background: #2f2e58;
        color: white;
        opacity: 0.95;
        background-image: linear-gradient(45deg, #2e315c, #345daf, #2d305b);
    }

    .mt-1 {
        margin-top: 10px;
    }

    .table>tbody>tr>td {
        vertical-align: initial !important;
    }

    h1 {
        margin: 0 auto;
    }

    .pay-title {
        padding-top: 10px;
        font-size: 15px;
        color: #3f51b5;
        text-align: center;
        background-image: linear-gradient(0deg, #673ab7, #2196f3);
        -webkit-font-smoothing: antialiased;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 16pt;
        font-weight: 600;
    }

    /* Begin Initiate Spinner */
    ._loader{
        width: 100px;
        height: 100px;
        border-radius: 100%;
        position: relative;
        margin: 0 auto;
    }

    /* Initiate Spinner  */
    .spinner span {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 100%;
        background-color: #ffffff;
        margin: 0px 5px;
        opacity: 0;
    }

    .spinner span:nth-child(1){
        animation: opacitychange 1s ease-in-out infinite;
    }

    .spinner span:nth-child(2){
        animation: opacitychange 1s ease-in-out 0.33s infinite;
    }

    .spinner span:nth-child(3){
        animation: opacitychange 1s ease-in-out 0.66s infinite;
    }

    @keyframes opacitychange{
        0%, 100%{ opacity: 0; }
        60%{ opacity: 1; }
    }

    #amount {
        width:60%;
        height:auto
    }

    .error {
        color: #f44336;
        display: inline-block;
        margin-bottom: 10px;
    }

    #next{
        top: 2px;
        position: relative;
    }

    .paytm span{
        position:relative;  
        display:inline;      
    }
    
    .paytm img {
        /* width: 80px; */
        left: -24px;
        position: absolute;
        top: 6px;
    }

    .paytm span:nth-child(1){
        animation-name: fade_1;
        animation-fill-mode: both;
        animation-iteration-count: infinite;
        animation-duration: 1.5s;
        animation-direction: alternate-reverse;  
        position: absolute;
    }

    .paytm span:nth-child(2){
        animation-name: fade_1;
        animation-fill-mode: both;
        animation-iteration-count: infinite;
        animation-duration: 1.5s;
        animation-direction: alternate;
    }

    .eazypay span{
        position:relative;  
        display:inline;      
    }
    
    .eazypay img {
        width: 80px;
        left: -16px;
        position: absolute;
        top: 6px;
    }

    .eazypay span:nth-child(1){
        animation-name: fade_2;
        animation-fill-mode: both;
        animation-iteration-count: infinite;
        animation-duration: 1.5s;
        animation-direction: alternate-reverse;  
        position: absolute;
    }

    .eazypay span:nth-child(2){
        animation-name: fade_2;
        animation-fill-mode: both;
        animation-iteration-count: infinite;
        animation-duration: 1.5s;
        animation-direction: alternate;
    }

    @keyframes fade_1{
            0%,50% {
            opacity: 0;
        }
            100%{
            opacity: 1;
            width: 100px;
        }
    }

    @keyframes fade_2{
            0%,50% {
            opacity: 0;
        }
            100%{
            opacity: 1;
            width: 100px;
        }
    }
	
	.table-responsive {
        border: 0px solid #ddd0;
    }
  
</style>
</head>

<body>
    <div id="wrapper" class="clearfix">
        <!-- Header -->
        <header class="header mt-5">
            <div class="header-nav navbar-scrolltofixed">
                <div class="header-nav-wrapper header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                <h1 class="header-title">MEI HOSTEL PAYMENT</h1>
                            </div>                         
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>