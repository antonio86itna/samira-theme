<?php
/**
 * Template principale
 * 
 * @package Samira_Theme
 */

get_header(); ?>

<main class="main-content">
    <?php if (is_home() && is_front_page()): ?>

        <!-- Hero Section -->
        <section class="hero section" id="home">
            <div class="container">
                <div class="hero__content">
                    <div class="hero__text">
                        <h1 class="hero__title">
                            <?php echo esc_html( get_option('samira_hero_title', __( 'Samira Mahmoodi', 'samira-theme' )) ); ?>
                        </h1>
                        <p class="hero__subtitle">
                            <?php echo esc_html( get_option('samira_hero_subtitle', __( 'Writing, Art, Rebirth', 'samira-theme' )) ); ?>
                        </p>
                        <div class="hero__actions">
                            <a href="#about" class="btn btn--primary"><?php echo esc_html__( 'Discover my story', 'samira-theme' ); ?></a>
                            <a href="#newsletter" class="btn btn--outline"><?php echo esc_html__( 'Subscribe to the newsletter', 'samira-theme' ); ?></a>
                        </div>
                    </div>
                    <div class="hero__image">
                        <?php 
                        $hero_image = get_option('samira_hero_image');
                        if ($hero_image): ?>
                            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr(get_option('samira_hero_title', 'Samira Mahmoodi')); ?>" class="hero__img">
                        <?php else: ?>
                            <div class="hero__img-placeholder">
                                <svg width="120" height="120" viewBox="0 0 120 120" fill="currentColor">
                                    <path d="M60 30c-16.569 0-30 13.431-30 30s13.431 30 30 30 30-13.431 30-30-13.431-30-30-30zm0 45c-8.284 0-15-6.716-15-15s6.716-15 15-15 15 6.716 15 15-6.716 15-15 15z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about section" id="about">
            <div class="container">
                <h2 class="section__title"><?php echo esc_html( get_option('samira_about_title', __( 'About Me', 'samira-theme' )) ); ?></h2>
                <div class="about__grid">
                    <div class="about__content">
                        <div class="about__text">
                            <?php 
                            $about_content = get_option('samira_about_content', __( 'Samira Mahmoodi began writing shortly after graduating from college. In 2016, she received a Bachelor of Science in Nursing. Unable to suppress her despair at that time, journaling her feelings led her to rediscover her love for art and literature.', 'samira-theme' ));
                            echo wp_kses_post(wpautop($about_content));
                            ?>
                        </div>
                    </div>
                    <div class="about__journey">
                        <h3 class="about__subtitle"><?php echo esc_html__( 'My Journey', 'samira-theme' ); ?></h3>
                        <p><?php echo esc_html__( 'In 2019 I published my first book, a story of personal discovery that revealed the reasons behind my sadness and where I also found my greatest power: myself.', 'samira-theme' ); ?></p>
                        <p><?php echo esc_html__( 'Art has always spoken to me and understood me better than anyone else. It has always been my safe haven for self-expression.', 'samira-theme' ); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Writing Section -->
        <section class="writing section" id="writing">
            <div class="container">
                <h2 class="section__title"><?php echo esc_html__( 'My Books', 'samira-theme' ); ?></h2>
                <div class="writing__content">
                    <?php
                    // Prima mostra i libri dal custom post type
                    $books_query = new WP_Query(array(
                        'post_type' => 'books',
                        'posts_per_page' => -1,
                        'meta_key' => 'book_year',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    ));

                    if ($books_query->have_posts()):
                        while ($books_query->have_posts()): $books_query->the_post(); ?>
                            <div class="book-card">
                                <div class="book-card__cover">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('medium', array('class' => 'book-card__cover')); ?>
                                    <?php else: ?>
                                        <div class="book-card__cover-placeholder">
                                            <span><?php echo esc_html__( 'Book', 'samira-theme' ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="book-card__content">
                                    <h3 class="book-card__title"><?php the_title(); ?></h3>
                                    <p class="book-card__year"><?php echo esc_html(get_post_meta(get_the_ID(), 'book_year', true)); ?></p>
                                    <div class="book-card__description">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <?php 
                                    $goodreads_link = get_post_meta(get_the_ID(), 'goodreads_link', true);
                                    $amazon_link = get_post_meta(get_the_ID(), 'amazon_link', true);
                                    ?>
                                    <div class="book-card__links">
                                        <?php if ($goodreads_link): ?>
                                            <a href="<?php echo esc_url($goodreads_link); ?>" class="btn btn--outline" target="_blank"><?php echo esc_html__( 'Read on Goodreads', 'samira-theme' ); ?></a>
                                        <?php endif; ?>
                                        <?php if ($amazon_link): ?>
                                            <a href="<?php echo esc_url($amazon_link); ?>" class="btn btn--primary" target="_blank"><?php echo esc_html__( 'Buy', 'samira-theme' ); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else:
                        // Fallback al libro dalle opzioni del tema
                        ?>
                        <div class="book-card">
                            <?php 
                            $book_cover = get_option('samira_book_cover');
                            if ($book_cover): ?>
                                <img src="<?php echo esc_url($book_cover); ?>" alt="Book Cover" class="book-card__cover">
                            <?php else: ?>
                                <div class="book-card__cover-placeholder">
                                      <span><?php echo esc_html__( 'Book', 'samira-theme' ); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="book-card__content">
                                <h3 class="book-card__title">
                                    <?php echo esc_html(get_option('samira_book_title', 'To Water Her Garden: A journey of self-discovery')); ?>
                                </h3>
                                <p class="book-card__year"><?php echo esc_html(get_option('samira_book_year', '2019')); ?></p>
                                <p class="book-card__description">
                                      <?php echo esc_html( get_option('samira_book_description', __( 'Within this space I revealed the reasons behind my sadness and where I also discovered my greatest power: myself.', 'samira-theme' )) ); ?>
                                </p>
                                <?php if (get_option('samira_social_goodreads')): ?>
                                      <a href="<?php echo esc_url(get_option('samira_social_goodreads')); ?>" class="btn btn--outline" target="_blank"><?php echo esc_html__( 'Read on Goodreads', 'samira-theme' ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Art Section -->
        <section class="art section" id="art">
            <div class="container">
                <h2 class="section__title"><?php echo esc_html__( 'My Art', 'samira-theme' ); ?></h2>
                <div class="art__content">
                      <p class="art__description"><?php echo esc_html__( 'Art has spoken to me and understood me better than anyone else. It has always been my safe haven for self-expression. Creating my art empowers me beyond explanation.', 'samira-theme' ); ?></p>
                    <div class="art__gallery">
                        <?php
                        $portfolio_query = new WP_Query(array(
                            'post_type' => 'portfolio',
                            'posts_per_page' => 6
                        ));

                        if ($portfolio_query->have_posts()): 
                            while ($portfolio_query->have_posts()): $portfolio_query->the_post(); ?>
                                <div class="art-item">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('medium_large', array('class' => 'art-item__image')); ?>
                                    <?php else: ?>
                                        <div class="art-item__placeholder">
                                            <svg width="60" height="60" viewBox="0 0 60 60" fill="currentColor">
                                                <path d="M30 20c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="art-item__content">
                                        <h3 class="art-item__title"><?php the_title(); ?></h3>
                                        <div class="art-item__excerpt"><?php the_excerpt(); ?></div>
                                    </div>
                                </div>
                            <?php endwhile;
                            wp_reset_postdata();
                        else:
                            // Placeholder items se non ci sono opere
                            for ($i = 1; $i <= 3; $i++): ?>
                                <div class="art-item">
                                    <div class="art-item__placeholder">
                                        <svg width="60" height="60" viewBox="0 0 60 60" fill="currentColor">
                                            <path d="M30 20c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10z"/>
                                        </svg>
                                    </div>
                                    <div class="art-item__content">
                                          <h3 class="art-item__title"><?php echo esc_html__( 'Art Piece', 'samira-theme' ); ?> <?php echo $i; ?></h3>
                                          <p class="art-item__excerpt"><?php echo esc_html__( 'Description of the artwork and creative process. Add your works from the admin panel.', 'samira-theme' ); ?></p>
                                    </div>
                                </div>
                            <?php endfor;
                        endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="newsletter section" id="newsletter">
            <div class="container">
                <div class="newsletter__content">
                      <h2 class="section__title"><?php echo esc_html( get_option('samira_newsletter_title', __( 'Stay Connected', 'samira-theme' )) ); ?></h2>
                    <p class="newsletter__description">
                          <?php echo esc_html( get_option('samira_newsletter_description', __( 'Subscribe to receive my thoughts, updates on current and future releases.', 'samira-theme' )) ); ?>
                    </p>
                    <form class="newsletter__form" id="newsletter-form">
                        <div class="form-row">
                            <div class="form-group">
                                  <input type="text" name="name" placeholder="<?php echo esc_attr__( 'Your name', 'samira-theme' ); ?>" class="form-input" required>
                            </div>
                            <div class="form-group">
                                  <input type="email" name="email" placeholder="<?php echo esc_attr__( 'Your email', 'samira-theme' ); ?>" class="form-input" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary newsletter__submit">
                            <span class="btn-text"><?php echo esc_html__( 'Subscribe to the Newsletter', 'samira-theme' ); ?></span>
                            <span class="btn-loading" style="display: none;">
                                <span class="loading-spinner"></span> <?php echo esc_html__( 'Subscribing...', 'samira-theme' ); ?>
                            </span>
                        </button>
                    </form>
                    <div class="newsletter__message" id="newsletter-message"></div>
                </div>
            </div>
        </section>

    <?php else: ?>

        <!-- Template per altre pagine -->
        <div class="container">
            <div class="content-area">
                <?php if (have_posts()): ?>
                    <?php while (have_posts()): the_post(); ?>
                        <article <?php post_class('single-post'); ?>>
                            <header class="entry-header">
                                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                                <?php if (get_post_type() === 'post'): ?>
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <span class="byline">
                                            <?php echo esc_html__( 'by', 'samira-theme' ); ?> <?php the_author(); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <?php if (has_post_thumbnail()): ?>
                                <div class="entry-thumbnail">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>

                            <?php if (get_post_type() === 'post'): ?>
                                <footer class="entry-footer">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)): ?>
                                        <div class="entry-categories">
                                            <strong><?php echo esc_html__( 'Categories:', 'samira-theme' ); ?></strong>
                                            <?php foreach ($categories as $category): ?>
                                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    $tags = get_the_tags();
                                    if (!empty($tags)): ?>
                                        <div class="entry-tags">
                                            <strong><?php echo esc_html__( 'Tags:', 'samira-theme' ); ?></strong>
                                            <?php foreach ($tags as $tag): ?>
                                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                                                    <?php echo esc_html($tag->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </footer>
                            <?php endif; ?>
                        </article>

                        <?php
                        // Navigation between posts
                        if (get_post_type() === 'post'):
                            the_post_navigation(array(
                                'next_text' => '<span class="nav-title">' . esc_html__( 'Next article', 'samira-theme' ) . '</span><br><span class="nav-post-title">%title</span>',
                                'prev_text' => '<span class="nav-title">' . esc_html__( 'Previous article', 'samira-theme' ) . '</span><br><span class="nav-post-title">%title</span>',
                            ));
                        endif;

                        // Comments
                        if (comments_open() || get_comments_number()):
                            comments_template();
                        endif;
                        ?>

                    <?php endwhile; ?>

                <?php else: ?>
                    <div class="no-posts">
                        <h2><?php echo esc_html__( 'Content not found', 'samira-theme' ); ?></h2>
                        <p><?php echo esc_html__( 'Sorry, no content matched your request. Please try searching for something else.', 'samira-theme' ); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (is_active_sidebar('blog-sidebar') && (is_single() || is_archive() || is_search())): ?>
                <aside class="sidebar">
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                </aside>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</main>

<?php get_footer(); ?>
