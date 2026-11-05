<?php
/**
 * Title: Case study layout (blank)
 * Slug: tresundios/case-study-layout
 * Categories: tresundios
 * Description: Editable case-study structure for posts or pages: client & industry, problem, what we built, services used, results, CTA. Never publish invented numbers.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"article","className":"case-page case-structure","layout":{"type":"default"}} -->
<article class="wp-block-group case-page case-structure"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Client &amp; industry</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[Client name] · [Industry] · [City]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">The problem</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[What was not working, in the client's words if possible]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">What Tresundios built</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[Website / store / app / campaign and the key decisions behind it]</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Services used</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>[Websites &amp; E-commerce / Performance Marketing / Brand &amp; Creative / Social &amp; Content Growth / Apps &amp; Digital Automation]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Results</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"result-placeholder"} -->
<p class="result-placeholder">[Add measurable result: e.g., 35% more enquiries within 3 months]</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="#audit">Get a free website &amp; growth audit </a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></article>
<!-- /wp:group -->
