<?php
/* CFL - extends Morpheus, whose functions are loaded AFTER this file */

/* Template overrides:
	morpheus/js/page.sections.js
	morpheus/template-sectioned.php

*/

add_action('wp_head', function () {
  remove_action('wp_head', 'coll_favicon');
}, 1);

require_once('galleries.php');

add_action( 'widgets_init', 'cfl_library_sidebar' );
function cfl_library_sidebar() {
    register_sidebar( array(
        'name' => __( 'Library Sidebar', 'cfl_theme' ),
        'id' => 'library-sidebar',
        'description' => __( 'Widgets in this area will be shown on all library pages.', 'theme-slug' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h6 class="widget-title"><span>',
				'after_title' => '</span></h6>',
    ) );
}

add_image_size( 'parallax-bg', 1280, 720, true );
//add_image_size( 'big-thumb', 450, 450, true );

// Hide the Admin bar for theme development
add_filter('show_admin_bar', '__return_false');

function hide_menu_items() {
    remove_menu_page( 'edit.php?post_type=coll-pricing' );
    remove_menu_page( 'edit.php?post_type=coll-service' );
    remove_menu_page( 'edit.php?post_type=coll-clients' );
    remove_menu_page( 'edit.php?post_type=coll-portfolio' );
    remove_menu_page( 'edit.php?post_type=coll-page-section' );
    remove_menu_page( 'edit.php?post_type=coll-background' );
}
add_action( 'admin_menu', 'hide_menu_items' );

function cfl_setup_head() {
	wp_enqueue_style('dashicons');
	wp_enqueue_style('cfl_fonts', 'https://fonts.googleapis.com/css?family=Montserrat|Raleway|Roboto');
	wp_enqueue_script('cfl_js',get_stylesheet_directory_uri().'/includes/cfl.js', array('jquery'));
	wp_enqueue_script('isotope',get_stylesheet_directory_uri() . '/includes/isotope.pkgd.min.js');
  wp_enqueue_script('imagesloaded',get_stylesheet_directory_uri() . '/includes/imagesloaded.pkgd.min.js');
  wp_enqueue_script('fancybox3',get_stylesheet_directory_uri() . '/includes/jquery.fancybox.min.js',array('jquery'));
  wp_enqueue_style('fancybox3',get_stylesheet_directory_uri() . '/includes/jquery.fancybox.min.css');
}
add_action( 'wp_enqueue_scripts', 'cfl_setup_head', 100);

add_filter('use_default_gallery_style', '__return_false');

/* -------- Include ACF Pro ------------ */
add_filter('acf/settings/path', 'my_acf_settings_path');
function my_acf_settings_path( $path ) {
    $path = get_stylesheet_directory() . '/includes/acf/'; return $path;
}
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
    $dir = get_stylesheet_directory_uri() . '/includes/acf/'; return $dir;
}
//add_filter('acf/settings/show_admin', '__return_false');
include_once( get_stylesheet_directory() . '/includes/acf/acf.php' );

// Add customized FlexSlider shortcode
include_once( get_stylesheet_directory() . '/includes/cflShortcodeFlexSlider.php' );

/* ----------  Add Magadi Days Posts grid shortcode ------------- */
// [magadi_days_grid limit="20"]
function magadi_days_grid_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'limit' => '-1',
        'sort' => 'random',
        'columns' => 3,
    ), $atts );

    // retrieve page section
	$Qargs = array(
		'posts_per_page' => $a['limit'],
		'orderby' => 'published',
		'order' => 'DESC',
		'category_name' => 'magadidays'
	);

	$injected = '<h3 class="subtitle-text" style="text-align:center">Happenings @ CFL</h3><p><div class="magadi-days-grid">';

	$loop = new WP_Query($Qargs);
	while ($loop->have_posts()) : $loop->the_post();
		global $post;
		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
		$t = get_the_title();
		$image = '<img src="'.$thumb[0].'" />';
		$injected .= '<div class="item small-6 large-4">'.$image.
					'<div class="overlay"><div class="details"><h4>'.$t.'</h4>'.
					'<p>'.get_the_excerpt().'</p><a href="'.get_the_permalink().'" class="button3">Read More</a></div></div></div>';
   	endwhile;
	wp_reset_postdata();

	$injected .= '</div>';

    return $injected;
}
add_shortcode( 'magadi_days_grid', 'magadi_days_grid_shortcode' );

