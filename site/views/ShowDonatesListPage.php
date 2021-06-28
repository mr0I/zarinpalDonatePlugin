<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>


<div class="container-fluid donateListContainer">
    <div class="donate_title">
        <h3>از  نویسندگان سیسوگ حمایت کنید</h3>
        <p>حمایت شما باعث دلگرمی ما در جهت تولید محتوای بروز و مفید خواهد بود</p>
        <div class="btn-group">
            <a class="<?php if ($_GET['sort_by'] == 'max_amount') echo 'active'; ?>" href="?sort_by=max_amount" style="border-radius: 0 3px 3px 0;">بیشترین مبلغ</a>
            <a class="<?php if ($_GET['sort_by'] == 'last_amount') echo 'active'; ?>" href="?sort_by=last_amount" style="border-radius: 3px 0 0 3px;">آخرین پرداخت</a>
        </div>
    </div>

	<div class="row">
		<?php
		global $wpdb;
		$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;
		if (isset($_GET['sort_by'])){
			$sort = htmlspecialchars(strip_tags(trim($_GET['sort_by'])), ENT_QUOTES);
			switch ($sort){
				case 'max_amount':
					$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY AmountTomaan DESC ");
					break;
				case 'last_amount':
					$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY InputDate DESC ");
					break;
				default:
					$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY AmountTomaan DESC ");
			}
		}else{
			$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY AmountTomaan DESC ");
		}
		?>

		<div class="donatesList">
			<?php
			foreach ($all_donates as $row){
				?>
				<div class="card donatesItem col-lg-2 col-md-3 col-sm-12">
					<figure>
						<img src="http://www.gravatar.com/avatar/<?= md5($row->Email); ?>?rating=PG&size=24&size=50&d=identicon" alt="تصویر حامی">
					</figure>
					<div class="card-body">
						<h5 class="card-name"><?= $row->Name ?></h5>
						<p class="card-price"><span class="digits"><?= $row->AmountTomaan ?></span> تومان </p>
						<p class="card-date text-muted"><?=   date('Y-m-d' , strtotime($row->InputDate));  ?> </p>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>