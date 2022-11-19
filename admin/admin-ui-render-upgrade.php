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
				            <h1><?php _e( 'Upgrade to SuperPWA Pro','super-progressive-web-apps'); ?></h1>
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
				                                    <h4><?php _e( 'Android APK APP Generator', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Easily generate Android APP (APK package) of your current PWA website.', 'super-progressive-web-apps' ); ?></p>
				                            </div>

				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Continuous Updates', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'We\'re continuously updating our premium features and releasing them.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
																		<div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Innovation', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'Be the first one to get the innovative features that we build in the future.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
																		<div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4><?php _e( 'Documentation', 'super-progressive-web-apps' ); ?></h4>
				                                </div>
				                                <p><?php _e( 'We create tutorials for every possible feature and keep it updated for you.', 'super-progressive-web-apps' ); ?></p>
				                            </div>
				                        </div><!-- /. fet -->
				                        <div class="pr-btn">
				                            <a href="#upgrade"><?php _e( 'Upgrade to Pro', 'super-progressive-web-apps' ); ?></a>
				                        </div><!-- /. pr-btn -->
				                    </div><!-- /. pr-fe -->
				                </div><!-- /.pr -->
				            </div><!-- /. pvf-cmp -->
				        </div><!-- /. pvf-cnt -->
				        <div id="upgrade" class="amp-upg">
				            <div class="upg-t">
				                <h2><?php _e( 'Let\'s Upgrade Your PWA', 'super-progressive-web-apps' ); ?></h2>
				                <span><?php _e( 'Choose your plan and upgrade in minutes!', 'super-progressive-web-apps' ); ?></span>
				            </div>
				            <div class="sp-pri-lst">
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=1" target="_blank">
				                        <h5><?php _e( 'PERSONAL', 'super-progressive-web-apps' ); ?></h5>
				                        <span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '99', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '99', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $59)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( '1 Site License', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( '1 year Updates', 'super-progressive-web-apps' ); ?> </span>
				                        <span class="sp-sv"><?php _e( 'Pro Features', 'super-progressive-web-apps' ); ?> </span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				                <div class="pri-tb rec">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=2" target="_blank">
				                        <h5><?php _e( 'MULTIPLE', 'super-progressive-web-apps' ); ?></h5>
				                        <span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '129', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '129', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $79)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( '3 Site License', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( '1 year Updates', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-sv"><?php _e( 'Save 78%', 'super-progressive-web-apps' ); ?></span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-rcm"><?php _e( 'RECOMMENDED', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=3" target="_blank">
				                        <h5><?php _e( 'WEBMASTER', 'super-progressive-web-apps' ); ?></h5>
										<span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '199', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '199', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $99)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( '10 Site License', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( 'Pro Features', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-sv"><?php _e( 'Save 83%', 'super-progressive-web-apps' ); ?></span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=4" target="_blank">
				                        <h5><?php _e( 'FREELANCER', 'super-progressive-web-apps' ); ?></h5>
										<span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '249', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '249', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $119)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( '25 Site License', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( 'Pro Features', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-sv"><?php _e( 'Save 90%', 'super-progressive-web-apps' ); ?></span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=5" target="_blank">
				                        <h5><?php _e( 'AGENCY', 'super-progressive-web-apps' ); ?></h5>
				               			<span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '499', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '499', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $199)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( 'Unlimited Site', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'E-mail Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( 'Pro Features', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-sv"><?php _e( 'UNLIMITED', 'super-progressive-web-apps' ); ?></span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				                <div class="pri-tb">
				                    <a href="https://superpwa.com/checkout?edd_action=add_to_cart&download_id=666&edd_options[price_id]=6" target="_blank">
				                        <h5><?php _e( 'LIFETIME', 'super-progressive-web-apps' ); ?></h5>
										<span class="d-amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '999', 'super-progressive-web-apps' ); ?></span>
				                        <span class="amt"><sup><?php _e( '$', 'super-progressive-web-apps' ); ?></sup><?php _e( '999', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s-amt"><?php _e( '(Save $199)', 'super-progressive-web-apps' ); ?></span>
				                        <span class="bil"><?php _e( 'Billed Annually', 'super-progressive-web-apps' ); ?></span>
				                        <span class="s"><?php _e( 'Unlimited Site', 'super-progressive-web-apps' ); ?></span>
				                        <span class="e"><?php _e( 'Tech Support', 'super-progressive-web-apps' ); ?></span>
				                        <span class="f"><?php _e( 'Pro Features', 'super-progressive-web-apps' ); ?></span>
				                        <span class="sp-sv"><?php _e( 'UNLIMITED', 'super-progressive-web-apps' ); ?></span>
				                        <span class="pri-by"><?php _e( 'Buy Now', 'super-progressive-web-apps' ); ?></span>
				                    </a>
				                </div>
				            </div><!-- /.pri-lst -->
				            <div class="tru-us">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/rating.png' ?>">
				                <h2><?php _e( 'Used by more than 40000+ Users!', 'super-progressive-web-apps' ); ?></h2>
				                <p><?php _e( 'More than 40k Websites, Blogs &amp; E-Commerce shops are powered by our SuperPWA making it the #1 Independent PWA plugin in WordPress.', 'super-progressive-web-apps' ); ?></p>
				                <a href="https://wordpress.org/support/plugin/super-progressive-web-apps/reviews/?filter=5" target="_blank"><?php _e( 'Read The Reviews', 'super-progressive-web-apps' ); ?></a>
				            </div>
				        </div><!--/ .amp-upg -->
				        <div class="ampfaq">
				            <h4><?php _e( 'Frequently Asked Questions', 'super-progressive-web-apps' ); ?></h4>
				            <div class="faq-lst">
				                <div class="lt">
				                    <ul>
				                        <li>
				                            <span><?php _e( 'Is there a setup fee?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'No. There are no setup fees on any of our plans', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'What\'s the time span for your contracts?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'All the plans are year-to-year which are subscribed annually except for lifetime plan.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'What payment methods are accepted?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'RWe accepts PayPal and Credit Card payments.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'Do you offer support if I need help?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'Yes! Top-notch customer support for our paid customers is key for a quality product, so we’ll do our very best to resolve any issues you encounter via our support page.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'Can I use the plugins after my subscription is expired?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'Yes, you can use the plugins but you will not get future updates for those plugins.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                    </ul>
				                </div>
				                <div class="rt">
				                    <ul>
				                        <li>
				                            <span><?php _e( 'Can I cancel my membership at any time?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'Yes. You can cancel your membership by contacting us.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'Can I change my plan later on?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'Yes. You can upgrade your plan by contacting us.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'Do you offer refunds?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'You are fully protected by our 100% Money Back Guarantee Unconditional. If during the next 14 days you experience an issue that makes the plugin unusable and we are unable to resolve it, we\'ll happily offer a full refund.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                        <li>
				                            <span><?php _e( 'Do I get updates for the premium plugin?', 'super-progressive-web-apps' ); ?></span>
				                            <p><?php _e( 'Yes, you will get updates for all the premium plugins until your subscription is active.', 'super-progressive-web-apps' ); ?></p>
				                        </li>
				                    </ul>
				                </div>
				            </div><!-- /.faq-lst -->
				            <div class="f-cnt">
				                <span><?php _e( 'I have other pre-sale questions, can you help?', 'super-progressive-web-apps' ); ?></span>
				                <p><?php _e( 'All the plans are year-to-year which are subscribed annually.', 'super-progressive-web-apps' ); ?></p>
				                <a href="https://superpwa.com/contact/'?utm_source=superpwa-plugin&utm_medium=addon-card'"><?php _e( 'Contact a Human', 'super-progressive-web-apps' ); ?></a>
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
