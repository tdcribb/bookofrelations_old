<?php 
if (ffmpegCommandExists("ffmpeg")>0) {
	// FFMPEG Exists on server
        //echo "FFMPEG IS installed";
	$ffmpeg_installed = true;
} else {
	// No FFMPEG
        //echo "FFMPEG is NOT installed";
	$ffmpeg_installed = false;
}

function ffmpegCommandExists($command) {
    $command = escapeshellarg($command);
    $exists = exec($command." -h",$out);
    return sizeof($out);
}


if($_POST['wpvp_hidden'] == 'Y') {
        //Form data sent
        $wpvp_width = $_POST['wpvp_video_width'];
	$wpvp_height = $_POST['wpvp_video_height'];
        $wpvp_thumb_width = $_POST['wpvp_thumb_width'];
	$wpvp_thumb_height = $_POST['wpvp_thumb_height'];
	$wpvp_capture_image = $_POST['wpvp_capture_image'];
//	$wpvp_category_list = $_POST['post_category'];
	
	update_option('wpvp_video_width', $wpvp_width);
	update_option('wpvp_video_height', $wpvp_height);
	update_option('wpvp_thumb_width', $wpvp_thumb_width);
	update_option('wpvp_thumb_height', $wpvp_thumb_height);
	update_option('wpvp_capture_image', $wpvp_capture_image);
//	update_option('post_category', $wpvp_category_list);
?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else{
	$wpvp_width = get_option('wpvp_video_width');
	$wpvp_height = get_option('wpvp_video_height');
	$wpvp_thumb_width = get_option('wpvp_thumb_width');
	$wpvp_thumb_height = get_option('wpvp_thumb_height');
	$wpvp_capture_image = get_option('wpvp_capture_image');
//	$wpvp_category_list = get_option('post_category');
}
?>
<div class="wrap">
	<?php	echo "<h2>" . __( 'WP Video Posts - Shortcodes Reminder' ) . "</h2>";?>

	<!-- PayPal Donate -->
	<?php echo "<h3>Please donate if you enjoy this plugin (WPVP):</h3>"; ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="J535UTFPCXFQC">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	<hr>

	<?php 	if(!$ffmpeg_installed){
		echo '<h3 style="color: red;">FFMPEG is not installed on the server, therefore this plugin cannot function properly.<br />Please verify with your administrator or hosting provider to have this installed and configured.</h3><br />';
	} ?>	
	<div style="font-weight: bold; font-size: 14px;"><?php _e('Youtube and Vimeo Embed Instructions');?></div>
	<?php _e('WP Video Posts allows the embed of Youtube and Vimeo videos with the use of the following shortcodes:');?>
	<br /><br />
	<strong>Youtube:</strong> [wpvp_embed type=youtube video_code=vAFQIciWsF4 width=560 height=315]
	<br /><br />
	<strong>Vimeo:</strong> [wpvp_embed type=vimeo video_code=23117398 width=500 height=281]
	<hr>
	<div style="font-weight: bold; font-size: 14px;"><?php _e('Front End Uploader & Front End Editor');?></div>
	<?php _e('WP Video Posts allows the front end uploader and front end editor for the video posts. Insert the following shortcode into the pages you would like to use for Uploader and Editor');?>
	<br /><br />
	<strong>Uploader:</strong> [wpvp_upload_video]
	<br /><br />
	<strong>Editor:</strong> [wpvp_edit_video]
	<p><?php _e('<em>Notice:</em> you would also need to associate the editor page under WP Video Posts - Front End Editor');?></p>
	<br /><br />
</div>
