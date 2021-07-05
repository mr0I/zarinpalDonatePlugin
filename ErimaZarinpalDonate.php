<?php
/*
Plugin Name: Zarinpal Donate - حمایت مالی
Plugin URI:
Description: افزونه حمایت مالی از وبسایت ها -- برای استفاده تنها کافی است کد زیر را درون بخشی از برگه یا نوشته خود قرار دهید  [ErimaZarinpalDonate]
Version: 1.5
Author: John Dou
Author URI: sisoog.com
*/

defined('ABSPATH') or die('Access denied!');
define ('ErimaZarinpalDonateDIR', plugin_dir_path( __FILE__ ));
define ('LIBDIR'  , ErimaZarinpalDonateDIR.'/lib');
define ('INCDIR'  , ErimaZarinpalDonateDIR.'/inc/');
define('ASSETSDIR', plugin_dir_url(__FILE__) . 'assets/');
define ('TABLE_DONATE'  , 'erima_donate');

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ErimaZarinpalDonateDIR . 'env.php';


if ( is_admin() )
{
	add_action('admin_menu', 'EZD_AdminMenuItem');
	function EZD_AdminMenuItem()
	{
		add_menu_page( 'تنظیمات افزونه حمایت مالی - زرین پال', 'حمایت مالی', 'administrator', 'EZD_MenuItem', 'EZD_MainPageHTML', /*plugins_url( 'myplugin/images/icon.png' )*/'dashicons-money-alt
', 6 );
		add_submenu_page('EZD_MenuItem','نمایش حامیان مالی','نمایش حامیان مالی', 'administrator','EZD_Hamian','EZD_HamianHTML');
	}
}

function EZD_MainPageHTML()
{
	include('EZD_AdminPage.php');
}

function EZD_HamianHTML()
{
	include('EZD_Hamian.php');
}


add_action( 'init', function (){
	add_shortcode('ErimaZarinpalDonate', 'ErimaZarinpalDonateForm');
	add_shortcode('ShowAuthorsList', 'ShowAuthorsListPage');
	add_shortcode('ShowDonatesList', 'ShowDonatesListPage');
});

function ShowAuthorsListPage(){
	ob_start();
	include(plugin_dir_path( __FILE__ ).'./site/views/show_authors_list.php');
	return do_shortcode(ob_get_clean());
}

function ShowDonatesListPage(){
	ob_start();
	include(plugin_dir_path( __FILE__ ).'./site/views/ShowDonatesListPage.php');
	return do_shortcode(ob_get_clean());
}

function ErimaZarinpalDonateForm() {
	$out = '';
	$outMessage = '';
	$error = '';
	$message = '';

	$MerchantID = get_option( 'EZD_MerchantID');
	$EZD_IsOK = get_option( 'EZD_IsOK');
	$EZD_IsError = get_option( 'EZD_IsError');
	$EZD_Unit = get_option( 'EZD_Unit');

	$Amount = '';
	$Description = '';
	$Name = '';
	$Mobile = '';
	$Email = '';

	/// Start REQUEST
	if(isset($_POST['submit']) && $_POST['submit'] == 'پرداخت')
	{
		require_once( LIBDIR . '/nusoap.php' );

		if($MerchantID == '')
		{
			$error = 'کد دروازه پرداخت وارد نشده است' . "<br>\r\n";
		}


		$Name =           filter_input(INPUT_POST, 'EZD_Name', FILTER_SANITIZE_SPECIAL_CHARS);  // Required
		$Mobile =         filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_SPECIAL_CHARS); // Optional
		$Email =          filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS); // Optional
		$Description =    filter_input(INPUT_POST, 'EZD_Description', FILTER_SANITIZE_SPECIAL_CHARS);  // Optional
		$Amount =         filter_input(INPUT_POST, 'EZD_Amount', FILTER_SANITIZE_SPECIAL_CHARS);
		$AuthorId =       filter_input(INPUT_POST, 'author_id', FILTER_SANITIZE_SPECIAL_CHARS); // Required
		$userName =       filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_SPECIAL_CHARS); // Required

		if ($Name == '' || $Name == null){
			$error .= 'لطفا نام خود را وارد کنید!' . "<br>\r\n";
		}
		if(is_numeric($Amount) != false)
		{
			if($EZD_Unit == 'ریال')
				$SendAmount =  $Amount / 10;
			else
				$SendAmount =  $Amount;
		}
		else
		{
			$error .= 'مبلغ به درستی وارد نشده است' . "<br>\r\n";
		}

		global $wpdb;
		$usersTable = $wpdb->prefix . 'users';
		$res = $wpdb->get_results("SELECT * FROM ${usersTable} WHERE ID = '${AuthorId}' AND display_name='${userName}' LIMIT 1");
		if(count($res) == 0) {
			$error .= 'خطا در اعتبارسنجی!' . "<br>\r\n";
		}


		$SendDescription = $Name . ' | ' . $Mobile . ' | ' . $Email . ' | ' . $Description ;

		if($error == '')
		{
			$CallbackURL = EZD_GetCallBackURL();  // Required
			// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl

			$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
			if (getenv('IS_DEV') == '1'){
				$client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
			}
			$client->soap_defencoding = 'UTF-8';
			$result = $client->call('PaymentRequest', array(
					array(
						'MerchantID' 	=> $MerchantID,
						'Amount' 		=> $SendAmount,
						'Description' 	=> $SendDescription,
						'Email' 		=> $Email,
						'Mobile' 		=> $Mobile,
						'CallbackURL' 	=> $CallbackURL
					)
				)
			);
			//Redirect to URL You can do it also by creating a form
			if($result['Status'] == 100)
			{
				// WriteToDB
				EZD_AddDonate(array(
					'Authority'     => $result['Authority'],
					'Name'          => $Name,
					'AmountTomaan'  => $SendAmount,
					'Mobile'        => $Mobile,
					'Email'         => $Email,
					'InputDate'     => current_time( 'mysql' ),
					'Description'   => $Description,
					'Author'   => $userName,
					'Status'        => 'SEND',
					'paymentStatus'        => 'Not Paid'
				),array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				));

				//Header('Location: https://www.zarinpal.com/pg/StartPay/'.$result['Authority']);

				$Location = 'https://www.zarinpal.com/pg/StartPay/'.$result['Authority'];
				if (getenv('IS_DEV') == '1'){
					$Location = 'https://sandbox.zarinpal.com/pg/StartPay/'.$result['Authority'];
				}


				return "<script>document.location = '${Location}'</script><center>در صورتی که به صورت خودکار به درگاه بانک منتقل نشدید <a href='${Location}'>اینجا</a> را کلیک کنید.</center>";
			}
			else
			{
				$error .= EZD_GetResaultStatusString($result['Status']) . "<br>\r\n";
			}
		}
	}
	//// END REQUEST


	/// Start RESPONSE
	if(isset($_GET['Authority']))
	{
		require_once( LIBDIR . '/nusoap.php' );

		$Authority = filter_input(INPUT_GET, 'Authority', FILTER_SANITIZE_SPECIAL_CHARS);

		if($_GET['Status'] == 'OK'){

			$Record = EZD_GetDonate($Authority);
			if( $Record  === false)
			{
				$error .= 'چنین تراکنشی در سایت ثبت نشده است' . "<br>\r\n";
			}
			else
			{
				$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
				if (getenv('IS_DEV') == '1'){
					$client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
				}

				$client->soap_defencoding = 'UTF-8';
				$result = $client->call('PaymentVerification', array(
						array(
							'MerchantID'	 => $MerchantID,
							'Authority' 	 => $Record['Authority'],
							'Amount'	 	 => $Record['AmountTomaan']
						)
					)
				);

				if($result['Status'] == 100)
				{
					EZD_ChangeStatus($Authority, 'OK');
					$message .= get_option( 'EZD_IsOk') . "<br>\r\n";
					$message .= 'کد پیگیری تراکنش:'. $result['RefID'] . "<br>\r\n";

					$EZD_TotalAmount = get_option("EZD_TotalAmount");
					update_option("EZD_TotalAmount" , $EZD_TotalAmount + $Record['AmountTomaan']);

					global $wpdb;
					$table = $wpdb->prefix . TABLE_DONATE;
					$update = $wpdb->update( $table, array(
						'TrackingCode'=> $result['RefID']
					),
						array( 'Authority' => $Authority),
						array( '%s' ),
						array( '%s' )
					);

					// Send email to author
					$donate = $wpdb->get_results( "SELECT * FROM $table WHERE Authority='$Authority' ");
					$AuthorName = $donate[0]->Author;
					$AuthorEmail = $wpdb->get_results( "SELECT user_email FROM $wpdb->users WHERE display_name='$AuthorName' ");
					sendEmail( $AuthorName, $result['RefID'] , $donate[0]->AmountTomaan , $AuthorEmail[0]->user_email);
				}
				else
				{
					EZD_ChangeStatus($Authority, 'ERROR');
					$error .= get_option( 'EZD_IsError') . "<br>\r\n";
					$error .= EZD_GetResaultStatusString($result['Status']) . "<br>\r\n";
				}
			}
		}
		else
		{
			$error .= 'تراکنش توسط کاربر بازگشت خورد';
			EZD_ChangeStatus($Authority, 'CANCEL');
		}
	}
	/// END RESPONSE


	$style = '';
	if(get_option('EZD_UseCustomStyle') == 'true')
	{
		$style = get_option('EZD_CustomStyle');
	}
	else
	{
		$style = '#EZD_MainForm {  width: 400px;  height: auto;  margin: 0 auto;  direction: rtl; }  #EZD_Form {  width: 96%;  height: auto;  float: right;  padding: 10px 2%; }  #EZD_Message,#EZD_Error {  width: 90%;  margin-top: 10px;  margin-right: 2%;  float: right;  padding: 5px 2%;  border-right: 2px solid #006704;  background-color: #e7ffc5;  color: #00581f; }  #EZD_Error {  border-right: 2px solid #790000;  background-color: #ffc9c5;  color: #580a00; }  .EZD_FormItem {  width: 90%;  margin-top: 10px;  margin-right: 2%;  float: right;  padding: 5px 2%; }    .EZD_FormLabel {  width: 35%;  float: right;  padding: 3px 0; }  .EZD_ItemInput {  width: 64%;  float: left; }  .EZD_ItemInput input {  width: 90%;  float: right;  border-radius: 3px;  box-shadow: 0 0 2px #00c4ff;  border: 0px solid #c0fff0;  font-family: inherit;  font-size: inherit;  padding: 3px 5px; }  .EZD_ItemInput input:focus {  box-shadow: 0 0 4px #0099d1; }  .EZD_ItemInput input.error {  box-shadow: 0 0 4px #ef0d1e; }  input.EZD_Submit {  background: none repeat scroll 0 0 #2ea2cc;  border-color: #0074a2;  box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset, 0 1px 0 rgba(0, 0, 0, 0.15);  color: #fff;  text-decoration: none;  border-radius: 3px;  border-style: solid;  border-width: 1px;  box-sizing: border-box;  cursor: pointer;  display: inline-block;  font-size: 13px;  line-height: 26px;  margin: 0;  padding: 0 10px 1px;  margin: 10px auto;  width: 50%;  font: inherit;  float: right;  margin-right: 24%; }';
	}


	global $wpdb;
	$usersTable = $wpdb->prefix . 'users';
	$user_id = $_GET['transaction_id'];
	$display_name = $_GET['user_name'];
	$author = $wpdb->get_results( "SELECT * FROM $usersTable WHERE id='$user_id' AND display_name='$display_name' ");

	(sizeof($author) !== 0)? $author_name = get_the_author_meta( 'display_name', $_GET['transaction_id'] ) : $author_name = '';
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$Name = $current_user->display_name;
	}

	$out = '
  <style>
    '. $style . '
  </style>
  
  
 
  
   <div style="clear:both;width:100%;float:right;">
	   <div id="EZD_MainForm">
	        <div class="EZD_FormTitle">
		        <h4 class="">شما در حال حمایت از <span>'.$author_name.'</span>  هستید. </h4>
			</div>
          <div id="EZD_Form">';
	if($message != '')
	{
		$outMessage = "<div id=\"EZD_Message\">
    ${message}
            </div>";
		return $outMessage;
	} else {
		$out .=      '<div class="col-lg-5 col-md-5 col-sm-12"><form method="post" id="erima_add_donate_frm">
              <div class="EZD_FormItem required">
                <label class="EZD_FormLabel">نام شما</label>
                
                <div class="EZD_ItemInput"><input type="text" name="EZD_Name" id="EZD_Name_Input" value="'. $Name .'" /></div>
              </div>
              
              <div class="EZD_FormItem">
                <label class="EZD_FormLabel">تلفن همراه</label>
                <div class="EZD_ItemInput"><input type="text" name="mobile" value="'. $Mobile .'" /></div>
              </div>
              
              <div class="EZD_FormItem">
                <label class="EZD_FormLabel">ایمیل</label>
                <div class="EZD_ItemInput"><input type="text" name="email" style="direction:ltr;text-align:left;" value="'. $Email .'" /></div>
              </div>
              
              <div class="EZD_FormItem">
                <label class="EZD_FormLabel">توضیحات</label>
                <div class="EZD_ItemInput"><input type="text" name="EZD_Description" value="'. $Description .'" /></div>
              </div>
              
              <div class="EZD_FormItem required">
                <label class="EZD_FormLabel">مبلغ</label>
                <div class="EZD_ItemInput">
                <select name="EZD_Amount" id="EZD_Amount_Select">
	                <option value="0">---</option>
	                <option value="10000">10000 تومان</option>
	                <option value="20000">20000 تومان</option>
	                <option value="50000">50000 تومان</option>
	                <option value="100000">100000 تومان</option>
	                <option value="others">سایر مبالغ</option>
				</select>
                  <input style="width:60%" type="text" name="EZD_Amount" id="EZD_Amount_Input" placeholder="مبلغ دلخواهتان را وارد کنید..." 
                  value="'. $Amount .'" onkeyup="this.value = this.value.replace(/[^\d]+/g, \'\');" />
                  <span style="margin-right:10px;display: none;">'. $EZD_Unit .'</span>
                </div>
              </div>
              
              <input type="hidden" value="'. $_GET['transaction_id'] .'" name="author_id">
              <input type="hidden" value="'. $_GET['user_name'] .'" name="user_name">
              <div class="EZD_FormItem"> 
              <input type="submit" name="submit" class="EZD_Submit" value="پرداخت" disabled />
              </div>
            </form>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12">
            <figure>
	            <img src="'.ASSETSDIR.'images/donate.jpg" alt="">
			</figure>
			</div>
            
           
          </div>
        </div>
      </div>
	';

		if($error != '')
		{
			$out .= "<div id=\"EZD_Error\">
    ${error}
            </div>";
		}

		return $out;
	}




}

