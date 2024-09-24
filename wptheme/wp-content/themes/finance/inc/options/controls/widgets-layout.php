<?php
/**
 * WARNING: This file is part of the OptionsPlus library. DO NOT edit
 * this file under any circumstances.
 */

/**
 * This class will be present an widgets layout control
 */
if (class_exists('WP_Customize_Control')) {

	class WidgetsLayout extends WP_Customize_Control {
		/**
		 * The control type
		 * 
		 * @var  string
		 */
		public $type = 'widgets-layout';

		public $max = 4;
		
		/**
		 * Render the control markup
		 * 
		 * @return  void
		 */
		public function render() {
			$id    = 'options-control-' . $this->id;
			$class = 'options-control options-control-' . $this->type;

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
		public function render_content() {
			$name           = '_options-widgets-layout-' . $this->id;
			$values         = $this->value();

			if ( ! is_array( $values ) )
				$values = json_decode(str_replace('&quot;', '"', $values), true );

			$columns_count  = $values['active'];
			$columns_layout = themesflat_customize_default_options2('footer_widget_areas3_layout');
			$max            = $this->max;
			?>
			<div class="options-control-inputs">
				<div class="columns-count">
					<?php foreach ( range(0, $max - 1) as $index ): ?>
					<label>
						<input type="radio" name="<?php themesflat_esc_attr( $name ) ?>" value="<?php themesflat_esc_attr( $index ) ?>" <?php checked( $columns_count, $index ) ?> />
						<span><?php echo esc_attr( $index + 1 ) ?></span>
					</label>
					<?php endforeach ?>
				</div>

				<div class="options-control-layouts">
					<?php foreach ( range(0, $max - 1) as $index ):
						if ( ! isset( $columns_layout[$index] ) || ! is_array( $columns_layout[$index] ) )
							continue;

						$columns = $columns_layout[$index];
						?>
						<div id="<?php themesflat_esc_attr( $name . '-' . $index ) ?>" class="<?php if ( $index == $columns_count ) echo esc_attr( 'active' ) ?>">
							<div class="widgetslayout-row">
								<?php foreach ( $columns as $width ): ?>
								<div class="widgetslayout-column" data-width="<?php themesflat_esc_attr( $width ) ?>">
									<span><?php themesflat_esc_html( $width ) ?></span>
								</div>
								<?php endforeach ?>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
			<input type="hidden" name="<?php themesflat_esc_attr($name);?>" <?php $this->link(); ?> value="<?php themesflat_esc_attr( json_encode( $this->value() ) ) ?>" />
			<?php
		}
	}
}
