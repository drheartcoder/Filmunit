<?php
/**
 * WARNING: This file is part of the OptionsPlus library. DO NOT edit
 * this file under any circumstances.
 */

/**
 * This class will be present an switch control
 */
if (class_exists('WP_Customize_Control')) {

	class Switcher extends WP_Customize_Control {
		/**
		 * The control type
		 * 
		 * @var  string
		 */
		public $type = 'switcher';

		/**
		 * Render the control
		 * 
		 * @return  string
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
		
		/**
		 * Render the control markup
		 * 
		 * @return  void
		 */
		public function render_content() {			
			$name = '_options-switcher-' . $this->id;
			?>
			<label id="<?php themesflat_esc_attr( $name ) ?>">
				<?php if ( ! empty( $this->label ) ): ?>
					<span class="options-control-title"><?php themesflat_esc_html( $this->label ) ?></span>
				<?php endif ?>

				<input type="checkbox" value="true" name="<?php themesflat_esc_attr($name);?>" <?php $this->link();  checked( $this->value() ) ?> />
				<span class="options-control-indicator">
					<span></span>
				</span>
			</label>
			<?php
		}
	}
}