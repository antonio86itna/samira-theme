    </main><!-- #primary -->

    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-about">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo" rel="home">
                            <?php echo esc_html(get_option('samira_logo_text', __( 'SM', 'samira-theme' ))); ?>
                        </a>
                    <?php endif; ?>
                    
                    <p class="footer-bio">
                        <?php 
                        $footer_text = get_option('samira_footer_text', __( 'Writer and artist. Art is my safe haven for self-expression.', 'samira-theme' ));
                        echo esc_html($footer_text);
                        ?>
                    </p>
                </div>
                
                <!-- Contact Information -->
                <div class="footer-contact">
                    <h4 class="footer-section-title"><?php esc_html_e('Get in Touch', 'samira-theme'); ?></h4>
                    
                    <?php 
                    $contact_email = get_option('samira_contact_email', '');
                    if ($contact_email): ?>
                        <p class="footer-contact-item">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                        </p>
                    <?php endif; ?>
                    
                    <?php 
                    $contact_phone = get_option('samira_contact_phone', '');
                    if ($contact_phone): ?>
                        <p class="footer-contact-item">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122L9.98 10.48c-.197.099-.23.315-.075.472a.678.678 0 0 1-.122.58l-1.794 2.307a.678.678 0 0 1-1.015.063l-1.034-1.034c-.484-.484-.661-1.169-.45-1.77a17.568 17.568 0 0 1 4.168-6.608 17.569 17.569 0 0 1 6.608-4.168c.601-.211 1.286-.033 1.77.45l1.034 1.034z"/>
                            </svg>
                            <a href="tel:<?php echo esc_attr($contact_phone); ?>"><?php echo esc_html($contact_phone); ?></a>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Social Media Section -->
                <div class="footer-social-section">
                    <h4 class="footer-section-title"><?php esc_html_e('Follow Me', 'samira-theme'); ?></h4>
                    <ul class="footer-social">
                        <?php
                        $social_links = array(
                            'instagram' => array('Instagram', 'dashicons-instagram'),
                            'goodreads' => array('Goodreads', 'dashicons-book'),
                            'linkedin'  => array('LinkedIn', 'dashicons-linkedin'),
                            'twitter'   => array('Twitter/X', 'dashicons-twitter'),
                        );

                        foreach ($social_links as $platform => $data) :
                            $url = get_option('samira_social_' . $platform);
                            if ($url) : ?>
                                <li class="social-item">
                                    <a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($data[0]); ?>">
                                        <span class="dashicons <?php echo esc_attr($data[1]); ?>" aria-hidden="true"></span>
                                        <span class="screen-reader-text"><?php echo esc_html($data[0]); ?></span>
                                    </a>
                                </li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                </div>
                
                <!-- Newsletter Section -->
                <div class="footer-newsletter">
                    <h4 class="footer-section-title">
                        <?php 
                        $newsletter_title = get_option('samira_newsletter_title', __( 'Stay Connected', 'samira-theme' ));
                        echo esc_html($newsletter_title);
                        ?>
                    </h4>
                    <p class="newsletter-description">
                        <?php 
                        $newsletter_description = get_option('samira_newsletter_description', __( 'Subscribe to receive my thoughts, updates on current and future releases.', 'samira-theme' ));
                        echo esc_html($newsletter_description);
                        ?>
                    </p>
                    
                    <?php if (samira_is_newsletter_configured()): ?>
                        <form class="newsletter-form" id="newsletter-form">
                            <div class="form-group">
                                <input type="email" 
                                       id="newsletter-email" 
                                       name="email" 
                                       placeholder="<?php esc_attr_e('Enter your email', 'samira-theme'); ?>" 
                                       required>
                            </div>
                            <div class="form-group">
                                <input type="text" 
                                       id="newsletter-name" 
                                       name="name" 
                                       placeholder="<?php esc_attr_e('Your name', 'samira-theme'); ?>" 
                                       required>
                            </div>
                            <button type="submit" class="newsletter-submit">
                                <?php esc_html_e('Subscribe', 'samira-theme'); ?>
                            </button>
                        </form>
                        <div id="newsletter-message" class="newsletter-message" style="display: none;"></div>
                    <?php else: ?>
                        <p class="newsletter-notice">
                            <?php esc_html_e('Newsletter configuration pending. Please check admin settings.', 'samira-theme'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="footer-copyright">
                        &copy; <?php echo date('Y'); ?> 
                        <?php 
                        $copyright_name = get_option('samira_copyright_name', __( 'Samira Mahmoodi', 'samira-theme' ));
                        echo esc_html($copyright_name);
                        ?>. 
                        <?php esc_html_e('All rights reserved.', 'samira-theme'); ?>
                    </p>
                    
                    <div class="footer-links">
                        <?php
                        if (has_nav_menu('footer')) {
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-nav-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ));
                        } else {
                            // Default footer links
                            $privacy_page = get_privacy_policy_url();
                            if ($privacy_page): ?>
                                <a href="<?php echo esc_url($privacy_page); ?>" class="footer-link">
                                    <?php esc_html_e('Privacy Policy', 'samira-theme'); ?>
                                </a>
                            <?php endif;
                            
                            $terms_page = get_option('samira_terms_page');
                            if ($terms_page): ?>
                                <a href="<?php echo esc_url($terms_page); ?>" class="footer-link">
                                    <?php esc_html_e('Terms of Service', 'samira-theme'); ?>
                                </a>
                            <?php endif;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
// Newsletter form handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const message = document.getElementById('newsletter-message');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('newsletter-email').value;
            const name = document.getElementById('newsletter-name').value;
            const submitBtn = form.querySelector('.newsletter-submit');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = '<?php esc_html_e('Subscribing...', 'samira-theme'); ?>';
            
            // Send AJAX request
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'action': 'samira_newsletter_signup',
                    'email': email,
                    'name': name,
                    'nonce': '<?php echo wp_create_nonce('samira_nonce'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                message.style.display = 'block';
                
                if (data.success) {
                    message.className = 'newsletter-message success';
                    message.textContent = data.data.message;
                    form.reset();
                } else {
                    message.className = 'newsletter-message error';
                    message.textContent = data.data.message;
                }
            })
            .catch(error => {
                message.style.display = 'block';
                message.className = 'newsletter-message error';
                message.textContent = '<?php esc_html_e('Something went wrong. Please try again.', 'samira-theme'); ?>';
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = '<?php esc_html_e('Subscribe', 'samira-theme'); ?>';
            });
        });
    }
});
</script>
</body>
</html>
