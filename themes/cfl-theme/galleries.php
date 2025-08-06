<?php
// Add support for prorgams
add_action( 'init', 'cfl_galleries_type' );
function cfl_galleries_type() {
	// Register Programs
	$gallery_labels = array(
    'name'               => 'Galleries',
    'singular_name'      => 'Gallery',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Gallery',
    'edit_item'          => 'Edit Gallery',
    'new_item'           => 'New Gallery',
    'all_items'          => 'All Galleries',
    'view_item'          => 'View Gallery',
    'search_items'       => 'Search Galleries',
    'not_found'          => 'No galleries found',
    'not_found_in_trash' => 'No galleries found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Galleries'
	);
	$gallery_args = array(
	'labels' => $gallery_labels,
	'public' => true,
	'exclude_from_search' => true,
	'supports' =>  array('thumbnail', 'title', 'editor'),
	'menu_position' => 20,
	'menu_icon' => 'dashicons-images-alt2',
	'rewrite' => array('slug' => 'photo-galleries')
	);

	register_post_type( 'cfl_gallery', $gallery_args );
}


function cfl_all_galleries_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'limit' => '12',
        'sort' => 'random',
        'columns' => 3,
    ), $atts );

    // retrieve page section
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$Qargs = array(
			'posts_per_page' => $a['limit'],
			'orderby' => 'published',
			'order' => 'DESC',
	    'post_type' => 'cfl_gallery',
			'paged' => $paged
		);
	//28-July-2019 Commented to get overlay similar to magadi days on homepage
		//$injected = '<div class="cfl-galleries-list">';
	//28-July-2019 Added to get overlay similar to magadi days on homepage
		$injected = '<div class="magadi-days-grid">';
	
		$loop = new WP_Query($Qargs);
		while ($loop->have_posts()) : $loop->the_post();
			$gallery = get_field('photos');
	    if( $gallery ) {
				$srcset = wp_get_attachment_image_srcset( $gallery[0]['ID'] );
	      $image = count($gallery) > 0 ? '<img src="'.$gallery[0]['sizes']['medium'].'" srcset="'.esc_attr( $srcset ).'"/>' : '<img src="placeholder.jpg" />';
	    }

			$t = get_the_title();
	//28-July-2019 Commented to get overlay similar to magadi days on homepage
		//	 $injected .= '<div class="gallery-thumb"><a class="image" href="'.get_the_permalink().'">'.$image.
		//				'</a><div class="details"><h4>'.$t.'</h4>'.
		//				'<a href="'.get_the_permalink().'" class="button3">View</a></div></div>'; 
	
	//28-July-2019 Added to get overlay similar to magadi days on homepage
	$injected .= '<div class="item small-6 large-4"><a class="image" href="'.get_the_permalink().'">'.$image.
					'</a><div class="overlay"><div class="details"><h4>'.$t.'</h4>'.
					'<a href="'.get_the_permalink().'" class="button3">View</a></div></div></div>';

	


	 	endwhile;
		$injected .= '</div>';

		$injected .= custom_pagination($loop->max_num_pages,"",$paged);

		wp_reset_postdata();

    return $injected;
}
add_shortcode( 'cfl_all_gallery_thumbnails', 'cfl_all_galleries_shortcode' );

function custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }

  $pagination_args = array(
    'base'            => get_pagenum_link(1) . '%_%',
    'format'          => 'page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  $output = '';
  if ($paginate_links) {
    $output .= "<nav class='gallery-pagination'>";
      $output .= '<div class="prev-page">'.prev_link().'</div>';
      $output .= '<div class="next-page">'.next_link($numpages).'</div>';
      $output .= "<div class='current-page'>Page " . $paged . " of " . $numpages . "</div> ";
      $output .=  $paginate_links;
    $output .=  "</nav>";
  }
  return $output;
}

function prev_link () {
  global $paged;
  if ( !is_single() && $paged > 1) {
		$nextpage = intval($paged) - 1;
		if ( $nextpage < 1 )
			$nextpage = 1;
    return '<a class="button3" href="'.esc_url(get_pagenum_link($nextpage)).'">Newer Galleries</a>';
	}
}
function next_link ($max_page) {
  global $paged;
  if ( !is_single() && $paged < $max_page) {
		$nextpage = intval($paged) + 1;
    return '<a class="button3" href="'.esc_url(get_pagenum_link($nextpage)).'">Older Galleries</a>';
	}
}

function attach_gallery_images( $post_id ) {
	if(is_single('cfl_gallery')) {
		// bail early if no ACF data
    if( empty($_POST['acf']) ) { return; }

    // specific field value
    $photos = $_POST['acf']['photos'];
		if($photos) {
			foreach($photos as $photo) {
				wp_insert_attachment( array('ID' => $photo->ID, 'post_parent' => $post_id ) );
			}
		}
  }
}
//add_action('acf/save_post', 'attach_gallery_images', 1);



?>
