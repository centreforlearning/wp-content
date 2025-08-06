<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sQrt121
 * Date: 9/16/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

class cflShortcodeFlexSlider {

    public function register($shortcodeName) {
		add_shortcode($shortcodeName, array($this, 'handleShortcode'));
    }

	static $addedAlready = false;

	public function handleShortcode( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'id'    => '',
			'class' => '',
			'imgsize' => 'full'
		), $atts ) );

		// get slider id
		if ( empty( $id ) ) {
			$args  = array( 'post_type' => 'coll-flexslider', 'posts_per_page' => 1 );
			$posts = get_posts( $args );
			$id    = $posts[0]->ID;
		}
		// meta
		$color          = '#bdc3c7';

		$uid = rand(1,9999);

		$output = '';

		// start slider
		$output .= '<div    id="slider-' . $uid . '"
                            class="coll-flexslider flexslider coll-bg-slider coll-no-bg coll-arrows-hover coll-arrows-in coll-bullets-always coll-captions-never" >';
		// js
		$output .= '<script type="text/javascript">
                jQuery(document).ready(function ($) {

                    function initSlider(){
                     var _slider = $("#slider-' . $uid . '")

                        _slider.flexslider({
                            slideshow: true,
                            start: function(slider){

                                _slider.find(".flex-control-nav > li > a")
                                    .css("border-color",  \'' . $color . '\')
                                    .hover (
                                        function(){
                                        $(this).css("background-color", "' . $color . '")
                                        },
                                        function(){
                                          $(this).css("background-color", "transparent")
                                        })
		                        _slider.find(".flex-control-nav > li > a.flex-active")
                                    .css("background-color", "' . $color . '")
                                _slider.find(".flex-direction-nav > li > a")
                                    .css("border-color",  \'' . $color . '\')
                                    .css("color",  \'' . $color . '\')
                                    .hover (
                                        function(){
                                        //$(this).css("background", "none")
                                        $(this).css("background-color", "' . $color . '")
                                        },
                                        function(){
                                          $(this).css("background-color", "transparent")
                                        })

                                    _slider.trigger("coll.flexslider.init");
                            },
                            before: function(_slider){
                                _slider.find(".flex-control-nav > li > a.flex-active")
                                    .css("background-color", "transparent")
                            },
                            after: function(_slider){
                                _slider.find(".flex-control-nav > li > a.flex-active")
                                    .css("background-color", "' . $color . '")
                                $(window).trigger("resize");
                            }
                        });
                    }

                    $(window).load(initSlider);
                    $(window).on("coll.shortcodes.update", initSlider);
                });
                </script>';


		// start items
		$output .= '<ul class="slides">';

		$slides = get_field('home_slider_imgs');

		foreach ( $slides as $slide ) {

			// build
			$output .= "<li>";
			$output .= '<img class="img js-coll-lazy"
                                    width="' . $slide['sizes'][$imgsize.'-width'] . '"
                                    height="' . $slide['sizes'][$imgsize.'-height'] . '"
                                    data-coll-src="' . $slide['sizes'][$imgsize] . '"
                                    alt="' . $slide['alt'] . '"/>';
			$output .= '<footer class="flex-caption">';
			if ( ! empty( $slide['caption'] ) ) {
				$output .= '<h3 class="caption">' . $slide['caption'] . '</h3>';
			}
			if ( ! empty( $slide['description'] ) ) {
				$output .= '<div class="description">' . $slide['description'] . '</div>';
			}
			$output .= '</footer>';

			$output .= "</li>";
		}

		$output .= '</ul>'; // end items
		$output .= '</div>'; // end slider

		return $output;
	}
}

$sc = new cflShortcodeFlexSlider();
$sc->register( 'cfl_flexslider' );