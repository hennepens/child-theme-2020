<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper lazy" id="wrapper-footer" class="" data-bg="<?php echo get_stylesheet_directory_uri(); ?>/images/newsletter-bg-2.jpg">
	<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<div class="row">
					<div class="d-flex col-md-2 justify-content-center align-items-md-start">
						<img id="logo-svg" class="mb-3" alt loading="lazy" data-lazy-type="image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/hennepens-logomark-small.svg" alt="Hennepen's" width="80" height="80">
					</div>
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-8">
								<?php wp_nav_menu( array('menu' => 'Main Footer Menu') ); ?>
							</div>
							<div class="col-md-4">
								<?php wp_nav_menu( array('menu' => 'Footer Menu Contact') ); ?>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="col-md-3">
				<ul class="d-flex list-inline justify-content-center justify-content-md-end social-cc">
					<li class="icon list-inline-item">
						<a href="https://instagram.com/hennepenscbd" target="_blank">
							<i class="icon icon-instagram"></i>
						</a>
					</li>
					<li class="icon list-inline-item">
						<a href="https://facebook.com/hennepens" target="_blank">
					  		<i class="icon icon-facebook"></i>
					  	</a>
					</li>
					<li class="icon list-inline-item">
						<a href="https://twitter.com/hennepens" target="_blank">
					  		<i class="icon icon-twitter"></i>
					  	</a>
					</li>
					<li class="icon list-inline-item">
						<a href="https://linkedin.com/company/hennepens" target="_blank">
					  		<i class="icon icon-linkedin"></i>
					  	</a>
					</li>
				</ul>
				<ul class="d-flex list-inline justify-content-center justify-content-md-end social-cc">
		            <li class="icon list-inline-item">
		            	<i class="icon icon-cc-visa"></i>
		            </li>
		            <li class="icon list-inline-item">
		            	<i class="icon icon-cc-mastercard"></i>
		            </li>
		            <li class="icon list-inline-item">
		            	<i class="icon icon-cc-discover"></i>
		            </li>
              	</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<footer class="site-footer mt-5" id="colophon">
					<div class="d-flex site-info justify-content-center">
						<ul class="d-md-flex d-block">
							<li>
								&copy; <?php echo date_i18n(_x( 'Y', 'copyright date format', 'understrap' )); ?> Hennepen&apos;s&reg;. All Rights Reserved.
							</li>
							<li>
								<a href="/terms-of-service">Terms of Service</a>
							</li>
							<li>	
								<a href="/privacy-policy">Privacy Policy</a>
							</li>
						</ul>
					</div><!-- .site-info -->
				</footer><!-- #colophon -->
			</div><!--col end -->
			<div class="col-md-12 disclaimer">
				<p class="p-0 m-0 text-justify">The statements made regarding these products have not been evaluated by the Food and Drug Administration. The efficacy of these products has not been confirmed by FDA approved research. These products are not intended to diagnose, treat, cure, or prevent any disease. All information presented here is not meant as a substitute for or alternative to information from health care practitioners. Please consult your health care professional about potential interactions or other possible complications before using any product. The Federal Food, Drug, and Cosmetic Act requires this notice.<br/><br/>
				All products showcased on this website contain below 0.3% THC by law.</p>
			</div>
		</div><!-- row end -->

	</div><!-- container end -->
</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

