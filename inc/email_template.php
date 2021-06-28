<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<body style="background-color: #EEE;width:100%;height:100%">
<div style="font-family: tahoma;font-size: 100%;direction: <?php echo is_rtl() ? 'rtl' : 'ltr' ?>;border:1px solid #CCC;width:500px; margin:50px auto;background-color: #FFF;border-radius: 3px;
	-webkit-border-radius: 3px;
	-o-border-radius: 3px;
	-moz-border-radius: 3px;
	-ms-border-radius: 3px;">
	<div style="width:100%;border-bottom: 2px solid #CCC;text-align: center;font-size: 10pt;padding: 20px 0;background-color:#DDD;"><?= 'حمایت مالی از شما' ?></div>
	<div style="font-size: 9pt;padding: 20px;line-height: 150%;">
		<h4 style=" text-align: right;margin-bottom: 20px;color:#009;"><?= 'پرداختی با مشخصات زیر برای شما ارسال شد.' ?></h4>
		<p style="text-align: right"><span><?= 'مبلغ واریزی' ?>: </span>{AmountTomaan}</p>
		<p style="text-align: right"><span><?= 'کد رهگیری' ?>: </span>{tracking_code}</p>
	</div>
</div>
</body>