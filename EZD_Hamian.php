<?php defined('ABSPATH') or die('&lt;h3&gt;Access denied!'); ?><div class="wrap" xmlns="http://www.w3.org/1999/html">    <div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2>حامیان مالی</h2>	<?php /*<ul class='subsubsub'>	<li class='all'><a href='edit.php?post_type=post' class="current">همه <span class="count">(13)</span></a> |</li>	<li class='publish'><a href='edit.php?post_status=publish&amp;post_type=post'>منتشرشده <span class="count">(12)</span></a> |</li>	<li class='draft'><a href='edit.php?post_status=draft&amp;post_type=post'>پیش‌نویس <span class="count">(1)</span></a></li></ul>*/	?>    <form id="posts-filter" action="<?php echo EZD_GetCallBackURL(); ?>" method="post">        <p class="search-box">            <label class="screen-reader-text" for="post-search-input">جست‌وجوی افراد:</label>            <input type="search" id="post-search-input" name="searchbyname" value="" />            <input type="submit" name="" id="search-submit" class="button" value="جست‌وجوی افراد"  /></p>        <input type="hidden" id="_wpnonce" name="_wpnonce" value="8aa9aa1697" /><input type="hidden" name="_wp_http_referer" value="/Project/wp-admin/edit.php" />	<div class="tablenav top">        </div>        <input type="hidden" name="mode" value="list" />        <br class="clear" /></div><form class="form-inline">    <input type="hidden" name="donate_select_nonce" id="donate_select_nonce" value="<?php echo wp_create_nonce('donate-select-nonce'); ?>" >    <div class="form-group mx-sm-3 mb-2">        <select class="form-control" id="donate_select" >            <option value="0">انتخاب</option>            <option value="1">پرداخت شده</option>        </select>    <button type="button" class="btn btn-primary mb-2" style="cursor: pointer;" id="donate_select_submit" name="item_select_submit" value="انجام">انجام</button>    </div></form><table class="wp-list-table widefat fixed posts" cellspacing="0">    <thead>    <tr>        <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">گزینش همه</label><input id="cb-select-all-1" type="checkbox" /></th>        <th scope='col' id='title' class='manage-column column-title sortable desc'  style="">            <span>نام و نام خانوادگی</span><span class="sorting-indicator"></span></th>        <th scope='col' id='author' class='manage-column column-author'  style="">مبلغ (تومان)</th>        <th scope='col' id='categories' class='manage-column column-categories'  style="">موبایل</th>        <th scope='col' id='tags' class='manage-column column-tags'  style="">ایمیل</th>        <th scope='col' id='comments' class='manage-column column-tags'  style="">توضیحات</th>        <th scope='col' id='payment_status' class='manage-column column-tags'  style="">وضعیت تسویه حساب</th>        <th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>تاریخ</span><span class="sorting-indicator"></span></th>    </tr>    </thead>    <tfoot>    <tr>        <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">گزینش همه</label><input id="cb-select-all-1" type="checkbox" /></th>        <th scope='col' id='title' class='manage-column column-title sortable desc'  style="">            <span>نام و نام خانوادگی</span><span class="sorting-indicator"></span></th>        <th scope='col' id='author' class='manage-column column-author'  style="">مبلغ (تومان)</th>        <th scope='col' id='categories' class='manage-column column-categories'  style="">موبایل</th>        <th scope='col' id='tags' class='manage-column column-tags'  style="">ایمیل</th>        <th scope='col' id='comments' class='manage-column column-tags'  style="">توضیحات</th>        <th scope='col' id='payment_status' class='manage-column column-tags'  style="">وضعیت تسویه حساب</th>        <th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>تاریخ</span><span class="sorting-indicator"></span></th>    </tr>    </tfoot>    <tbody id="the-list">	<?php	////////////Page	if(isset($_REQUEST['pageid']))	{		$page = htmlspecialchars(strip_tags(trim($_REQUEST['pageid'])), ENT_QUOTES);		if($page == 0)			$page = 1;		$End = $page * 30;		$Start = $End - 30;		// در صفحات بزرگتر از یک اولین رکورد در صفحا قبل نمایش داده شده		if($page > 1)			$Start ++;		$Limit = " LIMIT $Start , $End";	}	///	global $wpdb;	$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;	if(isset($_REQUEST['searchbyname']) && $_REQUEST['searchbyname'] != '')	{		$SearchName = htmlspecialchars(strip_tags(trim($_REQUEST['searchbyname'])), ENT_QUOTES);		$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` where `Name` LIKE '%$SearchName%' ORDER BY DonateID DESC ".$Limit);	}	else	{		$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` ORDER BY DonateID DESC ".$Limit);	}	// echo $resault;	foreach($result as $row) :		?>        <tr id="post-109" style="<?php if($row->Status == 'OK') echo 'background-color: #cfc'; ?>" class="post-109 type-post status-draft format-standard hentry category-news alternate iedit author-self" valign="top">            <th scope="row" class="check-column">                <label class="screen-reader-text" for="cb-select-109">گزینش رکورد</label>                <input id="cb-select-109" type="checkbox" name="chkDonates" value="<?= $row->DonateID ?>" />                <div class="locked-indicator"></div>            </th>            <td class="post-title page-title column-title"><strong><?php echo $row->Name; ?></strong> <small><?php echo $row->Status; ?></small>                <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>            </td>            <td class="author column-author"><?php echo $row->AmountTomaan; ?></td>            <td class="categories column-categories"><?php echo $row->Mobile; ?></td>            <td class="tags column-tags"><?php echo $row->Email; ?></td>            <td class="tags column-tags"><?php echo $row->Description; ?></td>	        <?php	        if ($row->paymentStatus == 'Not Paid') {		        ?> <td class="tags column-tags" style="color: red"><?php echo $row->paymentStatus; ?></td> <?php            } else {		        ?> <td class="tags column-tags" style="color: green"><?php echo $row->paymentStatus; ?></td> <?php	        }	        ?>            <td class="date column-date"><?php echo $row->InputDate; ?></td>        </tr>	<?php	endforeach;	?>    </tbody></table><div class="tablenav bottom">    <div class="alignleft actions">		<?php		$totalPay = get_option('EZD_TotalPayment');		$PageNumInt = 1;		if($totalPay > 0)		{			$PagesNum = $totalPay / 30;			$PageNumInt = intval($PagesNum);			if($PageNumInt < $PagesNum)				$PageNumInt++;		}		$currentPage = 1;		if(isset($_REQUEST['pageid']))			$currentPage = htmlspecialchars(strip_tags(trim($_REQUEST['pageid'])), ENT_QUOTES);		global $wpdb;		$donateTable = $wpdb->prefix . TABLE_DONATE;		$count = $wpdb->get_results("SELECT * FROM `wp_erima_donate`");		?>        <div class="tablenav-pages"><span class="displaying-num"><?php echo count($count); ?> مورد</span>			<?php			for($i = 1 ; $i <= $PageNumInt; $i++)			{				if($i == $currentPage)					echo '<a href="admin.php?page=EZD_Hamian&pageid='. $i .'"  class="first-page disabled">'. $i .'</a>';				else					echo '<a href="admin.php?page=EZD_Hamian&pageid='. $i .'"  class="first-page">'. $i .'</a>';			}			?>        </div>        <br class="clear" />    </div>    </form>    <div id="ajax-response"></div>    <br class="clear" /></div><?php//if (isset($_POST['item_select_submit']) && $_POST['item_select_submit'] == 'انجام'){//    $checkboxes = $_POST['post[]'];//    var_dump($checkboxes);//}