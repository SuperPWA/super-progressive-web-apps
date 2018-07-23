=== Super Progressive Web Apps ===
Contributors: arunbasillal, josevarghese, superpwa
Donate link: http://millionclues.com/donate/
Tags: pwa, progressive web apps, manifest, web manifest, android app, chrome app, add to homescreen, mobile web
Requires at least: 3.6.0
Tested up to: 4.9.7
Requires PHP: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SuperPWA helps you convert your WordPress website into a Progressive Web App instantly.

== Description ==

Progressive Web Apps (PWA) is a new technology that combines the best of mobile web and the best of mobile apps to create a superior mobile web experience. They are installed on the phone like a normal app (web app) and can be accessed from the home screen. 

Users can come back to your website by launching the app from their home screen and interact with your website through an app-like interface. Your return visitors will experience almost-instant loading times and enjoy the great performance benefits of your PWA!

Super Progressive Web Apps makes it easy for you to convert your WordPress website into a Progressive Web App instantly!

Once SuperPWA is installed, users browsing your website from a supported mobile device will see a "Add To Home Screen" notice (from the bottom of the screen) and will be able to 'install your website' on the home screen of their device. Every page visited is stored locally on their device and will be available to read even when they are offline!

SuperPWA is easy to configure, it takes less than a minute to set-up your Progressive Web App! SuperPWA does a clean uninstall, by removing every database entry and file that it creates. In fact, none of the default settings are saved to the database until you manually save it the first time. Go ahead and give it a try.