// Shortcodes for responsive structure
{
  function myc_row_shortcode( $atts, $content ) {
    $html = trim(do_shortcode($content));
    $html = substr($html,0,6) == '<br />' ? substr($html,6) : $html ;
    $html = substr($html,0,4) == '</p>' ? substr($html,5,-4) : $html ;
    $html = preg_replace('%</div>[\s(<br />)]*<div class="col%', '</div><div class="col', $html);
    return '<div class="row">'.$html.'</div>';

  }
  add_shortcode( 'row', 'myc_row_shortcode' );

  function myc_col_shortcode( $atts, $content, $tag ) {
    $last = isset($atts['last']) ? ' last' : '';
    $html = trim(do_shortcode($content));
    $html = substr($html,0,4) == '</p>' ? substr($html,5,-4) : $html ;
    $cols = 12 * intval(substr($tag,3,1)) / intval(substr($tag,5,1));
  	return '<div class="columns large-'.$cols.' small-12">'.$html.'</div>';
  }
  add_shortcode( 'col1-2', 'myc_col_shortcode' );
  add_shortcode( 'col1-3', 'myc_col_shortcode' );
  add_shortcode( 'col1-4', 'myc_col_shortcode' );
  add_shortcode( 'col2-3', 'myc_col_shortcode' );
  add_shortcode( 'col3-4', 'myc_col_shortcode' );
}


if (!function_exists('coll_excerpt_more')) {
    function coll_excerpt_more($excerpt)
    {
        return ' ...';
    }

    add_filter('excerpt_more', 'coll_excerpt_more');

}

function cfl_home_headings($content) {
	if (is_page_template('template-homepage.php')) {
		$before = '<div class="columns entry-title">';
		$before .= '<span class="sep_holder sep_holder_l"><span class="sep_line"></span></span>';

		$after = '<span class="sep_holder sep_holder_r"><span class="sep_line"></span></span>';
		$after .= '</div>';

		$content = preg_replace('/(<h2(.*?)<\/h2>)/is', $before . '$1'. $after, $content);
	}

	return $content;
}
add_filter('the_content', 'cfl_home_headings');

/* -------- Create Newsletter Type ------------ */
add_action( 'init', 'cfl_create_newsletter' );
function cfl_create_newsletter() {

	$type_labels = array(
    'name'               => 'Newsletters',
    'singular_name'      => 'Newsletter',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Newsletter',
    'edit_item'          => 'Edit Newsletter',
    'new_item'           => 'New Newsletter',
    'all_items'          => 'All Newsletters',
    'view_item'          => 'View Newsletters',
    'search_items'       => 'Search Newsletters',
    'not_found'          => 'No newsletters found',
    'not_found_in_trash' => 'No newsletters found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Newsletters'
	);

	$type_args = array(
	'labels' => $type_labels,
	'public' => true,
	'exclude_from_search' => true,
	'supports' =>  array('thumbnail', 'title', 'revisions'),
	'menu_position' => 31,
	'rewrite' => array('slug' => 'newsletters'),
	);

	register_post_type( 'cfl_newsletter', $type_args );

}


function my_own_gallery($output, $attr) {
    global $post;

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_div = '';
    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    $output = apply_filters( 'gallery_style', $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $link = wp_get_attachment_link($id, $size, true, false);
        if (isset($attr['link']) && 'file' == $attr['link']) {
        	$src = wp_get_attachment_image_src($id, 'large', false, false);
        	$link = '<a href="'.$src[0].'" data-fancybox="gallery">'.wp_get_attachment_image($id, $size).'</a>';
		}
		$item_styles = "columns";
		switch ($columns) {
			case 2:
            	$item_styles .= ' large-6 medium-6 small-12'; break;
            case 4:
	            $item_styles .= ' large-3 medium-4 small-6'; break;
			default:
    	        $item_styles .= ' large-4 medium-6 small-12'; break;
    	}


        $output .= "<div class='gallery-item {$item_styles}'>";
        $output .= "
            <{$icontag} class='gallery-icon'>
                $link
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        $output .= "</div>";
        ;
    }

    $output .= "
        </div>\n";

    return $output;
}
add_filter("post_gallery", "my_own_gallery",10,2);

