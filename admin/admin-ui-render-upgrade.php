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
	<style type="text/css">
		.fp-wr{width:95%;margin:0 auto;position:relative}.fp-img{width:100%;margin:0 auto;text-align:center;position:relative;line-height:0}.fp-img img{position:relative}.ov{background:#000;opacity:.8;bottom:0;left:0;position:absolute;right:0;top:0;border-radius:10px}.fp-cnt{position:absolute;top:40px;bottom:0;left:40px;right:40px;margin:0 auto;text-align:center}.fp-cnt h1{font-size:50px;color:#fff;font-weight:600}.fp-cnt p{margin-top:5px;color:#fff;font-size:23px;padding:0 100px;line-height:1.4}.fp-cnt .buy{text-decoration:none;color:#fff;padding:10px 35px;border-radius:40px;font-size:20px;line-height:1.4;display:inline-block;background:#56ab2f;background:-webkit-linear-gradient(to right,#a8e063,#56ab2f);background:linear-gradient(to right,#a8e063,#56ab2f);font-weight:700;text-shadow:1px 1px 1px #27a52c}.pvf{position:relative;top:-16px;border:1px solid #eee;padding-bottom:40px}.ext{display:grid;grid-template-columns:1fr 1fr 1fr;background:#f9f9f9;padding:45px 0 45px 25px}.ex-1{width:250px}.ex-1 h4{margin:15px 0 12px 0;font-size:18px;color:#222;font-weight:500}.ex-1 p{font-size:14px;color:#555;font-weight:400;margin:0}.e-1 img{width:65px!important}.e-2 img{width:45px!important}.e-3 img{width:49px!important}.pvf-cnt{width:100%;display:inline-block}.pvf-tlt{text-align:center;width:100%;margin:70px 0 60px 0}.pvf-tlt h2{font-size:36px;line-height:1.4;color:#000;font-weight:500;margin:0}.pvf-tlt span{font-size:16px;color:#000;margin-top:15px;display:inline-block;position:relative;top:4px}.pvf-cmp{display:grid;grid-template-columns:1fr 2fr}.fr{border-right:1px solid #eee}.fr h1,.pr h1{font-size:36px;font-weight:700;line-height:1.5;border-bottom:1px solid #efefef;padding:0 0 20px 35px}.pr h1{padding-left:50px}.fr-fe{color:#222;padding-top:10px}.fe-1{padding:22px 35px 35px 35px}.fe-1 h4{margin:0 0 10px 0;font-size:20px;line-height:1.4;font-weight:400;color:#000}.fe-1 p{font-size:15px;line-height:1.4;margin:0;color:#333}.pr-fe{padding:34px 35px 35px 35px}.pr-fe span{font-family:georgia;font-size:16px;font-weight:700;color:#000;font-style:italic;line-height:1.3}.fet{width:100%;display:grid;grid-template-columns:1fr 1fr;grid-gap:25px;margin-top:40px}.fe-2{color:#222}.fe-t img{width:22px!important;display:inline-block;vertical-align:middle}.fe-t h4{margin:0;display:inline-block;vertical-align:middle;font-size:19px;color:#000;font-weight:400;line-height:1.4;padding-left:8px}.fe-2 p{font-size:15px;line-height:1.4;margin:0;color:#555;padding-top:8px}.pr-btn{width:100%;display:inline-block;text-align:center;margin:50px 0 25px 0}.pr-btn a{text-decoration:none;color:#fff;padding:12px 35px 17px 35px;display:inline-block;border-radius:5px;font-size:28px;font-weight:500;line-height:1.2;background:-webkit-linear-gradient(to right,#eb3349,#f45c43);font-weight:600;background:#eb3349;background:linear-gradient(to right,#eb3349,#f45c43);margin-top:0;box-shadow:0 .15em .65em 0 rgba(0,0,0,.25)}.amp-upg{background:#f5f5f5;padding:60px 10px 0 10px}.upg-t{text-align:center;color:#222}.upg-t h2{margin:0;font-size:35px;color:#060606;line-height:1.3;font-weight:500}.upg-t>span{font-size:14px;line-height:1.2;margin-top:15px;display:inline-block;color:#666}.pri-lst{width:100%;display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;margin-top:70px;grid-gap:1px;box-shadow:0 10px 15px 1px #ddd}.pri-tb{background:#fff;text-align:center;border:1px solid #f9f9f9;position:relative}.pri-tb:hover{border:1px solid #489bff}.pri-tb a:hover .pri-by{background:#1e8fff}.pri-tb a{display:inline-block;text-decoration:none;color:#222;padding:20px 12px}.pri-tb h5{margin:0 0 20px 0;font-size:13px;line-height:1.2;letter-spacing:2px;font-weight:400;color:#000}.pri-tb span{display:inline-block}.pri-tb .amt{font-size:40px;color:#1e8fff;font-weight:500;margin-bottom:20px;display:block}.pri-tb .d-amt{font-size:24px;color:#666;font-weight:500;margin-bottom:15px;display:none;text-decoration:line-through}.d-amt sup{line-height:0;position:relative;top:7px}.pri-tb .s-amt{font-size:13px;color:#4caf50;font-weight:500;margin-bottom:10px;display:none}.pri-tb .amt sup{font-size:22px;padding:0 4px 0 0;position:relative;top:7px}.pri-tb .bil{color:#aaa;font-size:12px;margin-bottom:20px}.pri-tb .e,.pri-tb .f,.pri-tb .s{font-size:14px;margin-bottom:15px;color:#3b4750}.pri-tb .sv{font-size:12px;color:#fff;background:#4caf50;margin:0 auto;padding:1px 7px 2px 7px;border-radius:45px}.pri-by{font-size:15px;line-height:1.2;background:#333;border-radius:2px;padding:9px 18px 10px 18px;display:inline-block;color:#fff;margin-top:29px;font-weight:500}.pri-lst .rec{box-shadow:0 1px 40px 0 #ccc;background:#fff;z-index:9;margin-top:-20px;position:relative}.pri-lst .rec:hover .rcm{background:#489bff;color:#fff}.pri-lst .rec .pri-by{background:#1e8fff}.rcm{background:#dedede;color:#888;position:absolute;top:-20px;left:0;right:-1px;bottom:auto;padding:2px 0;font-size:11px;letter-spacing:2px}.tru-us{text-align:center;padding:60px 0;margin:0 auto;font-size:16px;color:#222}.tru-us h2{margin:20px 0 0 0;font-size:28px;font-weight:500}.tru-us p{font-size:17px;margin:19px 15% 18px 15%;color:#666;line-height:29px}.tru-us a{font-size:18px;color:#489bff;text-decoration:none;font-weight:400}.ampfaq{width:100%;margin:25px 0}.ampfaq h4{margin:0;text-align:center;font-size:20px;font-weight:500;color:#333}.faq-lst{margin-top:50px;display:grid;grid-template-columns:1fr 1fr}.lt{padding-left:50px}.lt,.rt{width:70%}.lt ul,.rt ul{margin:0}.lt ul li,.rt ul li{color:#222;margin-bottom:30px!important}.lt span,.rt span{font-size:17px;font-weight:500;margin-bottom:6px;display:inline-block}.lt p,.rt p{font-size:15px;margin:0}.f-cnt{text-align:center;margin-top:20px;color:#222}.f-cnt span{font-size:17px;margin:8px 0;font-weight:500}.f-cnt p{font-size:15px;margin:6px 0}.f-cnt a{background:#333;color:#fff;padding:15px 30px;text-decoration:none;font-size:18px;font-weight:500;display:inline-block;margin-top:15px}@media(max-width:1366px){.amp-upg{padding:60px 0 0 0}.fp-cnt p{line-height:29px;font-size:20px}}@media(max-width:1280px){.fp-cnt{top:1%}}@media(max-width:768px){.ext{grid-template-columns:1fr;grid-gap:30px 0;padding:30px}.pvf-tlt h2{font-size:26px}.pvf-cmp{grid-template-columns:1fr}.pr-btn a{font-size:22px}.pri-lst{grid-template-columns:1fr 1fr 1fr}.fp-cnt p{line-height:1.5;font-size:16px;margin-top:15px;padding:0 20px}.fp-cnt .buy{font-size:16px;padding:8px 30px}.fp-cnt{top:15px}.fp-cnt h1{font-size:30px}.ex-1{width:100%}.faq-lst{grid-template-columns:1fr}.rt{padding-left:50px}}
/*** Menu Types CSS ***/
/*** Related Post Desings ***/
	</style>
	<div class="wrap">
		<h1><?php _e( 'Upgrade for', 'super-progressive-web-apps' ); ?> SuperPWA <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		<p><?php _e( 'Upgrade the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
				<?php do_action("admin_upgrade_license_page"); ?>


				<div class="fp-wr">
				    <div class="fp-img">
				        <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/Bitmap.png' ?>">
				        <span class="ov"></span>
				    </div>
				    <div class="fp-cnt">
				            <h1>Upgrade to Pro</h1>
				            <p>Take your AMP to the next level with more beautiful themes, great extensions and more powerful features.</p>
				            <a class="buy" href="#upgrade">BUY NOW</a>
				    </div>
				    <div class="pvf">
				        <div class="ext">
				            <div class="ex-1 e-1">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-1.png' ?>">
				                <h4>Extensions</h4>
				                <p>Includes a suite of advanced features like Ads, Email Optin, Contact Forms, E-Commerce, CTA, Cache and 15+ premium extensions.</p>
				            </div>
				            <div class="ex-1 e-2">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-2.png' ?>">
				                <h4>Designs</h4>
				                <p>Wide Variety of AMP Theme Designs included with AMP Layouts. We are dedicated to release 2-3 new designs every month.</p>
				            </div>
				            <div class="ex-1 e-3">
				                <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/ex-3.png' ?>">
				                <h4>Dedicated Support</h4>
				                <p>Get private ticketing help from our full-time staff who helps you with the technical issues.</p>
				            </div>
				        </div><!-- /. ext -->
				        <div class="pvf-cnt">
				            <div class="pvf-tlt">
				                <h2>Compare Pro vs. Free Version</h2>
				                <span>See what you'll get with the professional version</span>
				            </div>
				            <div class="pvf-cmp">
				                <div class="fr">
				                    <h1>FREE</h1>
				                    <div class="fr-fe">
				                        <div class="fe-1">
				                            <h4>Continious Development</h4>
				                            <p>We take bug reports and feature requests seriously. We’re continiously developing &amp; improve this product for last 2 years with passion and love.</p>
				                        </div>
				                        <div class="fe-1">
				                            <h4>300+ Features</h4>
				                            <p>We're constantly expanding the plugin and make it more useful. We have wide variety of features which will fit any use-case.</p>
				                        </div>
				                        <div class="fe-1">
				                            <h4>Design</h4>
				                            <p>We have 4 Built in themes for AMP which elevates your AMP exeprience.</p>
				                        </div>
				                        <div class="fe-1">
				                            <h4>Technical Support</h4>
				                            <p>We have a full time team which helps you with each and every issue regarding AMP.</p>
				                        </div>
				                    </div><!-- /. fr-fe -->
				                </div><!-- /. fr -->
				                <div class="pr">
				                    <h1>PRO</h1>
				                    <div class="pr-fe">
				                        <span>Everything in Free, and:</span>
				                        <div class="fet">
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Advertisement</h4>
				                                </div>
				                                <p>Advanced Ad slots, Incontent ads &amp; Supports all Ad networks.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>AMP Cache</h4>
				                                </div>
				                                <p>Revolutionary cache system for AMP which makes it insanely fast.</p>
				                            </div>
				                            <div class="fe-2">
				                                <div class="fe-t">
				                                    <img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/tick.png' ?>">
				                                    <h4>Contact Forms</h4>
				                                </div>
				                                <p>Gravity Forms and Contact form 7 Support for the AMP.</p>
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




			</div>
		</div>
	</div>
	<?php
}