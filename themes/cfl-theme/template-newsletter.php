<?php
/**
 * Template Name: Newletters
 * Description: The template for displaying all newsletters.
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
                                <?php // retrieve page section
									$Qargs = array(
									'post_type' => 'cfl_newsletter',
									'posts_per_page' => -1,
									'orderby' => 'title',
									'order' => 'DESC'
								);

								$injected = '<div class="magadi-days-grid">';

								$loop = new WP_Query($Qargs);
								while ($loop->have_posts()) : $loop->the_post();
									global $post;
									$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
									$t = get_the_title();
									$image = '<img src="'.$thumb[0].'" />';
									$injected .= '<div class="item small-6 medium-4 large-3"><div class="thumb">'.$image.
												'<div class="overlay"><div class="details"><h4>Newsletter '.$t.'</h4>'.
												'<p>Issue '.get_field('issue').'</p><a href="'.get_field('pdf_file').'" class="button3">View/Download</a></div></div></div>
												<p class="title">'.$t.'</p>
												</div>';
								endwhile;
								wp_reset_postdata();

								$injected .= '</div>';

								echo $injected
								?>
                            </article>
                        </div>
                    </div>
                </div>
                <!--                end left-->
                <?php if (ot_get_option('coll_page_sidebar')) : ?>
                    <div class="large-3 columns">
                        <div class="sidebar-container">
                            <?php if (!dynamic_sidebar()) dynamic_sidebar('coll-page-sidebar'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>





    <?php
    endwhile;
endif; ?>
<?php get_footer(); ?>