/////////////////////////////////////////////////
// تنظیمات اولیه در هنگام اجرا شدن افزونه.
register_activation_hook(__FILE__,'EriamZarinpalDonate_install');
function EriamZarinpalDonate_install()
{
	EZD_CreateDatabaseTables();
}
function EZD_CreateDatabaseTables()
{
	global $wpdb;
	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;
	// Creat table
	$table = "CREATE TABLE IF NOT EXISTS `$erimaDonateTable` (
					  `DonateID` int(11) NOT NULL AUTO_INCREMENT,
					  `Authority` varchar(55) NOT NULL,
					  `Name` varchar(55) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
					  `AmountTomaan` int(11) NOT NULL,
					  `Mobile` varchar(11) ,
					  `Email` varchar(55),
					  `InputDate` varchar(55),
					  `Description` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci,
					  `TrackingCode` varchar(100),
					  `Status` varchar(20),
					  `paymentStatus` varchar(20),
					  `Author` varchar(55),
					  PRIMARY KEY (`DonateID`),
					  KEY `DonateID` (`DonateID`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	dbDelta($table);
	// Other Options
	add_option("EZD_TotalAmount", 0, '', 'yes');
	add_option("EZD_TotalPayment", 0, '', 'yes');
	add_option("EZD_IsOK", 'با تشکر پرداخت شما به درستی انجام شد.', '', 'yes');
	add_option("EZD_IsError", 'متاسفانه پرداخت انجام نشد.', '', 'yes');

	$style = '#EZD_MainForm {
  width: 400px;
  height: auto;
  margin: 0 auto;
  direction: rtl;
}

#EZD_Form {
  width: 96%;
  height: auto;
  float: right;
  padding: 10px 2%;
}

