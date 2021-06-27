<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php
global $wpdb;
$erimaDonateTable = $wpdb->prefix . TABLE_DONATE;
$all_donates = $wpdb->get_results( "SELECT * FROM $erimaDonateTable ORDER BY DonateID DESC ");
var_dump($all_donates);
?>

<div class="container">
	<div class="row">
		<div class="table-responsive">

			<table class="table">
				<thead class="thead-light">
				<tr>
					<th>Firstname</th>
					<th>Lastname</th>
					<th>Email</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>John</td>
					<td>Doe</td>
					<td>john@example.com</td>
				</tr>
				<tr>
					<td>Mary</td>
					<td>Moe</td>
					<td>mary@example.com</td>
				</tr>
				<tr>
					<td>July</td>
					<td>Dooley</td>
					<td>july@example.com</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
