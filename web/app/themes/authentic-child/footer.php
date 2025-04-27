<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Authentic
 */

?>

					<?php do_action( 'csco_main_content_end' ); ?>

				</div><!-- .main-content -->

				<?php do_action( 'csco_main_content_after' ); ?>

			</div><!-- .container -->

			<?php do_action( 'csco_site_content_end' ); ?>

		</div><!-- .site-content -->

		<?php do_action( 'csco_site_content_after' ); ?>

		<?php do_action( 'csco_footer_before' ); ?>

		<footer <?php csco_site_footer_class(); ?>>

			<?php do_action( 'csco_footer_start' ); ?>

			<?php get_template_part( 'template-parts/footer/footer-layout' ); ?>

			<div class="copy-right-footer-bottom cs-container">
				<p><?php echo str_replace('YYYY', date('Y'), get_field('copyright_text','option')); ?></p>
				<?php
				if ( has_nav_menu( 'privacy-menu' ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => 'privacy-menu',
							'menu_class'      => 'nav navbar-nav',
							'container'       => 'nav',
							'container_class' => 'nav navbar-footer navbar-lonely',
							'depth'           => 1,
						)
					);
				}
				?>
			</div>	


			<?php do_action( 'csco_footer_end' ); ?>

		</footer>

		<?php do_action( 'csco_footer_after' ); ?>

	</div><!-- .site-inner -->

	<?php do_action( 'csco_site_end' ); ?>

</div><!-- .site -->

<?php do_action( 'csco_body_end' ); ?>

<?php wp_footer(); ?>
</body>
</html>