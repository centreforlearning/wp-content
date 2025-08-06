<?php 
/**
 * Template Name: CFL Staff
 * Description: The template for displaying the staff page.
 *
 *
 * @package cfl
 */

get_header(); ?>
<?php
$content_columns = ot_get_option('coll_page_sidebar') ? '9' : '12';

if (have_posts()) :
    while (have_posts()) :
        the_post();
        // thumbnail
        $outputT = '';
        
		$the_query = new WP_Query( array(
			'post_type' => 'coll-team',
			'tax_query' => array(
				array(
					'taxonomy' => 'coll-team-teams',
					'field'    => 'slug',
					'terms'    => 'staff',
				),
			),
			'orderby' => 'rand',
			'posts_per_page' => 10,
		));
		
		if ( $the_query->have_posts() ) :
			$outputT .= '<section class="staff-photos js-coll-page-section coll-page-section">';
			
			while ( $the_query->have_posts() ) : $the_query->the_post();
				global $post;
				$outputT .= get_the_post_thumbnail( $post->ID, 'thumbnail' );
			
			endwhile; wp_reset_postdata();
			$outputT .= '</section>';
		endif; ?>

        <div class="wrapper common coll-single" id="skrollr-body">
        <?php echo $outputT; ?>
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
                <div class="large-<?php echo $content_columns; ?> columns">
                    <div class="copy-container">
                        <div class="content-wrapper">
                            <article class="entry-content">
                                <?php the_content(); ?>
                            </article>
                            <div class="team-members">
				
							<?php
							$the_query = new WP_Query( array(
								'post_type' => 'coll-team',
								'tax_query' => array(
									array(
										'taxonomy' => 'coll-team-teams',
										'field'    => 'slug',
										'terms'    => 'staff',
									),
								),
								'orderby' => 'title',
								'order' => 'ASC',
								'posts_per_page' => -1,
							));
							if ( $the_query->have_posts() ) : ?>
			
							<?php $a = 0; while ( $the_query->have_posts() ) : $the_query->the_post();
								$float = ++$a % 2 == 0 ? 'floatleft' : 'floatright';
							?>
			
							<div class="team-member">
									<div class="large-3 medium-3 columns <?php echo $float;?>">
										<div class="team-member-thumbnail">
										<?php the_post_thumbnail( 'thumbnail' ); ?>
										</div><!-- end .team-member-thumbnail -->
								</div>
								<div class="large-9 medium-9 columns">
										<h2><?php the_title(); ?></h2>
										<?php the_content(); ?>
								</div>
								<div class="clear"></div>
							</div><!-- end .team-member -->
									
				
							<?php endwhile; wp_reset_postdata(); endif; ?>
								
							<div class="team-member">
									<div class="large-3 medium-3 columns &lt;?php echo $float;?&gt;">
										<div class="team-member-thumbnail">
										<img width="449" height="449" src="https://cfl.in/wp-content/uploads/2017/10/WhatsApp-Image-2025-05-30-at-23.38.36-1-e1749709375429.jpeg" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
										</div><!-- end .team-member-thumbnail -->
								</div>
								<div class="large-9 medium-9 columns">
										<h2>Support Staff</h2>
										<p>Our support staff, who have been with us over so many years (from left to right): <strong>Nagaraja</strong>, <strong>Siddappa</strong>, <strong>Chandru</strong>, <strong>Raja</strong>.</p>
								</div>
								<div class="clear"></div>
							</div>
							
							</div><!-- end .support-staff -->
                        </div>
                    </div>
                </div>                 <!-- end left-->
            </div>
        </section>
    <?php
    endwhile;
endif; ?>
<?php get_footer(); ?>