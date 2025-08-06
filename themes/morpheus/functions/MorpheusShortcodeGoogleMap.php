<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sQrt121
 * Date: 9/16/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('MorpheusShortCodeScriptLoader.php');

class MorpheusShortcodeGoogleMap extends MorpheusShortCodeScriptLoader
{

    static $addedAlready = false;

    public function handleShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'id' => 'gmap-' . rand(1, 9999),
            'type' => 'ROADMAP',
            'latitude' => '40.7039419',
            'longitude' => '-74.0112864',
            'map_center' => '',
            'zoom' => '17',
            'map_style' => 'normal',
            'width' => '100%',
            'height' => '400px',
            'hue' => '#004cff',
            'saturation' => '0',
            'message' => 'Your Marker Message Here',
            'class' => '',
        ), $atts));

		if ($map_center == '') $map_center = $latitude . ',' . $longitude; // Default center is marker

        $output = '';
        $output .= '<div class="coll-google-map ' . $class . '">';
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
                
		if ($map_style == 'normal' ) {
			$output .= '

              // Create a map object, and include the MapTypeId to add
              // to the map type control.
              var myOptions = {
                center: mapCenter,
                zoom: ' . $zoom . ',
                scrollwheel: false,
                draggable: !isMobile,
                mapTypeId: google.maps.MapTypeId.' . $type . ',
              };
              var map = new google.maps.Map(document.getElementById("' . $id . '"), myOptions);
              
              
			  map.setCenter(bounds.getCenter());
			  

';
        } else {
        	// Styled map
        	$output .= '
                // Create an array of styles.
                  var styles = [
                    {
                      stylers: [
                        { hue: "' . $hue . '" },
                        { saturation: "' . $saturation . '" }
                      ]
                    }
                  ];
                  
              // Create a new StyledMapType object, passing it the array of styles,
              // as well as the name to be displayed on the map type control.
              var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});

              // Create a map object, and include the MapTypeId to add
              // to the map type control.
              var myOptions = {
                center: mapCenter,
                zoom: ' . $zoom . ',
                scrollwheel: false,
                draggable: !isMobile,
                mapTypeControlOptions: {
                  mapTypeIds: [google.maps.MapTypeId.' . $type . ', "map_style"]
                }
//                mapTypeId: google.maps.MapTypeId.' . $type . '
              };
              var map = new google.maps.Map(document.getElementById("' . $id . '"), myOptions);

              //Associate the styled map with the MapTypeId and set it to display.
              map.mapTypes.set("map_style", styledMap);
              map.setMapTypeId("map_style");
';
        }
			$output .= '

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
              
              map.addListener("zoom_changed", function() {
				console.log("Zoom: " + map.getZoom());
			  });
			  map.addListener("dragend", function() {
				console.log("Center: " + map.getCenter().toString());
			  });

              marker.setMap(map);

          }
          //$(window).load(initializeGoogleMap);
          initializeGoogleMap();

        });
        </script>';

        $output .= '<div id="' . $id . '" style="width:' . $width . '; height:' . $height . ';" class="gmap" ></div>';
        $output .= '</div>';

        return $output;
    }

    public function addScript()
    {
        if (!self::$addedAlready) {
            self::$addedAlready = true;
            wp_register_script('googlemaps', ('//maps.google.com/maps/api/js?sensor=false'), false, null, true);
            wp_enqueue_script('googlemaps');
        }
    }
}
$sc = new MorpheusShortcodeGoogleMap();
$sc->register('coll_gmap');
