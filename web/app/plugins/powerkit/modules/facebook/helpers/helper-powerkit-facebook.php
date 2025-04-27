<?php
/**
 * Helpers Facebook
 *
 * @package    Powerkit
 * @subpackage Modules/Helper
 */

/**
 * Facebook load sdk.
 */
function powerkit_facebook_load_sdk() {
	?>
		<div id="fb-root"></div>
		<script>( function( d, s, id ) {
			var js, fjs = d.getElementsByTagName( s )[0];
			if ( d.getElementById( id ) ) return;
			js = d.createElement( s ); js.id = id;
			js.src = "//connect.facebook.net/<?php echo esc_html( powerkit_get_locale() ); ?>/sdk.js#xfbml=1&version=v2.5&appId=<?php echo esc_html( powerkit_connect( 'facebook_app_id' ) ); ?>";
			fjs.parentNode.insertBefore( js, fjs );
		}( document, 'script', 'facebook-jssdk' ) );</script>
	<?php
}