#EZD_Message,#EZD_Error {
  width: 90%;
  margin-top: 10px;
  margin-right: 2%;
  float: right;
  padding: 5px 2%;
  border-right: 2px solid #006704;
  background-color: #e7ffc5;
  color: #00581f;
}

#EZD_Error {
  border-right: 2px solid #790000;
  background-color: #ffc9c5;
  color: #580a00;
}

.EZD_FormItem {
  width: 90%;
  margin-top: 10px;
  margin-right: 2%;
  float: right;
  padding: 5px 2%;
}

.EZD_FormLabel {
  width: 35%;
  float: right;
  padding: 3px 0;
}

.EZD_ItemInput {
  width: 64%;
  float: left;
}

.EZD_ItemInput input {
  width: 90%;
  float: right;
  border-radius: 3px;
  box-shadow: 0 0 2px #00c4ff;
  border: 0px solid #c0fff0;
  font-family: inherit;
  font-size: inherit;
  padding: 3px 5px;
}

.EZD_ItemInput input:focus {
  box-shadow: 0 0 4px #0099d1;
}

.EZD_ItemInput input.error {
  box-shadow: 0 0 4px #ef0d1e;
}

input.EZD_Submit {
  background: none repeat scroll 0 0 #2ea2cc;
  border-color: #0074a2;
  box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset, 0 1px 0 rgba(0, 0, 0, 0.15);
  color: #fff;
  text-decoration: none;
  border-radius: 3px;
  border-style: solid;
  border-width: 1px;
  box-sizing: border-box;
  cursor: pointer;
  display: inline-block;
  font-size: 13px;
  line-height: 26px;
  margin: 0;
  padding: 0 10px 1px;
  margin: 10px auto;
  width: 50%;
  font: inherit;
  float: right;
  margin-right: 24%;
}

