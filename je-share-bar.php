<?php
/*
Plugin Name: WP Share Bar
Plugin URI: https://github.com/odegraciajr
Description: Simple WordPress social media share bar with email subscription feature.
Author: Oscar De Gracia Jr(odegraciajr@gmail.com)
Version: 1.1.1
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_filter('the_content', 'share_bar_builder');
add_action( 'wp_enqueue_scripts', 'je_share_bar_scripts' );

function share_bar_builder($content)
{
	$top = '';
	$bottom = '';

  if(!is_feed() && !is_home() && !is_page()) {
    $top .= '<aside class="je_share_bar top">';
    $top .= share_bar_social();
    $top .= '</aside>';

    $bottom .= '<aside class="je_share_bar bottom">';
    $bottom .= share_bar_social();
    $bottom .= share_bar_email();
    $bottom .= '</aside>';
  }

	return $top . $content .$bottom;
}
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}
function share_bar_social () {
	global $post;
	$permalink = get_permalink($post->ID);
	$title = encodeURIComponent(get_the_title());
	$fbshares = 'Share';
	ob_start();
	?>
		<div class="share_btn">
			<a class="social_btn comments" href="#disqus_thread">
				<span>Comments</span>
			</a>
			<a class="social_btn facebook" data-permalink="<?= $permalink?>" href="#">
				<span class="je-share-count"><?= $fbshares?></span>
			</a>
			<a class="social_btn twitter share-tweet" rel="nofollow" target="_blank" href="https://twitter.com/intent/tweet?text=<?= $title?>&url=<?= $permalink?>" data-url="<?= $permalink?>" data-text="<?= $title?>">
				<span>Tweet</span>
			</a>
			<a class="social_btn email" href="#">
				<span>Email</span>
			</a>
			<a class="social_btn print" href="#">
				<span>Print</span>
			</a>
			<div class="clear"></div>
		</div>
	<?php
	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}

function share_bar_email () {
	ob_start();
	?>
		<div class="email_subscription">
			<h3>Like this Post?</h3>
			<p>Sign up for my blog updates and never miss a post. I’ll send you a FREE eBook as a thank-you.</p>
			<form action="https://app.getresponse.com/add_subscriber.html" accept-charset="utf-8" method="post">
				<input autocomplete="off" name="name" placeholder="Name" type="text">
				<input autocomplete="off" name="email" placeholder="Email Address" type="email">
        <input type="hidden" name="campaign_token" value="p7jLp" />
				<input class="submit" value="Get it Now" type="submit">
			</form>
		</div>
	<?php
	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}

function je_share_bar_scripts() {
	if(!is_feed() && !is_home() && !is_page()) {
  //if (is_single('shortcut'))
    wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
		wp_enqueue_style( 'google-font-oswald', 'https://fonts.googleapis.com/css?family=Oswald' );
		wp_enqueue_style( 'share_bar_css', plugin_dir_url( __FILE__ ) . '/je-styles.css' );
		wp_enqueue_script( 'twitter-graph', '//platform.twitter.com/widgets.js', array(), '1.0.0', true );
		wp_enqueue_script( 'je-customscript', plugin_dir_url( __FILE__ ) . '/je-scripts.js', array('jquery'), '1.0.0', true );
	}
}

function jesharebar_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Sharebar Subtitle', 'textdomain' ), 'jesharebar_meta_display_callback', 'post' );
}

function jesharebar_meta_display_callback( $post ) {
	$subtitle = get_post_meta( $post->ID, 'je_subtitle', true );
	?>
	<textarea cols="50" rows="2" id="je_subtitle" name="je_subtitle"><?php _e($subtitle);?></textarea>
    <?php
		wp_nonce_field( 'je_save_this_meta', 'je_nonce_save_subtitle' );
}

function jesharebar_save_meta_box( $post_id ) {
	$nonce_name   = isset( $_POST['je_nonce_save_subtitle'] ) ? $_POST['je_nonce_save_subtitle'] : '';
	$nonce_action = 'je_save_this_meta';
	$meta_value = $_POST['je_subtitle'];

	// Check if nonce is set.
	 if ( ! isset( $nonce_name ) ) {
			 return;
	 }

	 // Check if nonce is valid.
	 if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			 return;
	 }

	 // Check if user has permissions to save data.
	 if ( ! current_user_can( 'edit_post', $post_id ) ) {
			 return;
	 }

	 // Check if not an autosave.
	 if ( wp_is_post_autosave( $post_id ) ) {
			 return;
	 }

	 // Check if not a revision.
	 if ( wp_is_post_revision( $post_id ) ) {
			 return;
	 }

	 update_post_meta($post_id, 'je_subtitle', $meta_value);
}
add_action( 'add_meta_boxes', 'jesharebar_register_meta_boxes' );
add_action( 'save_post', 'jesharebar_save_meta_box' );
