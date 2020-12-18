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
		<link rel='stylesheet' href='<?php echo SUPERPWA_PATH_SRC . 'admin/css/upgrade-ui.css?ver='.SUPERPWA_VERSION ?>' media='all' />

	<div class="wrap">
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">

			<div id="the-list">
				<?php
				if(defined('SUPERPWA_PRO_VERSION')){
				 do_action("admin_upgrade_license_page");
				}else{ ?>


				<div class="fp-wr">
				    <div class="sp-fp-img">
				        <span class="sp_ov"></span>
				    </div>
				    <div class="sp-fp-cnt">
				            <h1><?php _e( 'Upgrade to SuperPWA Pro'); ?></h1>
          <p><?php _e( 'Take your PWA to the next level with SuperPWA PRO version.', 'super-progressive-web-apps' ); ?></p>
				            <a class="buy" href="#upgrade"><?php _e( 'Purchase now', 'super-progressive-web-apps' ); ?></a>
				    </div>
				    <div class="pvf">
				        <div class="ext">
				            <div class="ex-1 e-1">
				                <h4><?php _e( 'Premium Features', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'The premium features of SuperPWA enhances your app and takes it to a next level to help you reach more engagement and personalization with your user.', 'super-progressive-web-apps' ); ?></p>
				            </div>
				            <div class="ex-1 e-2">
				                <h4><?php _e( 'Continuous Innovation', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'We are planning to continiously build premium features and release them. We have a roadmap and we listen to our customers to turn their feedback into reality.', 'super-progressive-web-apps' ); ?></p>
				            </div>
				            <div class="ex-1 e-3">
				                <h4><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></h4>
				                <p><?php _e( 'Get private ticketing help from our full-time technical staff & developers who helps you with the technical issues.', 'super-progressive-web-apps' ); ?></p>
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
				                            <h4><?php _e( '50+ Features', 'super-progressive-web-apps' ); ?></h4>
				                            <p><?php _e( 'We\'re constantly expanding the plugin and make it more useful. We have wide variety of features which will fit any use-case.', 'super-progressive-web-apps' ); ?></p>
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
				                                    <h4><?php _e( 'Call to Action feature', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Easily gives notification banner your users to Add to Homescreen on website.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Advanced Tech Support', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'High skilled team will go above & beyond to help you with issues.', 'super-progressive-web-apps' ); ?></p>
				                            </div>

				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Android APK APP Generator</h4>
				                                </div>
				                                <p>Easily generate Android APP (APK package) of your current PWA website.</p>
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
																		<div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Documentation</h4>
				                                </div>
				                                <p>We create tutorials for every possible feature and keep it updated for you.</p>
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
				                <h2>Let's Upgrade Your PWA</h2>
				                <span>Choose your plan and upgrade in minutes!</span>
				            </div>
				            <div class="sp-pri-lst">
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=1" target="_blank">
				                        <h5>PERSONAL</h5>
				                        <span class="d-amt"><sup>$</sup>99</span>
				                        <span class="amt"><sup>$</sup>99</span>
				                        <span class="s-amt">(Save $59)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">1 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">1 year Updates </span>
				                        <span class="sp-sv">Pro Features </span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb rec">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=2" target="_blank">
				                        <h5>MULTIPLE</h5>
				                        <span class="d-amt"><sup>$</sup>129</span>
				                        <span class="amt"><sup>$</sup>129</span>
				                        <span class="s-amt">(Save $79)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">3 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">1 year Updates</span>
				                        <span class="sp-sv">Save 78%</span>
				                        <span class="pri-by">Buy Now</span>
				                        <span class="sp-rcm">RECOMMENDED</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=3" target="_blank">
				                        <h5>WEBMASTER</h5>
				                        <span class="d-amt"><sup>$</sup>199</span>
				                        <span class="amt"><sup>$</sup>199</span>
				                        <span class="s-amt">(Save $99)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">10 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sp-sv">Save 83%</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=4" target="_blank">
				                        <h5>FREELANCER</h5>
				                        <span class="d-amt"><sup>$</sup>249</span>
				                        <span class="amt"><sup>$</sup>249</span>
				                        <span class="s-amt">(Save $119)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">25 Site License</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sp-sv">Save 90%</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=5" target="_blank">
				                        <h5>AGENCY</h5>
				                        <span class="d-amt"><sup>$</sup>499</span>
				                        <span class="amt"><sup>$</sup>499</span>
				                        <span class="s-amt">(Save $199)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">Unlimited Site</span>
				                        <span class="e">E-mail support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sp-sv">UNLIMITED</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=6" target="_blank">
				                        <h5>LIFETIME</h5>
				                        <span class="d-amt"><sup>$</sup>999</span>
				                        <span class="amt"><sup>$</sup>999</span>
				                        <span class="s-amt">(Save $199)</span>
				                        <span class="bil">Billed Annually</span>
				                        <span class="s">Unlimited Site</span>
				                        <span class="e">Tech Support</span>
				                        <span class="f">Pro Features</span>
				                        <span class="sp-sv">UNLIMITED</span>
				                        <span class="pri-by">Buy Now</span>
				                    </a>
				                </div>
				            </div><!-- /.pri-lst -->
				            <div class="tru-us">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/rating.png' ?>">
				                <h2>Used by more than 40000+ Users!</h2>
				                <p>More than 40k Websites, Blogs &amp; E-Commerce shops are powered by our SuperPWA making it the #1 Independent PWA plugin in WordPress.</p>
				                <a href="https://wordpress.org/support/plugin/super-progressive-web-apps/reviews/?filter=5" target="_blank">Read The Reviews</a>
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
				                            <p>All the plans are year-to-year which are subscribed annually except for lifetime plan.</p>
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
				                            <p>Yes. You can upgrade your plan by contacting us.</p>
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