function filter_imported_posts($posts) {
  $skip = 0;
  for( $i=0; $i<count($posts); $i++) {
    $post = $posts[$i];
    if ( $post['post_type'] == 'attachment' && $post['post_parent'] === 0 ) {
      unset($posts[$i]);
      $skip++;
    }
  }
  //echo "$skip media items skipped.";
  return $posts;
}
//add_filter('wp_import_posts','filter_imported_posts',10,1);


function filter_exported_posts($posts) {
  for( $i=0; $i<count($posts); $i++) {
    $post = $posts[$i];
    if ( $post->post_type == 'attachment' && (int) $post->post_parent === 0 ) {
      unset($posts[$i]);
    }
  }
  return $posts;
}
add_filter('pre_export_posts','filter_exported_posts',10,1);

//CFL - Done for images as radio button in contact form
function add_shortcode_imageradio() {
    wpcf7_add_shortcode( 'imageradio', 'imageradio_handler', true );
}
add_action( 'wpcf7_init', 'add_shortcode_imageradio' );

function imageradio_handler( $tag ){
    $tag = new WPCF7_FormTag( $tag );

    $atts = array(
        'type' => 'radio',
        'name' => $tag->name,
        'list' => $tag->name . '-options' );

    $input = sprintf(
        '<input %s />',
        wpcf7_format_atts( $atts ) );
        $datalist = '';
        $datalist .= '<div class="imgradio">';
		$idx = 0;
        foreach ( $tag->values as $val ) {
        	list($radiovalue,$imagepath,$width) = explode("!", $val);
			
			if ($idx % 2 == 0) {
				$datalist .= sprintf(
				'<div class="one-half"><label><input type="radio" name="%s" value="%s" class="hideradio" /><img src="%s" width="%s"></label></div>', $tag->name, $radiovalue, $imagepath, $width 
				);
			}
			else{
				$datalist .= sprintf(
				'<div class="one-half last"><label><input type="radio" name="%s" value="%s" class="hideradio" /><img src="%s" width="%s"></label></div>', $tag->name, $radiovalue, $imagepath, $width 
				);
			}
			$idx++;
    	}
    $datalist .= '</div>';

    return $datalist;
}

function front_page_grid_shortcode( $atts ) {

	$injected = '<div class="front-page-grid">';

	//Loop
	$injected .= '<div class="item small-6 large-4"><img src="https://cfl.in/wp-content/uploads/2019/01/IMG_20180801_084239356.jpg" />'.
					'<div class="text"><div class="details"><h4>Admissions</h4>'.
					'<p>Some text here</p><a href="https://cfl.in/about-cfl/philosophy/" class="button1">Read More</a></div></div></div>';

		$injected .= '<div class="item small-6 large-4"><img src="https://cfl.in/wp-content/uploads/2019/01/IMG_20180801_084239356.jpg" />'.
					'<div class="text"><div class="details"><h4>Help Us</h4>'.
					'<p>Some text here</p><a href="https://cfl.in/about-cfl/philosophy/" class="button1">Read More</a></div></div></div>';

	$injected .= '<div class="item small-6 large-4"><img src="https://cfl.in/wp-content/uploads/2019/01/IMG_20180801_084239356.jpg" />'.
					'<div class="text"><div class="details"><h4>Philosophy</h4>'.
					'<p>Some text here</p><a href="https://cfl.in/about-cfl/philosophy/" class="button1">Read More</a></div></div></div>';
	

	//wp_reset_postdata();

	$injected .= '</div>';
	
    return $injected;
}
add_shortcode( 'front_page_grid', 'front_page_grid_shortcode' );

//CFL - Done for Paytm redirection from contact form
add_action( 'wp_footer', 'contact_form_paytm_redirect' );
  
function contact_form_paytm_redirect() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
    if ( '16399' == event.detail.contactFormId ) {
		window.location.href = "donations-from-india";
    }
}, false );
</script>
<?php
}