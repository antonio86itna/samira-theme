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
                            <?php echo esc_html(get_option('samira_logo_text', 'SM')); ?>
                        </a>
                    <?php endif; ?>
                    
                    <p class="footer-bio">
                        <?php 
                        $footer_text = get_option('samira_footer_text', 'Writer and artist. Art is my safe haven for self-expression.');
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
                    <div class="footer-social">
                        <?php 
                        $social_links = array(
                            'instagram' => array('Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.358-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.948 0-3.259-.014-3.667-.072-4.947-.2-4.358-2.618-6.78-6.98-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'),
                            'goodreads' => array('Goodreads', 'M17.663 8.164c0-1.584-.32-2.816-.96-3.696-.64-.88-1.552-1.32-2.736-1.32-1.056 0-1.92.352-2.592 1.056-.672.704-1.008 1.632-1.008 2.784v.192c0 1.152.336 2.08 1.008 2.784.672.704 1.536 1.056 2.592 1.056 1.184 0 2.096-.44 2.736-1.32.64-.88.96-2.112.96-3.696zm1.728 0c0 2.048-.48 3.648-1.44 4.8-.96 1.152-2.272 1.728-3.936 1.728-.832 0-1.568-.192-2.208-.576-.64-.384-1.152-.912-1.536-1.584h-.096v8.4h-1.728V3.292h1.632v1.92h.096c.384-.672.896-1.2 1.536-1.584.64-.384 1.376-.576 2.208-.576 1.664 0 2.976.576 3.936 1.728.96 1.152 1.44 2.752 1.44 4.8z'),
                            'linkedin' => array('LinkedIn', 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'),
                            'twitter' => array('Twitter/X', 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z')
                        );
                        
                        foreach ($social_links as $platform => $data):
                            $url = get_option('samira_social_' . $platform);
                            if ($url): ?>
                                <a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($data[0]); ?>">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="<?php echo esc_attr($data[1]); ?>"/>
                                    </svg>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
                
                <!-- Newsletter Section -->
                <div class="footer-newsletter">
                    <h4 class="footer-section-title">
                        <?php 
                        $newsletter_title = get_option('samira_newsletter_title', 'Stay Connected');
                        echo esc_html($newsletter_title);
                        ?>
                    </h4>
                    <p class="newsletter-description">
                        <?php 
                        $newsletter_description = get_option('samira_newsletter_description', 'Subscribe to receive my thoughts, updates on current and future releases.');
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
                        $copyright_name = get_option('samira_copyright_name', 'Samira Mahmoodi');
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
