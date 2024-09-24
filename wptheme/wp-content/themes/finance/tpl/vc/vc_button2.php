<?php
extract( shortcode_atts( array(
	'link' => '',
	'title' => esc_html__( 'Text on the button', 'finance' ),
	'color' => '',
	'icon' => '',
	'size' => '',
	'style' => '',
	'el_class' => '',
	'align' => ''
), $atts ) );

$class = 'vc_btn';
//parse link
$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );
$a_href = $link['url'];
$a_title = $link['title'];
$a_target = $link['target'];

$class .= ( $color != '' ) ? ( ' vc_btn_' . $color . ' vc_btn-' . $color ) : '';
$class .= ( $size != '' ) ? ( ' vc_btn_' . $size . ' vc_btn-' . $size ) : '';
$class .= ( $style != '' ) ? ' vc_btn_' . $style : '';

$el_class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . $class . $el_class, $this->settings['base'], $atts );
?>
<a class="<?php echo esc_attr( trim( $css_class ) ); ?>" href="<?php echo esc_url( $a_href ) ?>"
	<?php if ( ! empty( $a_title ) ) printf( 'title="%s"', esc_attr( $a_title ) ) ?>
	<?php if ( ! empty( $a_target ) ) printf( 'target="%s"', esc_attr( $a_target ) ) ?>
>
	<?php echo esc_html( $title ) ?>
</a>
<?php echo $this->endBlockComment( 'vc_button' ) . "\n";