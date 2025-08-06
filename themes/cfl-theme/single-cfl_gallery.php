<?php get_header(); ?>
<?php
$content_columns = ot_get_option('coll_page_sidebar') ? '9' : '12';

if (have_posts()) :
    while (have_posts()) :
        the_post();
        global $post;
        ?>
        <div class="wrapper common coll-single <?php if (has_post_thumbnail()) echo 'coll-parallax' ?>" id="skrollr-body">
        <section class="title-container js-coll-page-section coll-page-section">
            <div class="row">
                <div class="large-12 columns">
                    <div class="title-wrapper">
                        <h1 class="title-text"><?php echo get_the_title(get_the_ID()); ?></h1>
                        <p>Posted: <?php echo date('j-M-Y', strtotime($post->post_date)); ?></p>
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
                                <a href="<?php $parent = get_page_by_title('Photo Galleries', OBJECT, 'page'); echo get_permalink($parent->ID); ?>" class="button3 back-to-galleries">Back to Galleries</a>
                                <?php the_content(); ?>

                                <?php
                                  $photos = get_field('photos');
                                  if(count($photos) > 0) {
                                    echo '<div class="cfl-gallery">';
                                    foreach ($photos as $photo) {
                                      $srcset = wp_get_attachment_image_srcset( $photo['ID'] );
                                      echo '<div class="photo"><a href="'.$photo['sizes']['large'].'" data-fancybox="gallery" ><img src="'.$photo['sizes']['medium'].'" srcset="'.esc_attr( $srcset ).'" alt="'.$photo['alt'].'" /></a></div>';
                                    }
                                    echo '</div>';
                                  }
                                ?>
                            </article>
                            <?php
                            $defaults = array(
                                'before' => '<ul class="coll-pagination">',
                                'after' => '</ul>',
                                'link_before' => '<li>',
                                'link_after' => '</li>',
                                'next_or_number' => 'number',
                                'separator' => '',
                                'nextpagelink' => __('Next gallery'),
                                'previouspagelink' => __('Previous gallery'),
                                'pagelink' => '%',
                                'echo' => 1
                            );

                            wp_link_pages($defaults);
                            ?>
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