input[name="EZD_Amount"]{
display:none !important;
}

';
	add_option("EZD_CustomStyle", $style, '', 'yes');
	add_option("EZD_UseCustomStyle", 'false', '', 'yes');
}

function EZD_GetDonate($Authority)
{
	global $wpdb;
	$Authority = strip_tags($wpdb->escape($Authority));

	if($Authority == '')
		return false;

	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;

	$res = $wpdb->get_results( "SELECT * FROM ${erimaDonateTable} WHERE Authority = '${Authority}' LIMIT 1",ARRAY_A);

	if(count($res) == 0)
		return false;

	return $res[0];
}

function EZD_AddDonate($Data, $Format)
{
	global $wpdb;

	if(!is_array($Data))
		return false;

	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;

	$res = $wpdb->insert( $erimaDonateTable , $Data, $Format);

	if($res == 1)
	{
		$totalPay = get_option('EZD_TotalPayment');
		$totalPay += 1;
		update_option('EZD_TotalPayment', $totalPay);
	}

	return $res;
}

function EZD_ChangeStatus($Authority,$Status)
{
	global $wpdb;
	$Authority = strip_tags($wpdb->escape($Authority));
	$Status = strip_tags($wpdb->escape($Status));

	if($Authority == '' || $Status == '')
		return false;

	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;

	$res = $wpdb->query( "UPDATE ${erimaDonateTable} SET `Status` = '${Status}' WHERE `Authority` = '${Authority}'");

	return $res;
}

