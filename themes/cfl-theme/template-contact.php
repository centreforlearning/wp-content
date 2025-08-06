<?php 
/**
 * Template Name: Getting to CFL
 * Description: The template for displaying the map.
 *
 *
 * @package cfl
 */
 
 get_header(); ?>
<?php
$content_columns = ot_get_option('coll_page_sidebar') ? '9' : '12';

wp_register_script('googlemaps', ('//maps.google.com/maps/api/js'), false, null, true);
wp_enqueue_script('googlemaps');

if (have_posts()) :
    while (have_posts()) :
        the_post();
        // page header map
         extract(shortcode_atts(array(
            'id' => 'gmap-' . rand(1, 9999),
            'type' => 'TERRAIN',
            'latitude' => '12.960783',
            'longitude' => '77.309149',
            'map_center' => '12.96, 77.42',
            'zoom' => '11',
            'map_style' => 'normal',
            'width' => '100%',
            'height' => '400px',
            'message' => 'Centre For Learning',
        ), $atts));

		if ($map_center == '') $map_center = $latitude . ',' . $longitude; // Default center is marker

        $output = '';
		$output .= '<section class="background js-coll-page-section coll-page-section">';
		$output .= '<div class="js-coll-parallax coll-section-background">';
        $output .= '<div class="coll-google-map coll-bg-image js-coll-lazy">';
        $output .= '
        <script type="text/javascript">
        jQuery(document).ready(function ($) {
          function initializeGoogleMap() {

                var isMobile = ($("body").hasClass("coll-mobile")) ? true : false;
                var myLatlng = new google.maps.LatLng(' . $latitude . ',' . $longitude . ');
                var mapCenter = new google.maps.LatLng(' . $map_center . ');
                var blrCity = new google.maps.LatLng(12.95, 77.6);
                var boundsPoints = [myLatlng, blrCity];
				var bounds = new google.maps.LatLngBounds();
				for (var i = 0; i < boundsPoints.length; i++) {
				 bounds.extend(boundsPoints[i]);
				}                
                mapCenter = bounds.getCenter();
                ';
                
			$output .= '

              // Create a map object, and include the MapTypeId to add
              // to the map type control.
              var myOptions = {
                center: mapCenter,
                zoom: ' . $zoom . ',
                scrollwheel: false,
                draggable: !isMobile,
                mapTypeId: google.maps.MapTypeId.' . $type . ',
                disableDefaultUI: true,
              };
              var map = new google.maps.Map(document.getElementById("' . $id . '"), myOptions);
              
			  map.setCenter(bounds.getCenter());

              var contentString = "' . $message . '";
              var infowindow = new google.maps.InfoWindow({
                  content: contentString
              });

              var marker = new google.maps.Marker({
                  position: myLatlng
              });

              google.maps.event.addListener(marker, "click", function() {
                  infowindow.open(map,marker);
              });
              
              if (!isMobile) google.maps.event.addListenerOnce(map, "bounds_changed", function(event) {
				map.setZoom(map.getZoom()-1);

				if (this.getZoom() > 15) {
				  this.setZoom(15);
				}
			  });
			  map.fitBounds(bounds);

              marker.setMap(map);

          }
          //$(window).load(initializeGoogleMap);
          initializeGoogleMap();

        });
        </script>';

        $output .= '<div id="' . $id . '" style="width:' . $width . '; height:' . $height . ';" class="gmap" ></div>';
        $output .= '</div>';
		$output .= '</div>';
		$output .= '</section>';
        
        ?>
        <div class="wrapper common coll-single <?php if (has_post_thumbnail()) echo 'coll-parallax' ?>" id="skrollr-body">
        <?php echo $output; ?>
        <section class="title-container js-coll-page-section coll-page-section">
            <div class="row">
                <div class="large-12 columns">
                    <div class="title-wrapper">
                        <h1 class="title-text"><?php echo get_the_title(get_the_ID()); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content-container js-coll-page-section coll-page-section">
            <div class="row">
                <div class="large-9 columns">
                    <div class="copy-container">
                        <div class="content-wrapper">
                            <article class="entry-content">
                                <?php the_content(); ?>
                            </article>
                            <?php
                            $defaults = array(
                                'before' => '<ul class="coll-pagination">',
                                'after' => '</ul>',
                                'link_before' => '<li>',
                                'link_after' => '</li>',
                                'next_or_number' => 'number',
                                'separator' => '',
                                'nextpagelink' => __('Next page'),
                                'previouspagelink' => __('Previous page'),
                                'pagelink' => '%',
                                'echo' => 1
                            );

                            wp_link_pages($defaults);
                            ?>
                        </div>
                    </div>
                </div>
                <!--                end left-->
                    <div class="large-3 columns">
                        <div class="sidebar-container">
							<div class="contact-info">
								<h3>MAILING ADDRESS</h3>
								2, Good Earth Enclave<br />
								Uttarhalli Road, Kengeri<br />
								Bangalore 560060<br />
								India<br />
								<br />
								<h3>CAMPUS</h3>
								Village Varadenahalli<br />
								Magadi Taluka<br />
								Ramanagara District 562 120<br />
								India<br />
								<br />
								<h3>EMAIL</h3>
								<a href="mailto:info@cfl.in">info@cfl.in</a><br />
								<br />
								<h3>PHONE</h3><br />
								+91-080-27705748<br />
								+91-(0)9880833966<br />
							</div>
                        </div>
                    </div>
            </div>
        </section>





    <?php
    endwhile;
endif; ?>
<?php get_footer(); ?>