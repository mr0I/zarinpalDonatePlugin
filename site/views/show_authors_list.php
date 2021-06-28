<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php

if ( !is_user_logged_in() ) {
	?>
    <div class="alert alert-warning text-center" role="alert">
        <p>برای مشاهده این صفحه باید وارد سایت شوید!</p>
        <p><a href="https://sisoog.com/login/">ورود به سایت</a></p>
    </div>
	<?php
}else{
	$current_user = wp_get_current_user();
	$Name = $current_user->display_name;

	$Limit = '';
	if(isset($_GET['page_num']))
	{
		$page = htmlspecialchars(strip_tags(trim($_GET['page_num'])), ENT_QUOTES);
		if($page == 0) $page = 1;

		$End = $page * 4;
		$Start = $End - 4;
		if($page > 1) $Start ++;
		$Limit = " LIMIT $Start , $End";
	}


	global $wpdb;
	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;
	$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' ORDER BY DonateID DESC ");

	if (isset($_GET['sort_by'])){
		$sort = $_GET['sort_by'];
		switch ($sort){
			case 'all';
				$user_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' ORDER BY DonateID DESC $Limit ");
				break;
			case 'paid':
				$user_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' AND paymentStatus='Paid' ORDER BY DonateID DESC $Limit ");
				break;
			case 'not_paid':
				$user_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' AND paymentStatus='Not Paid' ORDER BY DonateID DESC $Limit ");
				break;
            default:
	            $user_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' ORDER BY DonateID DESC $Limit ");
		}
	} else{
		$user_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' ORDER BY DonateID DESC $Limit ");
	}

	$paid_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Author='$Name' AND paymentStatus='Paid' ORDER BY DonateID DESC $Limit ");
	?>

	<?php
	if (count($user_donates) == 0){
		?>
        <div class="alert alert-warning" role="alert"><p>هنوز هیچ پرداختی ثبت نشده است!</p></div>
		<?php
	} else {
		?>
        <div class="container">
            <div class="row">
                <div class="sort_btns">
                    <a href="?sort_by=all">همه <span class="count">(<?= count($all_donates); ?>)</span></a> |</a>
                    <a href="?sort_by=paid">پرداخت شده <span class="count">(<?= count($paid_donates); ?>)</span></a> |</a>
                    <a href="?sort_by=not_paid">پرداخت نشده <span class="count">(<?= count($all_donates) -count($paid_donates); ?>)</span></a></a>
                </div>

                <div class="table-responsive">
                    <table class="table" id="authors_list_table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>مبلغ</th>
                            <th>وضعیت تسویه حساب</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$counter = 1;
						foreach ($user_donates as $row){
							?>
                            <tr>
                                <td><?= $counter++; ?></td>
                                <td class="digits"><?= $row->AmountTomaan; ?></td>
                                <td style="font-weight: bold;"><?= ($row->paymentStatus == 'Paid')? '<span style="color: seagreen">پرداخت شده</span>' : '<span style="color: darkred">پرداخت نشده</span>' ?></td>
                                <td><?= $row->InputDate; ?></td>
                            </tr>
							<?php
						}
						?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div class="actions paginate_btns">
			<?php
			$total = count($user_donates);

			$PageNumInt = 1;
			if($total > 0)
			{
				$PagesNum = $total / 4;
				$PageNumInt = intval($PagesNum);
				if($PageNumInt < $PagesNum)
					$PageNumInt++;
			}

			$currentPage = 1;
			if(isset($_GET['page_num'])){
				$currentPage = htmlspecialchars(strip_tags(trim($_GET['page_num'])), ENT_QUOTES);
			}
			?>
			<?php
			for($i = 1 ; $i <= $PageNumInt; $i++)
			{
				if($i == $currentPage)
					echo '<a href="?page_num='. $i .'"  class="first-page active">'. $i .'</a>';
				else
					echo '<a href="?page_num='. $i .'"  class="first-page">'. $i .'</a>';
			}
			?>
        </div>
        <br class="clear" />

		<?php
	}
}