function EZD_GetResaultStatusString($StatusNumber)
{
	switch($StatusNumber)
	{
		case -1:
			return 'اطلاعات ارسال شده ناقص است';
		case -2:
			return 'IP و یا مرچنت کد پذیرنده صحیح نیست';
		case -3:
			return 'رقم باید بالای صد تومان باشد';
		case -4:
			return 'سطح تایید پذیرنده پایین تر از سطح نقره ای است';
		case -11:
			return 'درخواست مورد نظر یافت نشد';
		case -21:
			return 'هیچ نوع عملیات مالی برای این تراکنش یافت نشد';
		case -22:
			return 'تراکنش نا موفق می باشد';
		case -33:
			return 'رقم تراکنش با رقم پرداخت شده مطابقت ندارد';
		case -54:
			return 'درخواست مورد نظر آرشیو شده';
		case 100:
			return 'عملیات با موفقیت انجام شد';
		case 101:
			return 'عملیات این تراکنش با موفقیت انجام شد ولی قبلا عملیات اعتبار سنجی بر روی این تراکنش انجام شده است';
	}

	return '';
}

function EZD_GetCallBackURL()
{
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

	$ServerName = htmlspecialchars($_SERVER["SERVER_NAME"], ENT_QUOTES, "utf-8");
	$ServerPort = htmlspecialchars($_SERVER["SERVER_PORT"], ENT_QUOTES, "utf-8");
	$ServerRequestUri = htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, "utf-8");

	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $ServerName .":". $ServerPort . $_SERVER["REQUEST_URI"] ;
	}
	else
	{
		$pageURL .= $ServerName . $ServerRequestUri;
	}
	return $pageURL;
}