And the best part? If you ever get stuck, we are here to watch your back! [Open a support](https://wordpress.org/support/plugin/super-progressive-web-apps) ticket if you have a question or need a feature. We are super excited to hear your feedback and we want to genuinely help you build the best Progressive Web App for your WordPress website!

#### Quick Demo? 

* Open up [SuperPWA.com](https://superpwa.com/?utm_source=wordpress.org&utm_medium=description-demo) in a supported device. 
* Add the website to your home screen either from the Add to Home Screen prompt (Chrome for Android) or from the browser menu. 
* Open the app from your home screen and you will see the splash screen. 
* Turn off your data and wifi to go offline and open up the app. You will still be able to see the app and browse the pages you have already visited. 
* Browse to a page that you haven't visited before. The offline page will be displayed. 

#### Thank You PWA Enthusiasts! 

We are humbled by the feedback from the community. Thanks to everyone who believed in us and tried our plugin. Your feedback has been invaluable and we have learned a lot from your experience. Thank you for your love and support and we hope to return the love by striving to bring you the best ever Progressive Web Apps plugin for WordPress!

### What's in the box

Here are the current features of Super Progressive Web Apps: 

* Generate a manifest for your website and add it to the head of your website.
* Set the application icon for your Progressive Web App. 
* Set the background color for the splash screen of your Progressive Web App. 
* Your website will show the "Add to home screen" notice when accessed in a supported browser.
* Aggressive caching of pages using CacheStorage API.
* Pages once cached are served even if the user is offline. 
* Set custom offline page: Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.
* New in version 1.2: Support for theme-color meta property. Change the color of browser address bar of Chrome, Firefox OS and Opera to match your website colors. 
* New in version 1.2: Now you can edit the Application Name and Application Short name.
* New in version 1.2: Set the start page of your PWA.
* New in version 1.2: Set Accelerated Mobile Pages (AMP) version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* New in version 1.3: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* New in version 1.3: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* New in version 1.3: Improved in-browser service worker update handling.
* New in version 1.4: You can now set the default orientation of your PWA. Choose from "any" (Follow Device Orientation), "Portrait" and "Landscape".
* New in version 1.4: You can now set the theme_color property in the manifest.
* New in version 1.5: OneSignal integration for Push notifications.
* New in version 1.6: WordPress Multisite Network compatibility. 
* New in version 1.7: Add-Ons for SuperPWA is here! Ships with [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=description) to track visits coming from your PWA.
* New in version 1.8: Compatibility issues with OneSignal are now resolved! 
* New in version 1.8: New Add-On: [Apple Touch Icons](https://superpwa.com/addons/apple-touch-icons/?utm_source=wordpress.org&utm_medium=description) that sets your app icons as Apple Touch Icons. 

**Upcoming features:**

* Offline Indicator Notice.

### Progressive Web App Minimum Requirements

Progressive Web Apps require that your WordPress website is served from a secure origin i.e. your website should be HTTPS and not HTTP. If your website isn't HTTPS, please contact your host about it. You can also [ask us](https://wordpress.org/support/plugin/super-progressive-web-apps) if you need help.

### Device and Browser Support For PWA

Progressive web apps need browsers that support manifests and service workers. Currently Google Chrome (version 57+), Chrome for Android (62), Mozilla Firefox (57), Firefox for Android (58) are the major browsers that support PWA. 

The list is fast growing and is likely to be supported in most major browsers by the end of this year.

### How To Convert Your WordPress Website Into A Progressive Web App

#### WordPress Installation

* Visit WordPress Admin > Plugins > Add New
* Search for 'Super Progressive Web Apps'
* Click "Install Now" and then "Activate" Super Progressive Web Apps

To install manually:

* Upload super-progressive-web-apps folder to the /wp-content/plugins/ directory on your server
* Go to WordPress Admin > Plugins
* Activate Super Progressive Web Apps plugin from the list.

#### Customizing Your Progressive Web App

Your Progressive Web App should be ready to test with the default settings on activation. You can customize it further and make it truly your own.

* Go to WordPress Admin > SuperPWA
* Set a Background Color for the splash screen to be shown when your PWA is opened on a mobile device.
* Set the Application Icon. This will be the icon of your PWA when it is added to the homescreen in a mobile device. The icon must be a PNG image and exactly 192 x 192 pixels in size.
* Set the Offline Page. This page will be displayed if the user is offline and the page he requested is not cached already. Ideally you should create a dedicated WordPress page and set it here. Within the page you create, you could add a note that reads, "It looks like you are offline and the page you requested is not available right now. Please check back again once you are online.". 
* Click "Save Settings".

#### Testing Your Progressive Web App

* Open a supported browser in a supported device (for eg: Chrome for Android (62 or higher) in an Android Phone)
* Enter your website and wait till it fully loads
* You should see a pop-up that has your Application Icon and a button that reads "ADD TO HOME SCREEN".
* Click on it and your PWA will be added to your home screen. Wait for the install to complete. 
* Go to your home screen and open your PWA. Browse into a few pages if you like. Close the App.
* Disconnect from the internet and now open your PWA again. You should be able to see all the pages that you previously browsed. 
* Try visiting a page that you did not visit before. You should see the page you set as your "Offline Page" in the settings of SuperPWA. 

#### Troubleshooting Your Progressive Web App

Uh, oh. Your PWA did not work as expected? You do not see the "Add to Home Screen" notice?

* Make sure your website has a SSL certificate installed. i.e. your website should be https instead of http (as in https://your-domain.com).
* Make sure you are using a supported device and a supported browser. Refer to the "Device and Browser Support For PWA" list above.
* Make sure your Application Icon and Splash Screen Icon's are of PNG format and 192px X 192px and 512px X 512px in size respectively. 
* Clear the browser cache and try again. In Chrome for Android, go to Settings > Privacy > "Clear browsing data".
* If the application icon does not update after first install, delete the PWA from your phone, clear browser cache and install again. (We are working on making it better.) 
* Create a [new support ticket](https://wordpress.org/support/plugin/super-progressive-web-apps) and share a link to your website. We will take a look and figure it out for you.

### Feature Requests, Issues, Pull Requests

Here is our repository on [GitHub](https://github.com/SuperPWA/Super-Progressive-Web-Apps). Send us your pull requests, feature requests or issues, if any.

### About us

We are a duo who got excited about the idea. Our mission is simple: Help you build an awesome PWA that your users would want to have on their home screen.

When we first heard about PWA we wanted to learn everything about it. We have spent countless hours learning and wants to share it with the world. 

Please give us your constructive feedback and support. 

== Installation ==

To install this plugin:

1. Install the plugin through the WordPress admin interface, or upload the plugin folder to /wp-content/plugins/ using FTP.
2. Activate the plugin through the 'Plugins' screen in WordPress. 
3. Go to WordPress Admin > SuperPWA

== Frequently Asked Questions ==

If you have any questions, please ask it on the [support forum](https://wordpress.org/support/plugin/super-progressive-web-apps).

= Will Progressive Web Apps work on iOS devices? =

Starting with Safari for iOS 11.3, Apple devices offer partial support for PWA's. However, there is no native Add To Home Screen prompt just yet. You can add your app by tapping "Add to Home Screen" button in the share menu of the browser ( look for the square icon with an up arrow in the foreground ).

Just like you, we are eagerly awaiting the upcoming releases and we hope to see better compatibility in the coming months. 

= How To Customize Splash Screen = 

You can easily change the icon and the background color in SuperPWA > Settings. 

Further customizations are not available right now, not because of any limitation of SuperPWA, but because they are not available in the PWA technology. When more options come up in the future, we will add them to SuperPWA then. 

= How To Track Visits Originating From Your Progressive Web App = 

You can track visits from your PWA in your analytics software (for e.g. Google Analytics) using the UTM Tracking add-on of SuperPWA. Go to SuperPWA > Add-Ons and activate UTM Tracking. Then in SuperPWA > UTM Tracking, you can set the UTM parameters as needed. Please [refer the documentation](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=description-faq) for further information. 

= GDPR Compliance =

SuperPWA does not collect or store user data, nor does it set cookies or store tracking data. Content visited by users from your PWA is stored in the user's own device, in the cache of the browser. This is very similar to how modern browsers caches content offline for faster browsing. 

With the UTM Tracking Add-On, you will be able to differentiate the visits originating from your PWA in your analytics software. You may have to include this in your privacy policy. Please note that SuperPWA does not track the visits, we just help you add the UTM parameters to the URL of the Start Page of your app so that third party analytics tools can differentiate the visits. 

Feel free to get in touch if you have any questions. 

== Screenshots ==

1. Settings page in WordPress Admin > SuperPWA > Settings

== Changelog ==

= 1.9 =
* Date: 
* Tested with WordPress 4.9.7. 
* Enhancement: SuperPWA is now translated to French thanks to [@romainvincent](https://profiles.wordpress.org/romainvincent). 
* Enhancement: Added compatibility with Google Chrome 68 Mini Info-Bar. 
* Enhancement: Added support for images in offline page. Images added to offline page are now cached during service worker activation. 
* Enhancement: Improved the service worker installation routine to handle invalid entities in the service worker dependencies. 
* Bug Fix: Fixed issue where translation files in /languages/ folder was not being loaded. 

= 1.8.1 =
* Date: [05.June.2018](https://superpwa.com/push-notifications-are-here-again/?utm_source=wordpress.org&utm_medium=changelog#1.8.1)
* Enhancement: Added an admin notice with [instructions for OneSignal integration](https://superpwa.com/doc/setup-onesignal-with-superpwa/?utm_source=wordpress.org&utm_medium=changelog). 
* Enhancement: Updated console log message for URLs excluded from cache for better clarity. 

= 1.8 =
* Date: [31.May.2018](https://superpwa.com/push-notifications-are-here-again/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 4.9.6. 
* New Add-On: Apple Touch Icons. Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices. 
* Enhancement: Added support for Add to Home Screen prompt for Chrome 68 and beyond. 
* Enhancement: Better add-on activation and deactivation by hooking onto admin_post action. 
* Enhancement: Attempt to generate manifest and service worker automatically on visiting the SuperPWA settings page after adjusting root folder permissions. 
* Enhancement: Generated a .pot file with all strings for translation. You can also translate SuperPWA to your language by visiting [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/super-progressive-web-apps) 
* Bug Fix: Compatibility issues with OneSignal are resolved for single installs. 
* Bug Fix: Updated plugin action links and admin notices with the correct admin menu link. 

= 1.7.1 =
* Date: 05.May.2018
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context".

= 1.7 =
* Date: [03.May.2018](https://superpwa.com/introducing-add-ons-for-superpwa/?utm_source=wordpress.org&utm_medium=changelog)
* Minimum required WordPress version is now 3.6.0 (previously 3.5.0).
* New Feature: Add-Ons for SuperPWA is here!
* New Feature: SuperPWA is now a top-level menu to accommodate for the Add-Ons sub-menu page.
* New Feature: Add UTM Tracking parameters to the Start URL with the [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=changelog).
* Enhancement: Service worker URLs are now relative to accommodate for domain mapped Multisites.
* Bug Fix: Incorrect start_url when WordPress is installed in a folder, or when inner pages are used as start_url.
* Bug Fix: Incorrect manifest and service worker URLs when WordPress is installed in a folder. 

= 1.6 =
* Date: [23.April.2018](https://superpwa.com/1-6-released-multisite-network-support/?utm_source=wordpress.org&utm_medium=changelog)
* New Feature: WordPress Multisite Network Compatibility. One of the most requested features for SuperPWA is now here! Thanks [@juslintek](https://wordpress.org/support/topic/add-manifest-json-support-for-multisite/#post-9998629) for doing a major share of the heavy lifting.
* New Feature: Added description to the manifest. You can now include a brief description of what your app is about. 
* Enhancement: Moved manifest to the very top of wp_head for better compatibility with some browsers.
* Enhancement: Improved the file and folder naming, organization, and inline documentation for better readability.
* Enhancement: Force https:// on all assets and dependencies solving the problem of http:// URLs in manifest and service worker once and for all.
* Enhancement: Relative URL for manifest for out of the box compatibility with CDN's.
* Enhancement: Removed forcing of trailing slash on manifest and service worker URLs for better compatibility. 

= 1.5 =
* Date: 18.March.2018
* New Feature: OneSignal integration for Push notifications.
* Enhancement: Moved manifest to the top of wp_head for easier detection by browsers. 
* Enhancement: Added support for custom AMP endpoints for AMP for WordPress and AMP for WP.
* Enhancement: Added UI notice when using AMP for WordPress to warn user not to use the AMP version of start page if the start page is the homepage, the blog index, or the archives page.

= 1.4 =
* Date: [21.February.2018](https://wordpress.org/support/topic/you-asked-and-we-listened-superpwa-1-4-ships-with-two-user-feature-requests/)
* New Feature: Added UI for default orientation of your PWA. Orientation can now be set as "any", "portrait" or "landscape". [Feature request from @doofustoo](https://wordpress.org/support/topic/almost-perfect-335/).
* New Feature: Added UI for theme_color property in manifest. [Feature request from @krunalsm](https://wordpress.org/support/topic/diffrent-theme_color-and-background_color/).
* Enhancement: Improved compatibility with all major Accelerated Mobile Pages (AMP) plugins.
* Enhancement: Improved handling of external resources. 

= 1.3.1 =
* Date: 15.February.2018
* Enhancement: Improved how Start Page url is handled in the service worker to be compatible with plugins that force SSL. 
* Enhancement: Improved how start_url is handled in the manifest.
* Enhancement: Better handling of external resources.

= 1.3 =
* Date: 10.February.2018
* New Feature: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* Enhancement: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* Enhancement: Improved in-browser service worker update handling.
* Enhancement: Added automatic upgrade of manifest and service worker on plugin upgrade.
* Bug Fix: Only GET requests are served from the cache now. Fixes [this](https://wordpress.org/support/topic/errors-in-firefox-and-chrome/).

= 1.2 =
* Date: 06.February.2018
* New Feature: Support for theme-color.
* New Feature: Now you can edit the Application Name and Application Short name.
* New Feature: Set the start page of your PWA.
* New Feature: Set AMP version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* UI Improvement: Better organization of plugin settings. More intuitive with inline help. 
* UI Improvement: Added admin notice with a link to settings page on plugin activation.
* UI Improvement: Added checks for manifest, service worker and HTTPS and display the status neatly in the UI.
* Bug Fix: Fix a parse error that showed up only on PHP 5.3. 

= 1.1.1 =
* Date: 30.January.2018
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". PHP manual says "Prior to PHP 5.5, empty() only supports variables; anything else will result in a parse error."

= 1.1 =
* Date: 28.January.2018
* New Feature: Aggressive caching of pages using CacheStorage API.
* New Feature: Pages once cached are served even if the user is offline. 
* New Feature: Set custom offline page. Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.

= 1.0 =
* Date: 22.January.2018
* First release of the plugin.

== Upgrade Notice ==

= 1.8.1 =
* Enhancement: Added an admin notice with instructions for OneSignal integration. 
* Enhancement: Updated console log message for URLs excluded from cache for better clarity. 

= 1.8 =
* Tested with WordPress 4.9.6. 
* New Add-On: Apple Touch Icons. Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices. 
* Enhancement: Added support for Add to Home Screen prompt for Chrome 68 and beyond. 
* Enhancement: Better add-on activation and deactivation by hooking onto admin_post action. 
* Enhancement: Attempt to generate manifest and service worker automatically on visiting the SuperPWA settings page after adjusting root folder permissions. 
* Enhancement: Generated a .pot file with all strings for translation. You can also translate SuperPWA to your language by visiting translate.wordpress.org/projects/wp-plugins/super-progressive-web-apps 
* Bug Fix: Compatibility issues with OneSignal are resolved for single installs. 
* Bug Fix: Updated plugin action links and admin notices with the correct admin menu link. 

= 1.7.1 =
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context".

= 1.7 =
* Minimum required WordPress version is now 3.6.0 (previously 3.5.0).
* New Feature: Add-Ons for SuperPWA is here!
* New Feature: SuperPWA is now a top-level menu to accommodate for the Add-Ons sub-menu page.
* New Feature: Add UTM Tracking parameters to the Start URL with the [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=upgrade-notice).
* Enhancement: Service worker URLs are now relative to accommodate for domain mapped Multisites.
* Bug Fix: Incorrect start_url when WordPress is installed in a folder, or when inner pages are used as start_url.
* Bug Fix: Incorrect manifest and service worker URLs when WordPress is installed in a folder. 

= 1.6 =
* New Feature: WordPress Multisite Network Compatibility. One of the most requested features for SuperPWA is now here! Thanks [@juslintek](https://wordpress.org/support/topic/add-manifest-json-support-for-multisite/#post-9998629) for doing a major share of the heavy lifting.
* New Feature: Added description to the manifest. You can now include a brief description of what your app is about. 
* Enhancement: Moved manifest to the very top of wp_head for better compatibility with some browsers.
* Enhancement: Improved the file and folder naming, organization, and inline documentation for better readability.
* Enhancement: Force https:// on all assets and dependencies solving the problem of http:// URLs in manifest and service worker once and for all.
* Enhancement: Relative URL for manifest for out of the box compatibility with CDN's.
* Enhancement: Removed forcing of trailing slash on manifest and service worker URLs for better compatibility. 

= 1.5 =
* New Feature: OneSignal integration for Push notifications.
* Enhancement: Moved manifest to the top of wp_head for easier detection by browsers. 
* Enhancement: Added support for custom AMP endpoints for AMP for WordPress and AMP for WP.
* Enhancement: Added UI notice when using AMP for WordPress to warn user not to use the AMP version of start page if the start page is the homepage, the blog index, or the archives page.

= 1.4 =
* New Feature: Added UI for default orientation of your PWA. Orientation can now be set as "any", "portrait" or "landscape".
* New Feature: Added UI for theme_color property in manifest.
* Enhancement: Improved compatibility with all major Accelerated Mobile Pages (AMP) plugins.
* Enhancement: Improved handling of external resources. 

= 1.3.1 =
* Enhancement: Improved how Start Page url is handled in the service worker to be compatible with plugins that force SSL. 
* Enhancement: Improved how start_url is handled in the manifest.
* Enhancement: Better handling of external resources.

= 1.3 =
* New Feature: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* Enhancement: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* Enhancement: Improved in-browser service worker update handling.
* Enhancement: Added automatic upgrade of manifest and service worker on plugin upgrade.

= 1.2 =
* New Feature: Support for theme-color.
* New Feature: Now you can edit the Application Name and Application Short name.
* New Feature: Set the start page of your PWA.
* New Feature: Set AMP version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* UI Improvement: Better organization of plugin settings. More intuitive with inline help. 
* UI Improvement: Added admin notice with a link to settings page on plugin activation.
* UI Improvement: Added checks for manifest, service worker and HTTPS and display the status neatly in the UI.
* Bug Fix: Fix a parse error that showed up only on PHP 5.3. 

= 1.1.1 =
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". PHP manual says "Prior to PHP 5.5, empty() only supports variables; anything else will result in a parse error."

= 1.1 =
* New Feature: Aggressive caching of pages using CacheStorage API.
* New Feature: Pages once cached are served even if the user is offline. 
* New Feature: Set custom offline page: Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.

= 1.0 =
* First release of the plugin.