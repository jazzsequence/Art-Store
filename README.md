# Art Store
*A WordPress plugin that allows an artist to sell their work on their own WordPress website using PayPal. WordCamp Orange County 2015 Plugin-a-Palooza entry.*

 * Plugin URI: http://wpartstore.com
 * Author(s): [Chris Reynolds](https://github.com/jazzsequence), [Suzette Franck](https://github.com/safranck)
 * License: GPLv2

Art Store allows artists to sell their work on their own WordPress website using PayPal. It creates a gallery with all of the meta information for each artwork and displays them in a nice horizontal slider, as a thumbnail page, or in a widget.

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
