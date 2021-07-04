<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php
global $wpdb;
$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;
if (isset($_GET['sort_by'])){
	$sort = htmlspecialchars(strip_tags(trim($_GET['sort_by'])), ENT_QUOTES);
	switch ($sort){
		case 'max_amount':
			$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ORDER BY AmountTomaan DESC ");
			break;
		case 'last_amount':
			$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ORDER BY InputDate DESC ");
			break;
		default:
			$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ORDER BY AmountTomaan DESC ");
	}
}else{
	$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ORDER BY AmountTomaan DESC ");
}

// Convert Date To Shamsi
require_once LIBDIR . '/jdate/jdatetime.class.php';
$date = new jDateTime(true, true, 'Asia/Tehran');
?>

<div class="container-fluid donateListContainer">
    <div class="donate_title">
        <h3>از  نویسندگان سیسوگ حمایت کنید</h3>
        <p>حمایت شما باعث دلگرمی ما در جهت تولید محتوای بروز و مفید خواهد بود</p>
		<?php
		if (sizeof($all_donates) !== 0){
			?>
            <div class="btn-group">
                <a class="<?php if ($_GET['sort_by'] == 'max_amount' || $_GET['sort_by'] == '') echo 'active'; ?>" href="?sort_by=max_amount" style="border-radius: 0 3px 3px 0;">بیشترین مبلغ</a>
                <a class="<?php if ($_GET['sort_by'] == 'last_amount') echo 'active'; ?>" href="?sort_by=last_amount" style="border-radius: 3px 0 0 3px;">آخرین پرداخت</a>
            </div>
			<?php
		}
		?>
    </div>

    <div class="row">
		<?php
		if (sizeof($all_donates) == 0){
			?>
            <div class="alert alert-warning text-center w-75 m-auto" role="alert">
                <p class="text-center">هنوز هیچ پرداختی انجام نشده است!</p>
            </div>
			<?php
		}
		?>
        <div class="donatesList">
			<?php
			foreach ($all_donates as $row){
				?>
                <div class="card donatesItem col-lg-3 col-md-4 col-sm-12">
                    <figure>
                        <img src="http://www.gravatar.com/avatar/<?= md5($row->Email); ?>?rating=PG&size=24&size=50&d=identicon" alt="تصویر حامی">
                    </figure>
                    <div class="card-body">
                        <h5 class="card-name"><?= ($row->Name) ? $row->Name : "بدون نام" ?></h5>
                        <p class="card-price"><span class="digits"><?= $row->AmountTomaan . ' ' . get_option('EZD_Unit'); ?></span> </p>
                    </div>
                    <div class="card-footer">
						<?php
						$AuthorEmail = $wpdb->get_results( "SELECT user_email FROM $wpdb->users WHERE display_name='$row->Author' ");
						?>
                        <p class="card-date"><small class="text-muted">تاریخ: <?=   $date->date("l j F Y" , strtotime($row->InputDate));  ?></small> </p>
                        <p>
                            <small class="text-muted">نویسنده حمایت شده: <?= ($row->Author)? $row->Author : "-"; ?></small>
                            <img src="http://www.gravatar.com/avatar/<?= md5($AuthorEmail[0]->user_email); ?>?rating=PG&size=24&size=50&d=identicon" alt="تصویر نویسنده">
                        </p>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
		?>

    </div>
</div>