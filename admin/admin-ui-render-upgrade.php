<?php
/**
 * Upgrade to pro Settings UI
 *
 * @since 1.7
 * 
 * @function	superpwa_upgread_pro_interface_render()	Add-Ons UI renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function superpwa_upgread_pro_interface_render(){
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	?>
		<link rel='stylesheet' href='<?php echo SUPERPWA_PATH_SRC . 'admin/css/upgrade-ui.css' ?>' media='all' />

	<div class="wrap">
		<h1><?php _e( 'Upgrade for', 'super-progressive-web-apps' ); ?> SuperPWA <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		<p><?php // _e( 'Upgrade the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
				<?php
				if(defined('SUPERPWA_PRO_VERSION')){
				 do_action("admin_upgrade_license_page");
				}else{ ?>


				<div class="fp-wr">
				    <div class="fp-img">
				        <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/Bitmap.png' ?>">
				        <span class="ov"></span>
				    </div>
				    <div class="fp-cnt">
				            <h1><?php _e( 'Upgrade to Pro'); ?></h1>
				            <p><?php _e( 'Take your AMP to the next level with more beautiful themes, great extensions and more powerful features.', 'super-progressive-web-apps' ); ?></p>
				            <a class="buy" href="#upgrade"><?php _e( 'BUY NOW', 'super-progressive-web-apps' ); ?></a>
				    </div>
				    <div class="pvf">
				        <div class="ext">
				            <div class="ex-1 e-1">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-1.png' ?>">
				                <h4><?php _e( 'Extensions', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'Includes a suite of advanced features like Ads, Email Optin, Contact Forms, E-Commerce, CTA, Cache and 15+ premium extensions.', 'super-progressive-web-apps' ); ?></p>
				            </div>
				            <div class="ex-1 e-2">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-2.png' ?>">
				                <h4><?php _e( 'Designs', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'Wide Variety of AMP Theme Designs included with AMP Layouts. We are dedicated to release 2-3 new designs every month.', 'super-progressive-web-apps' ); ?></p>
				            </div>
				            <div class="ex-1 e-3">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-3.png' ?>">
				                <h4><?php _e( 'Dedicated Support', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'Get private ticketing help from our full-time staff who helps you with the technical issues.', 'super-progressive-web-apps' ); ?></p>
				            </div>
				        </div><!-- /. ext -->
				        <div class="pvf-cnt">
				            <div class="pvf-tlt">
				                <h2><?php _e( 'Compare Pro vs. Free Version', 'super-progressive-web-apps' ); ?></h2>
				                <span><?php _e( 'See what you\'ll get with the professional version', 'super-progressive-web-apps' ); ?></span>
				            </div>
				            <div class="pvf-cmp">
				                <div class="fr">
				                    <h1>FREE</h1>
				                    <div class="fr-fe">
				                        <div class="fe-1">
				                            <h4><?php _e( 'Continious Development', 'super-progressive-web-apps' ); ?></h4>
				                            <p><?php _e( 'We take bug reports and feature requests seriously. We’re continiously developing &amp; improve this product for last 2 years with passion and love.', 'super-progressive-web-apps' ); ?></p>
				                        </div>
				                        <div class="fe-1">
				                            <h4><?php _e( '300+ Features', 'super-progressive-web-apps' ); ?></h4>
				                            <p><?php _e( 'We\'re constantly expanding the plugin and make it more useful. We have wide variety of features which will fit any use-case.', 'super-progressive-web-apps' ); ?></p>
				                        </div>
				                        <div class="fe-1">
				                            <h4><?php _e( 'Design', 'super-progressive-web-apps' ); ?></h4>
				                            <p><?php _e( 'We have 4 Built in themes for AMP which elevates your AMP exeprience.', 'super-progressive-web-apps' ); ?></p>
				                        </div>
				                        <div class="fe-1">
				                            <h4><?php _e( 'Technical Support', 'super-progressive-web-apps' ); ?></h4>
				                            <p><?php _e( 'We have a full time team which helps you with each and every issue regarding AMP.', 'super-progressive-web-apps' ); ?></p>
				                        </div>
				                    </div><!-- /. fr-fe -->
				                </div><!-- /. fr -->
				                <div class="pr">
				                    <h1>PRO</h1>
				                    <div class="pr-fe">
				                        <span><?php _e( 'Everything in Free, and:', 'super-progressive-web-apps' ); ?></span>
				                        <div class="fet">
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Advertisement', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Advanced Ad slots, Incontent ads &amp; Supports all Ad networks.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'AMP Cache', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Revolutionary cache system for AMP which makes it insanely fast.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Contact Forms', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Gravity Forms and Contact form 7 Support for the AMP.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>E-Commerce</h4>
				                                </div>
				                                <p>WooCommerce &amp; Easy Digital Downloads Support.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Email Optin</h4>
				                                </div>
				                                <p>Native Email optin forms to capture email with 17+ company integrations.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Call To Action</h4>
				                                </div>
				                                <p>Get your message, product or offering to your visitors.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Localization</h4>
				                                </div>
				                                <p>Integrates with WPML, Polylang and WeGlot to provide localization.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Structured Data</h4>
				                                </div>
				                                <p>Advanced Schema integration in AMP and WordPress.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Advanced Custom Field</h4>
				                                </div>
				                                <p>Built-in tools to help you impliment ACF easily in AMP.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Ratings</h4>
				                                </div>
				                                <p>Easily add Rating to the posts. Supports 3 popular rating plugins.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Design Catalogue</h4>
				                                </div>
				                                <p>AMP Layouts has 6 pre-built designs, We are constantly adding every week.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Dedicated Support</h4>
				                                </div>
				                                <p>With a Dedicated person helping you with the extension setup and questions.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Continious Updates</h4>
				                                </div>
				                                <p>We're continiously updating our premium features and releasing them.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Innovation</h4>
				                                </div>
				                                <p>Be the first one to get the innovative features that we build in the future.</p>
				                            </div>
				                        </div><!-- /. fet -->
				                        <div class="pr-btn">
				                            <a href="#upgrade">Upgrade to Pro</a>
				                        </div><!-- /. pr-btn -->
				                    </div><!-- /. pr-fe -->
				                </div><!-- /.pr -->
				            </div><!-- /. pvf-cmp -->
				        </div><!-- /. pvf-cnt -->
				        <div id="upgrade" class="amp-upg">
				            <div class="upg-t">
				                <h2>Let's Upgrade Your AMP</h2>
				                <span>Choose your plan and upgrade in minutes!</span>
				            </div>
				            <div class="pri-lst">
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=1">
				                        <h5>PERSONAL</h5>
				                        <span class="d-amt"><sup>$</sup>99</span>
				                        <span class="amt"><sup>$</sup>99</span>
				                        <span class="s-amt">(Save $59)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">1 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">1 year Updates </span>
				                        <span class="sv">Pro Features </span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb rec">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=2">
				                        <h5>MULTIPLE</h5>
				                        <span class="d-amt"><sup>$</sup>129</span>
				                        <span class="amt"><sup>$</sup>129</span>
				                        <span class="s-amt">(Save $79)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">3 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">1 year Updates</span>
				                        <span class="sv">Pro Features</span>
				                        <span class="pri-by">Buy Now</span>
				                        <span class="rcm">RECOMMENDED</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=3">
				                        <h5>WEBMASTER</h5>
				                        <span class="d-amt"><sup>$</sup>199</span>
				                        <span class="amt"><sup>$</sup>199</span>
				                        <span class="s-amt">(Save $99)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">10 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sv">Save 83%</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=4">
				                        <h5>FREELANCER</h5>
				                        <span class="d-amt"><sup>$</sup>249</span>
				                        <span class="amt"><sup>$</sup>249</span>
				                        <span class="s-amt">(Save $119)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">25 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sv">Save 90%</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=5">
				                        <h5>AGENCY</h5>
				                        <span class="d-amt"><sup>$</sup>499</span>
				                        <span class="amt"><sup>$</sup>499</span>
				                        <span class="s-amt">(Save $199)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">Unlimited Site</span>
				                        <span class="e">E-mail support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sv">UNLIMITED</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=6">
				                        <h5>LifeTime</h5>
				                        <span class="d-amt"><sup>$</sup>999</span>
				                        <span class="amt"><sup>$</sup>999</span>
				                        <span class="s-amt">(Save $199)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">Unlimited Site</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sv">UNLIMITED</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				            </div><!-- /.pri-lst -->
				            <div class="tru-us">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/rating.png' ?>">
				                <h2>Trusted by more that 180000+ Users!</h2>
				                <p>More than 180k Websites, Blogs &amp; E-Commerce website are powered by our AMP making it the #1 Rated AMP plugin in WordPress Community.</p>
				                <a href="https://wordpress.org/plugins/super-progressive-web-apps/reviews/?filter=5" target="_blank">Read The Reviews</a>
				            </div>
				        </div><!--/ .amp-upg -->
				        <div class="ampfaq">
				            <h4>Frequently Asked Questions</h4>
				            <div class="faq-lst">
				                <div class="lt">
				                    <ul>
				                        <li>
				                            <span>Is there a setup fee?</span>
				                            <p>No. There are no setup fees on any of our plans</p>
				                        </li>
				                        <li>
				                            <span>What's the time span for your contracts?</span>
				                            <p>All the plans are year-to-year which are subscribed annually.</p>
				                        </li>
				                        <li>
				                            <span>What payment methods are accepted?</span>
				                            <p>We accepts PayPal and Credit Card payments.</p>
				                        </li>
				                        <li>
				                            <span>Do you offer support if I need help?</span>
				                            <p>Yes! Top-notch customer support for our paid customers is key for a quality product, so we’ll do our very best to resolve any issues you encounter via our support page.</p>
				                        </li>
				                        <li>
				                            <span>Can I use the plugins after my subscription is expired?</span>
				                            <p>Yes, you can use the plugins but you will not get future updates for those plugins.</p>
				                        </li>
				                    </ul>
				                </div>
				                <div class="rt">
				                    <ul>
				                        <li>
				                            <span>Can I cancel my membership at any time?</span>
				                            <p>Yes. You can cancel your membership by contacting us.</p>
				                        </li>
				                        <li>
				                            <span>Can I change my plan later on?</span>
				                            <p>Yes. You can upgrade or downgrade your plan by contacting us.</p>
				                        </li>
				                        <li>
				                            <span>Do you offer refunds?</span>
				                            <p>You are fully protected by our 100% Money Back Guarantee Unconditional. If during the next 14 days you experience an issue that makes the plugin unusable and we are unable to resolve it, we’ll happily offer a full refund.</p>
				                        </li>
				                        <li>
				                            <span>Do I get updates for the premium plugin?</span>
				                            <p>Yes, you will get updates for all the premium plugins until your subscription is active.</p>
				                        </li>
				                    </ul>
				                </div>
				            </div><!-- /.faq-lst -->
				            <div class="f-cnt">
				                <span>I have other pre-sale questions, can you help?</span>
				                <p>All the plans are year-to-year which are subscribed annually.</p>
				                <a href="https://superpwa.com/contact/'?utm_source=superpwa-plugin&utm_medium=addon-card'">Contact a Human</a>
				            </div><!-- /.f-cnt -->
				        </div><!-- /.faq -->
				    </div><!-- /. pvf -->
				</div>
			<?php } ?>




			</div>
		</div>
	</div>
	<?php
}