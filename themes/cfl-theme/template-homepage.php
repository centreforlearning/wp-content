<?php
/*
Template Name: CFL Homepage
*/

get_header(); ?>
<?php

$content_columns = ot_get_option('coll_page_sidebar') ? '9' : '12';

wp_enqueue_script('page.sections');

if (have_posts()) :
    while (have_posts()) :
        the_post();
    		
    		$headerSlider = get_page_by_path('home-header',OBJECT,'coll-flexslider');
        ?>
        
        <div class="wrapper common" id="skrollr-body">
		<section id="home-header-mobile" class="page-section coll-page-section hentry">
			 <?php echo do_shortcode('[cfl_flexslider class="coll-bg-slider" imgsize="parallax-bg"]'); ?>
			<div class="section-content row">
				<div class="entry-content columns">
				<?php the_field('intro_text'); ?>
				</div>
			</div>
		</section>
		<section id="home-header" class="coll-page-section js-coll-window-min">
			<div class="coll-section-background js-coll-parallax" >
			 <?php echo do_shortcode('[cfl_flexslider class="coll-bg-slider" imgsize="parallax-bg"]');?>
        	</div>
			<div class="section-content row">
				<div class="entry-content columns">
				<?php the_field('intro_text'); ?>
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