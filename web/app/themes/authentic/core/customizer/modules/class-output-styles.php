<?php
/**
 * Theme Customizer Output Styles
 *
 * @package Authentic
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CSCO_Customizer_Output_Styles' ) ) {
	/**
	 * Class Theme Customizer Output
	 */
	class CSCO_Customizer_Output_Styles {

		/**
		 * The class constructor
		 */
		public function __construct() {
			add_action( 'wp_head', array( $this, 'enqueue_output_styles' ), 999 );
			add_action( 'admin_head', array( $this, 'enqueue_output_styles' ), 999 );
		}

		/**
		 * Gets all our styles and returns them as a string.
		 *
		 * @param mixed $scheme Current scheme.
		 */
		public function get_output_styles( $scheme = null ) {

			$output_styles = __return_empty_string();

			// Get an array of all our fields.
			$fields = CSCO_Customizer::$fields;

			// Check if we need to exit early.
			if ( empty( $fields ) || ! is_array( $fields ) ) {
				return;
			}

			// Initially we're going to format our styles as an array.
			// This is going to make processing them a lot easier
			// and make sure there are no duplicate styles etc.
			$css = array();

			// Start parsing our fields.
			foreach ( $fields as $field ) {
				// No need to process fields without an output, or an improperly-formatted output.
				if ( ! isset( $field['output'] ) || empty( $field['output'] ) || ! is_array( $field['output'] ) ) {
					continue;
				}

				if ( isset( $field['support_dark'] ) && $field['support_dark'] ) {
					if ( 'dark' === $field['support_dark'] && 'dark' !== $scheme ) {
						continue;
					}
					if ( true === $field['support_dark'] && 'default' !== $scheme ) {
						continue;
					}
				}

				// Get the value of this field.
				$value = CSCO_Customizer_Helper::get_value( $field['settings'] );

				// Check active callback.
				if ( ! CSCO_Customizer_Helper::active_callback( $field ) ) {
					continue;
				}

				// Start parsing the output arguments of the field.
				foreach ( $field['output'] as $output ) {
					$skip = false;

					// No need to proceed this if the current value is the same as in the "exclude" value.
					if ( isset( $output['exclude'] ) && is_array( $output['exclude'] ) ) {
						foreach ( $output['exclude'] as $exclude ) {
							if ( is_array( $value ) ) {
								if ( is_array( $exclude ) ) {
									$diff1 = array_diff( $value, $exclude );
									$diff2 = array_diff( $exclude, $value );

									if ( empty( $diff1 ) && empty( $diff2 ) ) {
										$skip = true;
									}
								}
								// If 'choice' is defined check for sub-values too.
								if ( isset( $output['choice'] ) && isset( $value[ $output['choice'] ] ) && $exclude == $value[ $output['choice'] ] ) {
									$skip = true;
								}
							}
							if ( $skip ) {
								continue;
							}

							// Skip if value is defined as excluded.
							if ( $exclude === $value || ( '' === $exclude && empty( $value ) ) ) {
								$skip = true;
							}
						}
					}
					if ( $skip ) {
						continue;
					}

					if ( is_admin() && ! is_customize_preview() ) {
						// Check if this is an admin style.
						if ( ! isset( $output['context'] ) || ! in_array( 'editor', $output['context'], true ) ) {
							continue;
						}
					} elseif ( isset( $output['context'] ) && ! in_array( 'front', $output['context'], true ) ) {
						// Check if this is a frontend style.
						continue;
					}

					$output = wp_parse_args(
						$output, array(
							'element'       => '',
							'property'      => '',
							'media_query'   => 'global',
							'prefix'        => '',
							'units'         => '',
							'suffix'        => '',
							'value_pattern' => '$',
							'choice'        => '',
							'convert'       => '',
						)
					);
					// If element is an array, convert it to a string.
					if ( is_array( $output['element'] ) ) {
						$output['element'] = implode( ',', $output['element'] );
					}
					// Simple fields.
					if ( ! is_array( $value ) ) {
						$value_pattern = str_replace( '$', $value, $output['value_pattern'] );

						if ( 'rgb' === $output['convert'] ) {
							$value_pattern = csco_hex2rgba( $value_pattern, false );
						}

						if ( ! empty( $output['element'] ) && ! empty( $output['property'] ) ) {
							$css[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $value_pattern . $output['units'] . $output['suffix'];
						}
					} else {
						if ( 'typography' === $field['type'] ) {

							$value = CSCO_Customizer_Helper::typography_sanitize( $value );

							$properties = array(
								'font-family',
								'font-size',
								'variant',
								'font-weight',
								'font-style',
								'letter-spacing',
								'word-spacing',
								'line-height',
								'text-align',
								'text-transform',
								'text-decoration',
								'color',
							);

							foreach ( $properties as $property ) {
								// Early exit if the value is not in the defaults.
								if ( ! isset( $field['default'][ $property ] ) ) {
									continue;
								}

								// Early exit if the value is not saved in the values.
								if ( ! isset( $value[ $property ] ) || ! $value[ $property ] ) {
									continue;
								}

								// Take care of variants.
								if ( 'variant' === $property && isset( $value['variant'] ) && ! empty( $value['variant'] ) ) {

									// Get the font_weight.
									$font_weight = str_replace( 'italic', '', $value['variant'] );
									$font_weight = in_array( $font_weight, array( '', 'regular' ), true ) ? '400' : $font_weight;

									$css[ $output['media_query'] ][ $output['element'] ]['font-weight'] = $font_weight;

									// Is this italic?
									$is_italic = ( false !== strpos( $value['variant'], 'italic' ) );
									if ( $is_italic ) {
										$css[ $output['media_query'] ][ $output['element'] ]['font-style'] = 'italic';
									}
									continue;
								}

								$css[ $output['media_query'] ][ $output['element'] ][ $property ] = $output['prefix'] . $value[ $property ] . $output['suffix'];
							}



						} elseif ( 'multicolor' === $field['type'] ) {

							if ( ! empty( $output['element'] ) && ! empty( $output['property'] ) && ! empty( $output['choice'] ) ) {
								$css[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $value[ $output['choice'] ] . $output['units'] . $output['suffix'];
							}
						} else {

							if ( 'group-background' === $field['type'] ) {
								foreach ( $value as $key => $subvalue ) {
									if ( 'background-image' === $key ) {
										$value[ $key ] = sprintf( 'url("%s")', set_url_scheme( $subvalue ) );
									}
								}
							}

							foreach ( $value as $key => $subvalue ) {
								$property = $key;

								if ( false !== strpos( $output['property'], '%%' ) ) {

									$property = str_replace( '%%', $key, $output['property'] );

								} elseif ( ! empty( $output['property'] ) ) {

									$output['property'] = $output['property'] . '-' . $key;
								}

								if ( 'background-image' === $output['property'] && false === strpos( $subvalue, 'url(' ) ) {
									$subvalue = sprintf( 'url("%s")', set_url_scheme( $subvalue ) );
								}
								if ( $subvalue ) {
									$css[ $output['media_query'] ][ $output['element'] ][ $property ] = $subvalue;
								}
							}
						}
					}
				}
			}

			// Process the array of CSS properties and produce the final CSS.
			if ( ! is_array( $css ) || empty( $css ) ) {
				return null;
			}

			foreach ( $css as $media_query => $styles ) {
				$output_styles .= ( 'global' !== $media_query ) ? $media_query . '{' : '';

				foreach ( $styles as $style => $style_array ) {
					$css_for_style = '';

					foreach ( $style_array as $property => $value ) {
						if ( is_string( $value ) && '' !== $value ) {
							$css_for_style .= sprintf( '%s:%s;', $property, $value );
						} elseif ( is_array( $value ) ) {
							foreach ( $value as $subvalue ) {
								if ( is_string( $subvalue ) && '' !== $subvalue ) {
									$css_for_style .= sprintf( '%s:%s;', $property, $subvalue );
								}
							}
						}
						$value = ( is_string( $value ) ) ? $value : '';
					}
					if ( '' !== $css_for_style ) {
						$output_styles .= $style . sprintf( '{%s}', $css_for_style );
					}
				}

				$output_styles .= ( 'global' !== $media_query ) ? '}' : '';
			}

			$output_styles = apply_filters( 'csco_customizer_output_styles', $output_styles );

			return $output_styles;
		}

		/**
		 * Enqueue output styles.
		 *
		 * @param string $page Current page.
		 */
		public function enqueue_output_styles( $page ) {
			if ( 'admin_enqueue_scripts' === current_filter() ) {
				if ( 'post.php' !== $page && 'post-new.php' !== $page ) {
					return;
				}
				if ( is_customize_preview() ) {
					return;
				}
			}

			if ( ! csco_live_get_theme_mod( 'color_enable_dark_mode', false ) ) {
				wp_register_style( 'csco-customizer-output-default-styles', false, array(), csco_get_theme_data( 'Version' ) );

				wp_enqueue_style( 'csco-customizer-output-default-styles' );

				wp_add_inline_style( 'csco-customizer-output-default-styles', $this->get_output_styles( 'default' ) );
			} else {

				$schemes = array(
					'default',
					'dark',
				);

				$data = csco_site_scheme_data();

				foreach ( $schemes as $scheme ) {
					$media = $scheme === $data['site_scheme'] ? '' : ' media="max-width: 1px"';

					?>
					<style id="csco-customizer-output-<?php echo esc_attr( $scheme ); ?>-styles" <?php echo wp_kses( $media, 'post' ); ?>>
						<?php call_user_func( 'printf', '%s', $this->get_output_styles( $scheme ) ); ?>
					</style>
					<?php
				}
			}
		}
	}

	new CSCO_Customizer_Output_Styles();
}
