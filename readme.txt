=== WP Art Store ===
Contributors: jazzs3quence, suzettefranck
Donate link: http://wpartstore.com/donate
Tags: store, paypal, art store, art, work, wpartstore, artstore
Requires at least: 3.4
Tested up to: 4.2.2
Stable tag: 0.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows artists to easily list their work for sale on their own WordPress powered website.

== Description ==

Art Store allows artists to sell their work on their own WordPress website using PayPal (or any other third-party payment processor). A horizontal-scrolling gallery with each artwork can be used via a shortcode.

**Using the Gallery**

The included gallery uses a jQuery library called [SmoothDivScroll](http://smoothdivscroll.com/) by Thomas Kahn. You can use this gallery, or not, to display your art pieces. To use the gallery, we've included a shortcode. By default, the shortcode will display all your art.

`[art-store-gallery]`

You can limit the number of items that appear by adding a `posts` parameter to the shortcode:

`[art-store-gallery posts=10]`

You can specify a height or width to attempt to make all your images uniform. You can specify a height, a width, or both, and the gallery will attempt to use your values whilst not altering the aspect ratio of the thumbnail image.

`[art-store-gallery height=500]` - gallery with height/width set to 500
`[art-store-gallery width=500]` - gallery with height/width set to 500
`[art-store-gallery height=250 width=300]` - gallery with height set to 250 and width set to 300

(Note: The gallery will use whatever crop settings you have specified on the thumbnail in the media library -- you can change the crop settings by editing the image in the media library.)

== Installation ==

This section describes how to install the plugin and get it working.

1. Go to Plugins > Add New in the Dashboard.
2. Click to browse for xxxx.zip to upload.
3. Go to Settings

1. Alternatively, you can copy the contents `xxxx.zip` to the `/wp-content/plugins/xxxx/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings

== Frequently Asked Questions ==

= Do I need a PayPal account to use this plugin? =

Not necessarily. But you will need some kind of third party payment processor that either gives you a link to your purchase page or an embeddable HTML code.

= Where can I submit features requests, bug enhancements, and issues?

[Github](https://github.com/jazzsequence/Art-Store/issues/)

== Screenshots ==

 None yet

== Changelog ==

= 0.9.1 =
* added an meta value empty field check if product information is set but no value is saved
* set mousewheelScrolling to false to disable. can still scroll by click/touch and by hovering over the edges


= 0.9.0 =
* fixed a bug that displayed the current product in the related products widget

= 0.5.0 =
* Initial release
* For full changelog/revision history, [see commits on Github](https://github.com/jazzsequence/Art-Store/commits/master)


== Upgrade Notice ==


