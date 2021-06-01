<?php 

defined('ABSPATH') or die('Access denied!');

if ( $_POST ) {
	
	if ( isset($_POST['EZD_MerchantID']) ) {
		update_option( 'EZD_MerchantID', $_POST['EZD_MerchantID'] );
	}
	
	if ( isset($_POST['EZD_IsOK']) ) {
		update_option( 'EZD_IsOK', $_POST['EZD_IsOK'] );
	}
  
	if ( isset($_POST['EZD_IsError']) ) {
		update_option( 'EZD_IsError', $_POST['EZD_IsError'] );
	}
	
  if ( isset($_POST['EZD_Unit']) ) {
		update_option( 'EZD_Unit', $_POST['EZD_Unit'] );
	}
  
  if ( isset($_POST['EZD_UseCustomStyle']) ) {
		update_option( 'EZD_UseCustomStyle', 'true' );
    
    if ( isset($_POST['EZD_CustomStyle']) )
    {
      update_option( 'EZD_CustomStyle', strip_tags($_POST['EZD_CustomStyle']) );
    }
    
	}
  else
  {
    update_option( 'EZD_UseCustomStyle', 'false' );
  }
  
	echo '<div class="updated" id="message"><p><strong>تنظیمات ذخیره شد</strong>.</p></div>';
	
}
//XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
?>
<h2 id="add-new-user">تنظیمات افزونه حمایت مالی - زرین پال</h2>
<h2 id="add-new-user">جمع تمام پرداخت ها : <?php echo get_option("EZD_TotalAmount"); ?>  تومان</h2>
<form method="post">
  <table class="form-table">
    <tbody>
      <tr class="user-first-name-wrap">
        <th><label for="EZD_MerchantID">کد دروازه پرداخت</label></th>
        <td>
          <input type="text" class="regular-text" value="<?php echo get_option( 'EZD_MerchantID'); ?>" id="EZD_MerchantID" name="EZD_MerchantID">
          <p class="description indicator-hint">XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX</p>
        </td>
      </tr>
      <tr>
        <th><label for="EZD_IsOK">پرداخت صحیح</label></th>
        <td><input type="text" class="regular-text" value="<?php echo get_option( 'EZD_IsOK'); ?>" id="EZD_IsOK" name="EZD_IsOK"></td>
      </tr>
      <tr>
        <th><label for="EZD_IsError">خطا در پرداخت</label></th>
        <td><input type="text" class="regular-text" value="<?php echo get_option( 'EZD_IsError'); ?>" id="EZD_IsError" name="EZD_IsError"></td>
      </tr>
      
      <tr class="user-display-name-wrap">
        <th><label for="EZD_Unit">واحد پول</label></th>
        <td>
          <?php $EZD_Unit = get_option( 'EZD_Unit'); ?>
          <select id="EZD_Unit" name="EZD_Unit">
            <option <?php if($EZD_Unit == 'تومان' ) echo 'selected="selected"' ?>>تومان</option>
            <option <?php if($EZD_Unit == 'ریال' ) echo 'selected="selected"' ?>>ریال</option>
          </select>
        </td>
      </tr>
      
      <tr class="user-display-name-wrap">
        <th>استفاده از استایل سفارشی</th>
        <td>
          <?php $EZD_UseCustomStyle = get_option('EZD_UseCustomStyle') == 'true' ? 'checked="checked"' : ''; ?>
          <input type="checkbox" name="EZD_UseCustomStyle" id="EZD_UseCustomStyle" value="true" <?php echo $EZD_UseCustomStyle ?> /><label for="EZD_UseCustomStyle">استفاده از استایل سفارشی برای فرم</label><br>
        </td>
      </tr>
      
      
      <tr class="user-display-name-wrap" id="EZD_CustomStyleBox" <?php if(get_option('EZD_UseCustomStyle') != 'true') echo 'style="display:none"'; ?>>
        <th>استایل سفارشی</th>
        <td>
          <textarea style="width: 90%;min-height: 400px;direction:ltr;" name="EZD_CustomStyle" id="EZD_CustomStyle"><?php echo get_option('EZD_CustomStyle') ?></textarea><br>
        </td>
      </tr>
      
    </tbody>
  </table>
  <p class="submit"><input type="submit" value="به روز رسانی تنظیمات" class="button button-primary" id="submit" name="submit"></p>
</form>

<script>
  if(typeof jQuery == 'function')
  {
    jQuery("#EZD_UseCustomStyle").change(function(){
      if(jQuery("#EZD_UseCustomStyle").prop('checked') == true)
        jQuery("#EZD_CustomStyleBox").show(500);
      else
        jQuery("#EZD_CustomStyleBox").hide(500);
    });
  }
</script>

