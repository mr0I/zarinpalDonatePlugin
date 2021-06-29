<?php defined('ABSPATH') or die('&lt;h3&gt;Access denied!'); ?><?phpglobal $wpdb;$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY DonateID DESC ");$ok_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ORDER BY DonateID DESC ");$paid_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE paymentStatus='Paid' ORDER BY DonateID DESC ");?>    <div class="wrap" xmlns="http://www.w3.org/1999/html">        <div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2>حامیان مالی</h2>        <form id="posts-filter" action="<?php echo EZD_GetCallBackURL(); ?>" method="post">            <p class="search-box">                <label class="screen-reader-text" for="post-search-input">جست‌وجوی نویسنده:</label>                <input type="search" id="post-search-input" name="searchbyname" value="" />                <button type="submit" id="search-submit" class="btn btn-primary mb-2" style="cursor: pointer;">جست‌وجوی نویسنده</button>            </p>            <input type="hidden" id="_wpnonce" name="_wpnonce" value="8aa9aa1697" /><input type="hidden" name="_wp_http_referer" value="/Project/wp-admin/edit.php" />            <input type="hidden" name="mode" value="list" />        </form>    </div>    <form class="form-inline">        <input type="hidden" name="donate_select_nonce" id="donate_select_nonce" value="<?php echo wp_create_nonce('donate-select-nonce'); ?>" >        <div class="form-group mx-sm-3 mb-2" style="display: flex;justify-content: space-between">            <div class="form-group-item">                <ul class="subsubsub">                    <li class="all"><a href="admin.php?page=EZD_Hamian&pageid=1" class="<?php if($_GET['sort_by']=='') echo 'current'; ?>" aria-current="page">همه <span class="count">(<?= count($all_donates); ?>)</span></a> |</li>                    <li class="completed"><a href="admin.php?page=EZD_Hamian&sort_by=competed&pageid=1" class="<?php if($_GET['sort_by']=='competed') echo 'current'; ?>">تکمیل شده <span class="count">(<?= count($ok_donates) ?>)</span></a> |</li>                    <li class="not_completed"><a href="admin.php?page=EZD_Hamian&sort_by=not_competed&pageid=1" class="<?php if($_GET['sort_by']=='not_competed') echo 'current'; ?>">تکمیل نشده <span class="count">(<?= count($all_donates) - count($ok_donates) ?>)</span></a> |</li>                    <li class="paid"><a href="admin.php?page=EZD_Hamian&sort_by=paid&pageid=1" class="<?php if($_GET['sort_by']=='paid') echo 'current'; ?>">پرداخت شده <span class="count">(<?= count($paid_donates) ?>)</span></a> |</li>                    <li class="not_paid"><a href="admin.php?page=EZD_Hamian&sort_by=not_paid&pageid=1" class="<?php if($_GET['sort_by']=='not_paid') echo 'current'; ?>">پرداخت نشده</a> (<?= (count($all_donates) - count($paid_donates)) ?>) </li>                </ul>            </div>        </div>    </form>    <form class="form-inline">        <input type="hidden" name="donate_select_nonce" id="donate_select_nonce" value="<?php echo wp_create_nonce('donate-select-nonce'); ?>" >        <div class="form-group mx-sm-3 mb-2" style="display: flex;justify-content: space-between">            <div class="form-group-item">                <select class="form-control" id="donate_select" >                    <option value="0">انتخاب</option>                    <option value="1">پرداخت شده</option>                </select>                <button type="button" class="btn btn-primary mb-2" style="cursor: pointer;" id="donate_select_submit" name="item_select_submit" value="انجام">انجام</button>            </div>        </div>    </form>    <table class="wp-list-table widefat posts" cellspacing="0">        <thead>        <tr>            <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">گزینش همه</label><input id="cb-select-all-1" type="checkbox" /></th>            <th scope='col' id='title' class='manage-column column-title'  style="">                <span>نام و نام خانوادگی</span><span class="sorting-indicator"></span>            </th>            <th scope='col' id='author' class='manage-column column-author'  style="">مبلغ (تومان)</th>            <th scope='col' id='categories' class='manage-column column-categories'  style="">موبایل</th>            <th scope='col' id='tags' class='manage-column column-tags'  style="">ایمیل</th>            <th scope='col' id='comments' class='manage-column column-tags'  style="">توضیحات</th>            <th scope='col' id='payment_status' class='manage-column column-tags'  style="">وضعیت تسویه حساب</th>            <th scope='col' id='author' class='manage-column column-tags'  style="">نویسنده</th>            <th scope='col' id='date' class='manage-column column-date'  style=""><span>تاریخ</span><span class="sorting-indicator"></span></th>        </tr>        </thead>        <tfoot>        <tr>            <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">گزینش همه</label><input id="cb-select-all-1" type="checkbox" /></th>            <th scope='col' id='title' class='manage-column column-title'  style="">                <span>نام و نام خانوادگی</span><span class="sorting-indicator"></span>            </th>            <th scope='col' id='author' class='manage-column column-author'  style="">مبلغ (تومان)</th>            <th scope='col' id='categories' class='manage-column column-categories'  style="">موبایل</th>            <th scope='col' id='tags' class='manage-column column-tags'  style="">ایمیل</th>            <th scope='col' id='comments' class='manage-column column-tags'  style="">توضیحات</th>            <th scope='col' id='payment_status' class='manage-column column-tags'  style="">وضعیت تسویه حساب</th>            <th scope='col' id='author' class='manage-column column-tags'  style="">نویسنده</th>            <th scope='col' id='date' class='manage-column column-date'  style=""><span>تاریخ</span><span class="sorting-indicator"></span></th>        </tr>        </tfoot>        <tbody id="the-list">		<?php		//////////// Page ////////////		$LIMIT = '';		if(isset($_GET['pageid']))		{			$page = htmlspecialchars(strip_tags(trim($_GET['pageid'])), ENT_QUOTES);			$lim = getenv('PAGINATE_NUM');			$offset = --$page * (getenv('PAGINATE_NUM'));			$LIMIT = " LIMIT $lim OFFSET $offset";		}		if(isset($_REQUEST['searchbyname']) && $_REQUEST['searchbyname'] != '')		{			$SearchName = htmlspecialchars(strip_tags(trim($_REQUEST['searchbyname'])), ENT_QUOTES);			$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` WHERE `Name` LIKE '%$SearchName%' ORDER BY DonateID DESC ". $LIMIT);			$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable  ");		}        elseif (isset($_GET['sort_by'])){			$sort = $_GET['sort_by'];			switch ($sort){				case 'competed';					$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` WHERE Status='OK' ORDER BY DonateID DESC ". $LIMIT);					$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status='OK' ");					break;				case 'not_competed';					$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` WHERE Status<>'OK' ORDER BY DonateID DESC ". $LIMIT);					$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE Status<>'OK' ");					break;				case 'paid';					$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` WHERE paymentStatus='Paid' ORDER BY DonateID DESC ". $LIMIT);					$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE paymentStatus='Paid' ");					break;				case 'not_paid';					$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` WHERE paymentStatus<>'Paid' ORDER BY DonateID DESC ". $LIMIT);					$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable WHERE paymentStatus<>'Paid' ");					break;				default:					$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` ORDER BY DonateID DESC ". $LIMIT);					$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ");			}		}		else		{			$result = $wpdb->get_results( "SELECT * FROM `$erimaDonateTable` ORDER BY DonateID DESC ".  $LIMIT);			$total = $wpdb->get_results( "SELECT * FROM $erimaDonateTable  ");		}		foreach($result as $row) :			?>            <tr id="post-109" style="<?php if($row->Status == 'OK') echo 'background-color: #cfc'; ?> <?php if($row->Status != 'OK') echo 'background-color: #FBE9E7'; ?>" class="post-109 type-post status-draft format-standard hentry category-news alternate iedit author-self" valign="top">                <th scope="row" class="check-column">                    <label class="screen-reader-text" for="cb-select-109">گزینش رکورد</label>                    <input id="cb-select-109" type="checkbox" name="chkDonates" value="<?= $row->DonateID ?>" />                    <div class="locked-indicator"></div>                </th>                <td class="post-title page-title column-title"><strong><?php echo $row->Name; ?></strong> <small><?php echo $row->Status; ?></small>                    <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>                </td>                <td class="author column-author digits"><?php echo $row->AmountTomaan; ?></td>                <td class="categories column-categories"><?php echo $row->Mobile; ?></td>                <td class="tags column-tags"><?php echo $row->Email; ?></td>                <td class="tags column-tags"><?php echo $row->Description; ?></td>				<?php				if ($row->paymentStatus == 'Not Paid') {					?> <td class="tags column-tags" style="color: red">پرداخت نشده</td> <?php				} else {					?> <td class="tags column-tags" style="color: green">پرداخت شده</td> <?php				}				?>                <td class="author column-author" style="display: flex; justify-content: flex-start; align-items: center;">                    <?php echo $row->Author; ?>                    <figure>                        <img src="http://www.gravatar.com/avatar/<?= md5($row->Email); ?>?rating=PG&size=24&size=50&d=identicon"                             style="width: 30px;height: auto;" alt="تصویر حامی">                    </figure>                </td>                <td class="date column-date"><?php echo $row->InputDate; ?></td>            </tr>		<?php		endforeach;		?>        </tbody>    </table>    <div class="tablenav bottom">        <div class="alignright actions paginate_btns">			<?php			$totalPay = count($total);			$PageNumInt = 1;			if($totalPay > 0)			{				$PagesNum = $totalPay / getenv('PAGINATE_NUM');				$PageNumInt = intval($PagesNum);				if($PageNumInt < $PagesNum)					$PageNumInt++;			}			$currentPage = 1;			if(isset($_REQUEST['pageid']))				$currentPage = htmlspecialchars(strip_tags(trim($_REQUEST['pageid'])), ENT_QUOTES);			?>			<?php			if (!isset($_GET['sort_by']))  $_GET['sort_by'] = '';			for($i = 1 ; $i <= $PageNumInt; $i++)			{				if($i == $currentPage)					echo '<a href="admin.php?page=EZD_Hamian&pageid='. $i .' &sort_by='.$_GET['sort_by'].' "  class="first-page active">'. $i .'</a>';				else					echo '<a href="admin.php?page=EZD_Hamian&pageid='. $i . ' &sort_by='.$_GET['sort_by'].' "  class="first-page">'. $i .'</a>';			}			?>        </div>        <br class="clear" />    </div>    </form>    <div id="ajax-response"></div>    <br class="clear" />    </div><?php