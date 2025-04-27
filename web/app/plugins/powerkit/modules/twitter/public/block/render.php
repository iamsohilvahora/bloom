<?php
/**
 * Twitter block template
 *
 * @var        $attributes - block attributes
 * @var        $options - layout options
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    PowerKit
 * @subpackage PowerKit/templates
 */

$params = array(
	'title'       => esc_html__( 'Twitter Feed', 'powerkit' ),
	'username'    => $attributes['username'],
	'number'      => $attributes['number'],
	'header'      => $attributes['showHeader'],
	'button'      => $attributes['showFollowButton'],
	'replies'     => $attributes['showReplies'],
	'retweets'    => $attributes['showRetweets'],
	'template'    => 'default',
	'is_block'    => true,
	'block_attrs' => $attributes,
);

echo '<div class="' . esc_attr( $attributes['className'] ) . '" ' . ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ) . '>';

// Instagram output.
if ( $attributes['username'] ) {
	powerkit_twitter_get_recent( $params, 'powerkit_twitter_block_cache' );
} else {
	powerkit_alert_warning( 'Twitter data is not set, please check the User Name.' );
}

echo '</div>';
