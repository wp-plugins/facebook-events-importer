=== Facebook Events Importer ===
Author: Volk
Contributors: jprescher
Donate link: http://wpfbevents.com/
Tags: Facebook, events, import
Requires at least: 4.1.1
Tested up to: 4.2
Stable tag: 4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Import Facebook events into your WordPress blog or website. Use the included widgets and sidebar to customize your events template.

== Description ==
https://vimeo.com/123367789

<h3>WordPress Facebook Events Importer:</h3>
Using Facebook Events feature is a great way to get the word out. Having to double or triple post your content can be a drag. Facebook Events Importer bridges the gap between your website and your Facebook events making event management easier. 

<h3>WordPress Facebook Events Importer Features:</h3>
 
<ul>
<li>Facebook Event Custom Post Type</li>
<li>Unlimited Facebook Page Imports</li>
<li>Automatic Facebook Event Updates</li>
<li>Free Code Examples – wpfbevents.com/code-examples</li>
<li>No Coding Required with add-ons</li>
<li>Frontend Events Widget with with add-ons</li>
<li>Frontend Event Templates Mobile Friendly Layouts with add-ons</li>
</ul>


== Installation ==

1. Upload `facebook_events` folder to the `/wp-content/plugins/` directory
1. Upload `facebook_events_pro` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your settings using your Facebook App ID and App Secret
4. You are ready to import!
5. Upgrading to the pro features gives you access to the templates and widget

== Changelog ==

= 2.3 =
* WP 4.2 tested and stable (New 4.2 Emoji script formatting issue discovered.)
* Google Map for Pro users
* Style Editor for Pro users
* Venue Addition for Pro users
* More Fields available view updated `http://wpfbevents.com/code-examples/`
* Events pagination for Pro users
* Increased request limit for API calls Pro & free versions
* Expired events bug fixed for Pro users
* Tags support soon.
 
= 2.2 =
* Timezone Fixes, minor layout adjustments, Update from Facebook SDK creating <not-applicable> tag for locations from v2.2 sdk. Google map coming soon.

= 2.1 =
* CSS fixes Sidebar in templates with body class overrides.

= 2.0 =
* CSS Layout fix for Pro Single Template. Fixes Sidebar.

= 1.9 =
* Fixed missing locations due to 2.3 Facebook SDK update. Resolved Timezone issues for Dates and output in PRO version.  

= 1.8 =
* Facebook Upgraded v2.3 1.8 now supports places 

= 1.7 =
* Improved error response messages

= 1.6 =
* Improved error checking and field validation.

= 1.5 =
* Added Automatic event updates. Not able to remove imported pages from list bug fixed.

= 1.4 =
* Requires PHP 5.4 or greater before activation

= 1.3 =
* Bug fix with missing icon on some installs

= 1.2 =
* Localized ajax scripts for non-root installation.

= 1.1 =
* bug fixed with get_fbe_date($meta,$format)

= 1.0 =
* beta release

== Markup Example ==

`http://wpfbevents.com/code-examples/`
Facebook App configuration.
`http://wpfbevents.com/images/facebook_app_config.jpg`



== Upgrade Notice ==
= 2.3 =
* WP 4.2 tested
* Google Map for Pro users
* Style Editor for Pro users
* Venue Addition for Pro users
* More Fields available view updated `http://wpfbevents.com/code-examples/`
* Events pagination for Pro users
* Increased request limit for API calls Pro & free versions
* Expired events bug fixed for Pro users
* Tags support soon.

= 2.2 =
Timezone Fixes, minor layout adjustments, Update from Facebook SDK creating <not-applicable> tag for locations from v2.2 sdk. Google map coming soon.

= 2.1 =
CSS fixes Sidebar in templates with body class overrides. - pro version

= 2.0 =
CSS Layout fix for Pro Single Template. Fixes Sidebar.

= 1.9 =
Fixed missing locations due to 2.3 Facebook SDK update. Resolved Timezone issues for Dates and output in PRO version.  

= 1.8 =
Facebook Upgraded v2.3 1.8 now supports places 

= 1.7.1 =
Added specific error response type

= 1.7 =
Improved error response messages

= 1.6 =
Improved error checking and field validation.

= 1.5 =
Added Automatic event updates. Not able to remove imported pages from list bug fixed.

= 1.4 =
Requires php 5.4 or greater before activation.

= 1.3 =
Bug fix with missing icon on some installs.

= 1.2 =
Localized ajax scripts for non-root installation.

= 1.1 =
Fixed bug fixed with end_date returning null.  

== Frequently Asked Questions ==

= 404 on events page? =

If you are getting a 404 page when you visit http://YOURSITE.com/facebook-events/ url
just go to your Settings → Permalinks page and visit the events page again. This is a bug with WordPress not updating it’s database permalink table.

= Get Errors when importing? =

If you get the following error: “There may not be any future events or the page is age restricted” you should check those first. If you have upcoming events and the page is not restricted check your app settings against the Example Facebook App Setup.jpg included with the plugin or here `http://wpfbevents.com/images/facebook_app_config.jpg`. 
 
 == Screenshots == 
1. Screen 1
2. Screen 2
3. Screen 3