<?php get_header(); ?>
<?php

if (have_posts()) :
    while (have_posts()) :
        the_post();
        // thumbnail
        $outputT = '';
        if (has_post_thumbnail()) {
            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'parallax-bg');
			$valign = get_field('fimage_valign') == 'top' ? ' top' : '';
			
            $outputT .= '<section class="background js-coll-page-section coll-page-section">';
            $outputT .= '<div class="js-coll-parallax coll-section-background">';
            $outputT .= '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII="
                            class="coll-bg-image js-coll-lazy'.$valign.'"
                            width="' . $thumb[1] . '"
                            height="' . $thumb[2] . '"
                            data-coll-src="' . $thumb[0] . '"
                            alt="' . get_the_title($post->ID) . '" />';
            $outputT .= '<div class="color-overlay"></div>';
            $outputT .= '</div>';
            $outputT .= '</section>';
        }

        ?>
        <div class="wrapper common coll-single coll-post <?php if (has_post_thumbnail()) echo 'coll-parallax' ?>" id="skrollr-body">
        <?php echo $outputT; ?>
        <section class="content-container js-coll-page-section coll-page-section">
            <div class="row">
                <div class="large-12 columns">
                    <div class="title-wrapper">
                        <h1 class="title-text"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
            <div class="row">
				<div class="large-2 medium-2 columns">
				 <div class="post-meta">
					<div class="timestamp"><?php the_time('d M Y'); ?></div>
						<div class="author-meta">
							<div class="wrapper">
								<div class="image"><?php echo get_avatar(get_the_author_meta('ID'), 100); ?></div>
								<div class="text">
								<span class="by-author"><?php _e('By ', 'framework');
									the_author(); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="copy-container large-10 medium-10 columns">
					<div class="content-wrapper">
						<article class="entry-content clearfix">
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
        	<div class="row">
        		<div class="columns large-10 medium-10 large-offset-2 medium-offset-2">
					<footer class="coll-post-info">
						<?php if (has_category()) { ?>
							<div class="categories">
								<span class="caption"><?php _e('Categories ', 'framework'); ?></span>
								<span class="list"><?php the_category(' '); ?></span>
							</div>
						<?php } ?>
						<?php if (has_tag()) { ?>
							<div class="tags">
								<span class="caption"><?php _e('Tags ', 'framework'); ?></span>
								<span class="list"><?php the_tags('', ''); ?></span>
							</div>
						<?php } ?>
					</footer>
					<!--                            comment button-->
					<a class="coll-button coll-accent-color leave-comment"
					   href="<?php echo get_permalink($post->ID); ?>#comments"
					   target="self">
						<?php _e('Leave a comment', 'framework'); ?>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="coll-section-divider">
					<span class="text large-2 medium-2"><?php _e('More Posts', 'framework'); ?></span>
					<span class="line large-10 medium-10"><span class="color"></span></span>
				</div>
				<div class="navigation-container large-10 large-offset-2 medium-offset-2 medium-10">
					<div class="row">
						<div class="previous large-6 medium-6 columns">
							<?php if (get_next_post()) : $pID = get_next_post()->ID; ?>
								<a class="arrow" href="<?php echo get_permalink($pID); ?>">
									<div class="icon"><i class="fa fa-long-arrow-left"></i></div>
									<div class="info">
										<label><?php _e('Next', 'framework'); ?></label>

										<h3 class="title-text"><?php echo get_the_title($pID); ?></h3>
									</div>

								</a>
							<?php endif; ?>
						</div>
						<div class="next large-6 medium-6 columns">
							<?php if (get_previous_post()) : $pID = get_previous_post()->ID; ?>

								<a class="arrow" href="<?php echo get_permalink($pID); ?>">
									<div class="icon"><i class="fa fa-long-arrow-right"></i></div>
									<div class="info">
										<label><?php _e('Previous', 'framework'); ?></label>

										<h3 class="title-text"><?php echo get_the_title($pID); ?></h3>
									</div>


								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div id="comments">

				</div>

				<div class="coll-section-divider">
					<span class="text large-2 medium-2"><?php _e('Comments', 'framework'); ?></span>
					<span class="line large-10 medium-10"><span class="color"></span></span>
				</div>
				<div class="comments-container large-10 large-offset-2  medium-offset-2 medium-10">
					<?php comments_template('', true); ?>
				</div>
				<?php if ('open' == $post->comment_status) : ?>
					<div class="coll-section-divider">
						<span class="text large-2 medium-2"><?php _e('Comment', 'framework'); ?></span>
						<span class="line large-10 medium-10"><span class="color"></span></span>
					</div>
					<div class="form-container large-10 large-offset-2 medium-offset-2 medium-10">
						<?php coll_output_comment_form(array('class_submit' => 'myclass')); ?>
					</div>
				<?php endif; ?>

			</div>
        </section>





    <?php
    endwhile;
endif; ?>
<?php get_footer(); ?>