# UM Ratings Members Directory
Extension to Ultimate Member for adding Ratings to the Members Directory Page.

## UM Settings
UM Settings -> Misc
1. Ratings Members Directory - Meta Keys
2. Ratings Members Directory - Form Ids

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
Version 1.0.0
  
## Installation
Download the zip file and install as a WP Plugin, activate the plugin.
