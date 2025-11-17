<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Samira_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'samira-theme' ); ?></a>

        <header id="masthead" class="header">
                <div class="header__content container">
				<div class="site-branding">
					<?php
					$logo_type = get_option( 'samira_logo_type', 'text' );
					$logo_image_id = get_option( 'samira_logo_image_id', '' );
					$logo_image_url = get_option( 'samira_logo_image', '' );
					$logo_text = get_option( 'samira_logo_text', __( 'SM', 'samira-theme' ) );

					if ( $logo_type === 'image' && ( $logo_image_id || $logo_image_url ) ) {
						?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
							<?php
							if ( $logo_image_id ) {
								echo wp_get_attachment_image( $logo_image_id, 'medium', false, array(
									'class' => 'custom-logo',
									'alt'   => get_bloginfo( 'name' ),
								) );
							} else {
								?>
								<img src="<?php echo esc_url( $logo_image_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="custom-logo" loading="eager">
								<?php
							}
							?>
						</a>
						<?php
					} else {
						?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php echo esc_html( $logo_text ); ?>
							</a>
						</h1>
						<?php
					}
					?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="menu-toggle-text"><?php esc_html_e( 'Menu', 'samira-theme' ); ?></span>
						<span class="menu-icon">
							<span></span>
							<span></span>
							<span></span>
						</span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'nav-menu',
							'container'      => false,
							'fallback_cb'    => 'samira_fallback_menu',
						)
					);
					?>
				</nav><!-- #site-navigation -->

                                <div class="header__actions">
					<!-- Dark Mode Toggle -->
                                       <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'samira-theme' ); ?>">
                                               <svg class="dark-mode-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>
                                       </button>

					<!-- Contact Button -->
					<a href="#contact" class="contact-btn header__cta">
						<?php esc_html_e( 'Contact', 'samira-theme' ); ?>
					</a>
                                </div>
                        </div>
        </header><!-- #masthead -->

	<?php
	// Fallback menu for when no menu is assigned
	function samira_fallback_menu() {
		echo '<ul id="primary-menu" class="nav-menu">';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'samira-theme' ) . '</a></li>';
		echo '<li><a href="#about">' . esc_html__( 'About', 'samira-theme' ) . '</a></li>';
		echo '<li><a href="#writing">' . esc_html__( 'Books', 'samira-theme' ) . '</a></li>';
		echo '<li><a href="#art">' . esc_html__( 'Art', 'samira-theme' ) . '</a></li>';
		echo '<li><a href="#newsletter">' . esc_html__( 'Newsletter', 'samira-theme' ) . '</a></li>';
		echo '</ul>';
	}
	?>

	<main id="primary" class="site-main">
