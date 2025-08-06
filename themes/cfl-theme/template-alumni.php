<?php
/**
 * Template Name: CFL Alumni
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
        if (has_post_thumbnail()) {
            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'parallax-bg');
            $valign = get_field('fimage_valign');
            $class = $valign == 'top' ? 'valign-top' : 'valign-center';
            $outputT .= '<section class="background js-coll-page-section coll-page-section">';
            $outputT .= '<div class="js-coll-parallax coll-section-background '.$class.'">';
            $outputT .= '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII="
                            class="coll-bg-image js-coll-lazy"
                            width="' . $thumb[1] . '"
                            height="' . $thumb[2] . '"
                            data-coll-src="' . $thumb[0] . '"
                            alt="' . get_the_title($post->ID) . '" />';
            $outputT .= '<div class="color-overlay"></div>';
            $outputT .= '</div>';
            $outputT .= '</section>';
        }

        ?>
        <div class="wrapper common coll-single <?php if (has_post_thumbnail()) echo 'coll-parallax' ?>" id="skrollr-body">
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
                        </div>
                    </div>
                </div>
            </div>
			<div class="team-members row">

			<?php
			$the_query = new WP_Query( array(
				'post_type' => 'coll-team',
				'tax_query' => array(
					array(
						'taxonomy' => 'coll-team-teams',
						'field'    => 'slug',
						'terms'    => 'alumni',
					),
				),
				'orderby' => 'rand',
				'posts_per_page' => -1,
			));
			if ( $the_query->have_posts() ) : ?>

			<?php $a = 0; while ( $the_query->have_posts() ) : $the_query->the_post();
				$float = ++$a % 2 == 0 ? 'floatleft' : 'floatright'; ?>

			<div class="team-member">
					<div class="large-3 medium-3 columns <?php echo $float;?>">
						<div class="team-member-thumbnail">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
						</div><!-- end .team-member-thumbnail -->
				</div>
				<div class="large-9 medium-9 columns">
						<h2><?php the_title(); ?></h2>
						<h4>Class of <?php the_field('class_year');?></h4>
						<?php the_content(); ?>
						<p class="compiled">~ Compiled in <?php the_field('year_written');?></p>
				</div>
			</div><!-- end .team-member -->

			<div class="clear"></div>

			<?php endwhile; wp_reset_postdata(); endif; ?>

			</div><!-- end .team-members -->
        </section>
    <?php
    endwhile;
endif; ?>
<?php get_footer(); ?>
