<?php
/*
	template for layout="table"
*/

global $pmproal_link_arguments;
?>
<table id="pmpro_levels" class="<?php
	if(!empty($template))
		echo "pmpro_advanced_levels-" . esc_attr( $template );
	else
		echo "pmpro_advanced_levels-table";
	if($template === "gantry" || $template === "bootstrap")
		echo " table table-striped table-bordered";
?>">
<thead>
  <tr>
	<?php do_action('pmproal_extra_cols_before_header'); ?>
	<th><?php esc_html_e('Level', 'pmpro-advanced-levels-shortcode');?></th>
	<?php if(!empty($show_price)) { ?>
		<th><?php esc_html_e('Price', 'pmpro-advanced-levels-shortcode');?></th>
	<?php } ?>
	<?php if(!empty($expiration)) { ?>
		<th><?php esc_html_e('Expiration', 'pmpro-advanced-levels-shortcode');?></th>
	<?php } ?>
	<th>&nbsp;</th>
	<?php do_action('pmproal_extra_cols_after_header'); ?>
  </tr>
</thead>
<tbody>
<?php	
	$count = 0;
	foreach($pmpro_levels_filtered as $level)
	{
		$pmproal_link_arguments['level'] = $level->id;
		$current_level = pmpro_hasMembershipLevel( $level->id );

	?>
	<tr id="pmpro_level-<?php echo esc_attr( $level->id ); ?>" class="<?php if($current_level) { echo 'pmpro_level-current '; } if($highlight == $level->id) { echo 'pmpro_level-highlight '; } ?>">
		<?php do_action('pmproal_extra_cols_before_body', $level->id, $template); ?>
		<td>
			<h2><?php echo wp_kses( $level->name, pmproal_allowed_html() ); ?></h2>
			<?php if(!empty($description)) { echo wp_kses_post( wpautop($level->description) ); } ?>
		</td>
		<?php if(!empty($show_price)) { ?>
		<td>
			<?php 
				if($price === 'full')
					echo wp_kses( pmpro_getLevelCost( $level, true, false ), array( 'strong' => array() ) );
				else
					echo wp_kses( pmpro_getLevelCost( $level, false, true ), array( 'strong' => array() ) );
			?>
		</td>
		<?php } ?>
		<?php 
			if(!empty($expiration)) 
			{ 
				?>
				<td>
				<?php 
					$level_expiration = pmpro_getLevelExpiration($level);
					if(empty($level_expiration))
						esc_html_e('Membership Never Expires.', 'pmpro-advanced-levels-shortcode');
					else
						echo wp_kses( $level_expiration, pmproal_allowed_html() );
				?>
				</td>
				<?php 
			} 
		?>
		<td>
		<?php if ( ! pmpro_hasMembershipLevel() ) { ?>
			<a class="<?php
				if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
				elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
				elseif($template === "woothemes") { echo "woo-sc-button custom"; }
				else { echo "pmpro_btn pmpro_btn-select"; }
			?>" href="<?php echo esc_url( add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ) ); ?>"><?php echo esc_html( $checkout_button ); ?></a>
		<?php } elseif ( !$current_level ) { ?>                	
			<a class="<?php
				if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
				elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
				elseif($template === "woothemes") { echo "woo-sc-button custom"; }
				else { echo "pmpro_btn pmpro_btn-select"; }
			?>" href="<?php echo esc_url( add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ) ); ?>"><?php echo esc_html( $checkout_button ); ?></a>
		<?php } elseif($current_level) { ?>      
			
			<?php
				//if it's a one-time-payment level or recurring level that's expiring soon, offer a link to renew	
				$specific_level = pmpro_getSpecificMembershipLevelForUser($current_user->ID, $level->id);										
				if( pmpro_isLevelExpiringSoon( $specific_level) && $specific_level->allow_signups )
				{
				?>
					<a class="<?php
						if($template === "genesis" || $template === "foundation" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-primary"; }
						elseif($template === "woothemes") { echo "woo-sc-button custom"; }
						else { echo "pmpro_btn pmpro_btn-select"; }
					?>" href="<?php echo esc_url( add_query_arg( $pmproal_link_arguments, pmpro_url("checkout", null, "https") ) ); ?>"><?php echo esc_html( $renew_button ); ?></a>
				<?php
				}
				else
				{
				?>
					<a class="<?php
						if($template === "genesis" || $template === "twentyfourteen") { echo "button"; }
						elseif($template === "foundation") { echo "button info"; }
						elseif($template === "gantry" || $template === "bootstrap") { echo "btn btn-info"; }
						elseif($template === "woothemes") { echo "woo-sc-button silver"; }
						else { echo "pmpro_btn disabled"; }
					?>" href="<?php echo esc_url( pmpro_url("account") ); ?>"><?php echo esc_html( $account_button ); ?></a>
				<?php
				}
			?>
			
		<?php } ?>
		</td>
		<?php do_action('pmproal_extra_cols_after_body', $level->id, $template); ?>
	</tr>
	<?php
	}
?>
</tbody>
</table>
