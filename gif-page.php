<?php
/*
Template Name: Gif-page
*/

/**
 * The template for gif page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimal-Artistic-Portfolio
 */
$gifInRow = 3;
$gifQueryArgs = array(
    'post_type'         => 'gif',
    'posts_per_page'    => -1,
    'post_status'       => 'publish'
);
get_header(); ?>


<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->
            </article>
        <?php endwhile; // End of the loop. ?>
        <?php $gifQuery = new WP_Query($gifQueryArgs); ?>
        <?php if( $gifQuery->have_posts()): ?>
            <?php $p = 0; ?>
            <div class="row">
            <?php while( $gifQuery->have_posts() ) : $gifQuery->the_post(); ?>
                <?php /* if($p > 0 && $p % $gifInRow == 0 ) :?>
                    </div><!-- .row --><div class="row">
                <?php endif; */ ?>
                <?php if( get_the_post_thumbnail_url($post, 'full') ): ?>
                    <article id="post-<?php echo $post->ID;?>" class="animated-gif">
                        <a class="fluidbox" href="<?php echo get_the_post_thumbnail_url($post, 'full');?>" data-fluidbox-loader>
                            <img 
                                src="<?php echo get_the_post_thumbnail_url($post, 'medium');?>"
                                title="<?php echo $post->post_title; ?>" 
                                class="gif-thumbnail">
                        </a>
                        <div class="gif-source hidden">
                            <!-- preload our gif -->
                            <img src="<?php echo get_the_post_thumbnail_url($post, 'full');?>">
                        </div>
                    </article>
                    <?php //$p++; ?>
                <?php endif; ?>
            <?php endwhile; ?>
            </div><!-- .row -->
        <?php endif; ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
