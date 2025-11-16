<?php
/**
 * Template Name: About Page
 * Description: About page template with image gallery
 *
 * @package Samira_Theme
 */

get_header(); ?>

<main class="main-content about-page">
    <div class="container">
        <article class="about-page-content">
            <header class="about-page-header">
                <h1 class="about-page-title">
                    <?php echo esc_html( get_option('samira_about_page_title', __( 'About Me', 'samira-theme' )) ); ?>
                </h1>
            </header>

            <div class="about-page-description">
                <?php
                $about_description = get_option('samira_about_page_description', '');
                if ( $about_description ) {
                    echo wp_kses_post( wpautop( $about_description ) );
                }
                ?>
            </div>

            <?php
            // Get gallery images
            $gallery_images = get_option('samira_about_page_gallery', array());

            if ( !empty($gallery_images) && is_array($gallery_images) ) :
                // Limit to 50 images max
                $gallery_images = array_slice($gallery_images, 0, 50);
                $total_images = count($gallery_images);
                $initial_load = 9; // Show 9 images initially
            ?>
                <div class="about-page-gallery">
                    <div class="gallery-grid" id="gallery-grid">
                        <?php
                        foreach ( $gallery_images as $index => $image_id ) :
                            if ( !$image_id ) continue;

                            $image_url = wp_get_attachment_image_url( $image_id, 'large' );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                            $image_caption = wp_get_attachment_caption( $image_id );

                            // Hide images beyond initial load
                            $hidden_class = ( $index >= $initial_load ) ? 'gallery-item--hidden' : '';
                            ?>
                            <div class="gallery-item <?php echo esc_attr( $hidden_class ); ?>" data-index="<?php echo esc_attr( $index ); ?>">
                                <div class="gallery-item__inner">
                                    <img src="<?php echo esc_url( $image_url ); ?>"
                                         alt="<?php echo esc_attr( $image_alt ? $image_alt : __( 'Gallery Image', 'samira-theme' ) ); ?>"
                                         loading="lazy"
                                         class="gallery-item__image">
                                    <?php if ( $image_caption ) : ?>
                                        <div class="gallery-item__caption">
                                            <?php echo esc_html( $image_caption ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ( $total_images > $initial_load ) : ?>
                        <div class="gallery-actions">
                            <button type="button" class="btn btn--outline gallery-load-more" id="gallery-load-more">
                                <?php esc_html_e( 'Load More', 'samira-theme' ); ?>
                                <span class="gallery-count">(<span id="current-count"><?php echo $initial_load; ?></span> / <?php echo $total_images; ?>)</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="about-page-no-gallery">
                    <p><?php esc_html_e( 'No gallery images available yet.', 'samira-theme' ); ?></p>
                </div>
            <?php endif; ?>
        </article>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('gallery-load-more');

    if (loadMoreBtn) {
        const galleryGrid = document.getElementById('gallery-grid');
        const currentCountSpan = document.getElementById('current-count');
        const hiddenItems = galleryGrid.querySelectorAll('.gallery-item--hidden');
        const totalImages = <?php echo isset($total_images) ? intval($total_images) : 0; ?>;
        const loadIncrement = 9; // Load 9 more images at a time
        let currentlyShown = <?php echo isset($initial_load) ? intval($initial_load) : 0; ?>;

        loadMoreBtn.addEventListener('click', function() {
            const itemsToShow = galleryGrid.querySelectorAll('.gallery-item--hidden');
            let itemsShownCount = 0;

            // Show next batch of images
            itemsToShow.forEach(function(item, index) {
                if (itemsShownCount < loadIncrement) {
                    item.classList.remove('gallery-item--hidden');
                    itemsShownCount++;
                }
            });

            currentlyShown += itemsShownCount;
            currentCountSpan.textContent = currentlyShown;

            // Hide button if all images are shown
            if (currentlyShown >= totalImages) {
                loadMoreBtn.style.display = 'none';
            }
        });
    }
});
</script>

<style>
.about-page {
    padding: 60px 0;
}

.about-page-content {
    max-width: 1200px;
    margin: 0 auto;
}

.about-page-header {
    text-align: center;
    margin-bottom: 40px;
}

.about-page-title {
    font-size: 3rem;
    font-family: var(--font-heading, 'Playfair Display', serif);
    color: var(--color-text, #2c2c2c);
    margin: 0;
}

.about-page-description {
    max-width: 800px;
    margin: 0 auto 60px;
    font-size: 1.1rem;
    line-height: 1.8;
    text-align: center;
}

.about-page-gallery {
    margin-top: 40px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

@media (min-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    background: #f5f5f5;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.gallery-item--hidden {
    display: none;
}

.gallery-item:hover {
    transform: translateY(-4px);
}

.gallery-item__inner {
    position: relative;
    width: 100%;
    padding-bottom: 100%; /* Square aspect ratio */
    overflow: hidden;
}

.gallery-item__image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-item__image {
    transform: scale(1.05);
}

.gallery-item__caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: #fff;
    font-size: 0.9rem;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-item__caption {
    transform: translateY(0);
}

.gallery-actions {
    text-align: center;
    padding: 20px 0;
}

.gallery-load-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.gallery-count {
    font-size: 0.9em;
    opacity: 0.8;
}

.about-page-no-gallery {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

/* Dark mode support */
body.dark-mode .about-page-title {
    color: var(--color-text-dark, #f5f5f5);
}

body.dark-mode .gallery-item {
    background: #2c2c2c;
}

/* Responsive */
@media (max-width: 767px) {
    .about-page-title {
        font-size: 2rem;
    }

    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php get_footer(); ?>
