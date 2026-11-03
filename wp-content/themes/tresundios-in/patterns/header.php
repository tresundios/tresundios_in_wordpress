<?php
/**
 * Title: Header
 * Slug: tresundios/header
 * Categories: tresundios
 * Inserter: no
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:html -->
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'tresundios' ); ?></a>
<div class="progress-bar-top" aria-hidden="true"><span id="progress"></span></div>
<div class="cursor" aria-hidden="true"><span class="cursor-dot"></span><span class="cursor-ring"></span></div>
<!-- /wp:html -->

<!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"navbar glass tr-navbar","layout":{"type":"default"}} -->
<div class="wp-block-group navbar glass tr-navbar"><!-- wp:html -->
<a class="navbar-brand py-0" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Tresundios home', 'tresundios' ); ?>"><img class="logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo-light.webp' ) ); ?>" alt="Tresundios" width="176" height="30"></a>
<button class="navbar-toggler" type="button" aria-controls="tr-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'tresundios' ); ?></button>
<!-- /wp:html -->

<!-- wp:group {"anchor":"tr-menu","className":"tr-menu","layout":{"type":"default"}} -->
<div id="tr-menu" class="wp-block-group tr-menu"><!-- wp:navigation {"overlayMenu":"never","className":"tr-nav","ariaLabel":"Main","layout":{"type":"flex","flexWrap":"wrap"}} -->
<!-- wp:navigation-link {"label":"Services","url":"<?php echo esc_url( home_url( '/services/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Products","url":"<?php echo esc_url( home_url( '/products/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Pricing","url":"<?php echo esc_url( home_url( '/pricing/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Work","url":"<?php echo esc_url( home_url( '/work/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Resources","url":"<?php echo esc_url( home_url( '/resources/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Process","url":"<?php echo esc_url( home_url( '/process/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"About","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Contact","url":"<?php echo esc_url( home_url( '/contact/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#book-a-call"><?php esc_html_e( 'Book a free call', 'tresundios' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