function sendEmail($name,$tracking_code,$AmountTomaan,$email){
	ob_start();
	include INCDIR . 'email_template.php';
	$html=ob_get_contents();
	ob_end_clean();
	$html=  str_replace('{name}',$name, $html);
	$html=  str_replace('{AmountTomaan}',$AmountTomaan, $html);
	$html=  str_replace('{tracking_code}',$tracking_code, $html);
	$headers  = 'From: no-reply@domain.com'. "\r\n" .
	            'MIME-Version: 1.0' . "\r\n" .
	            'Content-type: text/html; charset=utf-8' . "\r\n" .
	            'X-Mailer: PHP/' . phpversion();

	return wp_mail( $email, 'حمایت مالی(سیسوگ)', $html, $headers);
}


define('ZARIN_ADMIN_CSS', plugin_dir_url(__FILE__) . 'admin/css/');
define('ZARIN_ADMIN_JS', plugin_dir_url(__FILE__) . 'admin/js/');
define('ZARIN_ADMIN', plugin_dir_path(__FILE__) . 'admin/');
add_action ('admin_enqueue_scripts', function(){
	wp_enqueue_style('admin-styles', ZARIN_ADMIN_CSS.'admin-styles.css');
	wp_enqueue_script('admin-scripts', ZARIN_ADMIN_JS.'admin-scripts.js' , array('jquery'));
	wp_localize_script( 'admin-scripts', 'ZARINADMINAJAX', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'security' => wp_create_nonce( '28=(n6i|R|CMQ/' )
	));
});

if(is_admin()){
	include(ZARIN_ADMIN . 'ajax_requests.php');
}

define('ZARIN_CSS', plugin_dir_url(__FILE__) . 'assets/css/');
define('ZARIN_JS', plugin_dir_url(__FILE__) . 'assets/js/');
add_action( 'wp_enqueue_scripts', function(){
	// styles
	wp_enqueue_style( 'bootstrap', ZARIN_CSS . 'bootstrap.min.css');
	wp_enqueue_style( 'erima_styles', ZARIN_CSS . 'styles.css');
	// scripts
	wp_enqueue_script('erima_scripts', ZARIN_JS.'scripts.js' , array('jquery'));
});


?>