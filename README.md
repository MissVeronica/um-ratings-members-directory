# UM Ratings Members Directory
Extension to Ultimate Member for adding Ratings to the Members Directory Page.

## UM Settings
UM Settings -> Misc
1. Ratings Members Directory - Meta Keys - Name of the rating meta keys comma separated.
2. Ratings Members Directory - Form Ids - Form Ids comma separated.
3. Ratings Members Directory - Sorting stars - Select No sorting, Ascending or Descending.

## UM Customization
https://docs.ultimatemember.com/article/1516-templates-map
1. Customize the Members Directory template file: <code>members-grid.php</code>
2. Add <code>{{{user.ratings}}}</code> where to display the user ratings
3. Example to show ratings after the profile name

<code>// please use for buttons priority > 100
	do_action( 'um_members_just_after_name_tmpl', $args ); ?>
	{{{user.hook_just_after_name}}}
	{{{user.ratings}}}
	<# if ( user.can_edit ) { #></code>

## Updates
1. Version 1.0.0
2. Version 1.1.0 Sorting stars
  
## Translations or Text changes
Use the "Say What?" plugin with text domain ultimate-member.

https://wordpress.org/plugins/say-what/

## Installation
Download the zip file and install as a WP Plugin, activate the plugin.
