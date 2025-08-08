<?php
/**
 * Portfolio archive template
 *
 * @package Samira_Theme
 */
get_header(); ?>

<main class="main-content">
    <section class="section portfolio-archive">
        <div class="container">
            <h1 class="section__title"><?php post_type_archive_title(); ?></h1>
            <?php
            $terms = get_terms(
                array(
                    'taxonomy'   => 'art_type',
                    'hide_empty' => true,
                )
            );
            ?>
            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                <div class="portfolio-tabs" data-current="all">
                    <button class="portfolio-tab active" data-term="all"><?php echo esc_html__( 'All', 'samira-theme' ); ?></button>
                    <?php foreach ( $terms as $term ) : ?>
                        <button class="portfolio-tab" data-term="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="art__gallery portfolio-grid">
                <?php
                $portfolio_loop = new WP_Query(
                    array(
                        'post_type'      => 'portfolio',
                        'posts_per_page' => -1,
                    )
                );
                if ( $portfolio_loop->have_posts() ) :
                    while ( $portfolio_loop->have_posts() ) :
                        $portfolio_loop->the_post();
                        $item_terms = get_the_terms( get_the_ID(), 'art_type' );
                        $term_slugs = $item_terms ? wp_list_pluck( $item_terms, 'slug' ) : array();
                        $data_terms = implode( ' ', $term_slugs );
                        ?>
                        <div class="art-item portfolio-item" data-terms="<?php echo esc_attr( $data_terms ); ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'art-item__image' ) ); ?>
                            <?php endif; ?>
                            <div class="art-item__content">
                                <h3 class="art-item__title"><?php the_title(); ?></h3>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p><?php echo esc_html__( 'No works found', 'samira-theme' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
