<?php
/**
 * WARNING: This file is part of the OptionsPlus library. DO NOT edit
 * this file under any circumstances.
 */

/**
 * This class will be present an colorpicker control
 */
if (class_exists('WP_Customize_Control')) {

	class Backgrounds extends WP_Customize_Control {
		/**
		 * The control type
		 * 
		 * @var  string
		 */
		public $type = 'backgrounds';

		/**
		 * List of predefined background patterns
		 * 
		 * @var  array
		 */
		public $patterns;


		/**
		 * Enqueue assets for this control
		 * 
		 * @return  void
		 */
		public function enqueue() {
			wp_enqueue_script( 'wp-plupload' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		public function render() {
			$id    = 'options-control-' . $this->id;
			$class = 'options-control options-control-background';

			if ( $this->value() )
				$this->class = 'active';

			if ( ! empty( $this->class ) )
				$class .= " {$this->class}";

			if ( empty( $this->label ) )
				$class .= ' no-label';

			?><li id="<?php themesflat_esc_attr( $id ); ?>" class="<?php themesflat_esc_attr( $class ) ?>">
				<?php $this->render_content(); ?>
			</li><?php
		}
		
		/**
		 * Render the control markup
		 * 
		 * @return  void
		 */
		public function render_content() {
			$__patterns = themes_predefined_background_patterns();
			$name = '_options-control-background-' . $this->id;
			$values = $this->value();
			$default = array(
				'type'     => 'none',
				'pattern'  => 'none',
				'color'    => '#fff',
				'image'    => '',
				'repeat'   => 'repeat',
				'position' => 'top-left',
				'style'    => 'scroll'
			);

			if ( ! is_array( $values ) )
				$values = $default;
			else
				$values = array_merge( $default, $values );
			?>

			<div class="options-control-inputs">
				<div class="background-color">
					<div class="options-control-color-picker">
						<div class="options-control-inputs">
							<input type="text" class='flat-color-picker wp-color-picker' id="<?php themesflat_esc_attr( $name ) ?>-color" name="<?php themesflat_esc_attr($name);?>[color]" data-default-color="<?php themesflat_esc_attr( $values['color'] ) ?>" />
						</div>
					</div>
				</div>

				<div class="background-type">
					<label>
						<input type="radio" name="<?php themesflat_esc_attr($name);?>][type]" value="none" <?php checked( $values['type'], 'none' ) ?> />
						<span><?php esc_html_e( 'None', 'finance' ) ?></span>
					</label>
					<label>
						<input type="radio" name="<?php themesflat_esc_attr($name);?>][type]" value="patterns" <?php checked( $values['type'], 'patterns' ) ?> />
						<span><?php esc_html_e( 'Patterns', 'finance' ) ?></span>
					</label>
					<label>
						<input type="radio" name="<?php themesflat_esc_attr($name);?>][type]" value="custom" <?php checked( $values['type'], 'custom' ) ?> />
						<span><?php esc_html_e( 'Custom', 'finance' ) ?></span>
					</label>
				</div>
				
				<div class="background-patterns">
					<label>
						<input type="radio" value="none" name="<?php themesflat_esc_attr($name);?>][pattern]" <?php checked( $values['pattern'], 'none' ) ?> />
						<span> </span>
					</label>

					<?php foreach ( $__patterns as $id => $url ): ?>
					<label>
						<input type="radio" value="<?php themesflat_esc_attr( $id ) ?>" name="<?php themesflat_esc_attr($name);?>][pattern]" <?php checked( $values['pattern'], $id ) ?> /> 
						<span style="background: url(<?php themesflat_esc_attr( $url ) ?>)"> </span>
					</label>
					<?php endforeach  ?>
				</div>

				<div class="background-custom">
					<div class="options-control-media-picker background-image">
						<div class="options-control-title"><?php esc_html_e( 'Background Image', 'finance' ) ?></div>
						<div class="options-control-inputs">
							<div class="upload-dropzone">
								<span class="upload-message">
									<?php esc_html_e( 'Drop a file here or', 'finance' ) ?>
									<a href="#" class="browse-media"><?php esc_html_e( 'select a file', 'finance' ) ?></a>
									<a href="#" class="upload"></a>
								</span>
								<input type="hidden" data-property="id"/>
								<input type="hidden" data-property="thumbnail"/>
								<span class="upload-preview"></span>
							</div>
							<a href="#" class="button remove"><?php esc_html_e( 'Remove', 'finance' ) ?></a>
						</div>
						<input type="hidden" name="<?php themesflat_esc_attr($name);?>][image]" value="<?php themesflat_esc_attr( $values['image'] ) ?>" />
					</div>

					<div class="options-control-radio-images background-repeat">
						<div class="options-control-title"><?php esc_html_e( 'Background Repeat', 'finance' ) ?></div>
						<div class="options-control-inputs">
							<label class="background-no-repeat">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][repeat]" value="no-repeat" <?php checked( $values['repeat'], 'no-repeat' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'No Repeat', 'finance' ) ?>"><?php esc_html_e( 'No Repeat', 'finance' ) ?></span>
							</label>
							<label class="background-repeat-x">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][repeat]" value="repeat-x" <?php checked( $values['repeat'], 'repeat-x' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Repeat X', 'finance' ) ?>"><?php esc_html_e( 'Repeat X', 'finance' ) ?></span>
							</label>
							<label class="background-repeat-y">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][repeat]" value="repeat-y" <?php checked( $values['repeat'], 'repeat-y' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Repeat Y', 'finance' ) ?>"><?php esc_html_e( 'Repeat Y', 'finance' ) ?></span>
							</label>
							<label class="background-repeat-all">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][repeat]" value="repeat" <?php checked( $values['repeat'], 'repeat' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Repeat All', 'finance' ) ?>"><?php esc_html_e( 'Repeat All', 'finance' ) ?></span>
							</label>
						</div>
					</div>

					<div class="options-control-radio-images background-position">
						<div class="options-control-title"><?php esc_html_e( 'Background Position', 'finance' ) ?></div>
						<div class="options-control-inputs">
							<label class="background-top-left">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="top-left" <?php checked( $values['position'], 'top-left' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Top Left', 'finance' ) ?>"><?php esc_html_e( 'Top Left', 'finance' ) ?></span>
							</label>
							<label class="background-top-center">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="top-center" <?php checked( $values['position'], 'top-center' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Top Center', 'finance' ) ?>"><?php esc_html_e( 'Top Center', 'finance' ) ?></span>
							</label>
							<label class="background-top-right">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="top-right" <?php checked( $values['position'], 'top-right' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Top Right', 'finance' ) ?>"><?php esc_html_e( 'Top Right', 'finance' ) ?></span>
							</label>

							<label class="background-middle-left">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="center-left" <?php checked( $values['position'], 'center-left' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Middle Left', 'finance' ) ?>"><?php esc_html_e( 'Middle Left', 'finance' ) ?></span>
							</label>
							<label class="background-middle-center">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="center-center" <?php checked( $values['position'], 'center-center' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Middle Center', 'finance' ) ?>"><?php esc_html_e( 'Middle Center', 'finance' ) ?></span>
							</label>
							<label class="background-middle-right">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="center-right" <?php checked( $values['position'], 'center-right' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Middle Right', 'finance' ) ?>"><?php esc_html_e( 'Middle Right', 'finance' ) ?></span>
							</label>

							<label class="background-bottom-left">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="bottom-left" <?php checked( $values['position'], 'bottom-left' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Bottom Left', 'finance' ) ?>"><?php esc_html_e( 'Bottom Left', 'finance' ) ?></span>
							</label>
							<label class="background-bottom-center">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="bottom-center" <?php checked( $values['position'], 'bottom-center' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Bottom Center', 'finance' ) ?>"><?php esc_html_e( 'Bottom Center', 'finance' ) ?></span>
							</label>
							<label class="background-bottom-right">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][position]" value="bottom-right" <?php checked( $values['position'], 'bottom-right' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Bottom Right', 'finance' ) ?>"><?php esc_html_e( 'Bottom Right', 'finance' ) ?></span>
							</label>
						</div>
					</div>

					<div class="options-control-radio-images background-style">
						<div class="options-control-title"><?php esc_html_e( 'Background Style', 'finance' ) ?></div>
						<div class="options-control-inputs">
							<label class="background-fixed">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][style]" value="fixed" <?php checked( $values['style'], 'fixed' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Fixed', 'finance' ) ?>"><?php esc_html_e( 'Fixed', 'finance' ) ?></span>
							</label>
							<label class="background-scroll">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][style]" value="scroll" <?php checked( $values['style'], 'scroll' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Scroll', 'finance' ) ?>"><?php esc_html_e( 'Scroll', 'finance' ) ?></span>
							</label>
							<label class="background-cover">
								<input type="radio" name="<?php themesflat_esc_attr($name);?>][style]" value="cover" <?php checked( $values['style'], 'cover' ) ?> />
								<span data-title="<?php themesflat_esc_attr( 'Cover', 'finance' ) ?>"><?php esc_html_e( 'Cover', 'finance' ) ?></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="background-value" name="<?php themesflat_esc_attr($name);?>" <?php $this->link();?>  value="<?php themesflat_esc_attr( json_encode( $this->value() ) ) ?>" />
			<?php
		}
	}
}
