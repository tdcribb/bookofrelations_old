<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?><meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?> | Preserving Your Family History for Future Generations</title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
		<script src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/js/jquery.easing.1.3.js"></script>
		<script src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/js/jquery.columnizer.min.js"></script>
		<script src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/js/jquery.fullscreenr.js"></script>
		<script>
  			var $ = jQuery.noConflict();    
		</script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/plugins/buddypress/bp-themes/bp-default/bor.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/plugins/buddypress/bp-themes/bp-default/iphone.css" />
		<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
		<script src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/plugins/buddypress/bp-themes/bp-default/bor.js"></script>

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-36827559-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>

		<script>
			// var FullscreenrOptions = { width: 1012, height: 790, bgID: '#wood-bg' }; //black leather
			// var FullscreenrOptions = { width: 2000, height: 1333, bgID: '#wood-bg' }; //brown leather
			var FullscreenrOptions = { width: 1280, height: 1080, bgID: '#wood-bg' }; //wood grain
			jQuery.fn.fullscreenr(FullscreenrOptions); 
		</script>

		<script type="text/javascript">
  			(function() {
    			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    			po.src = 'https://apis.google.com/js/plusone.js';
    			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  			})();
		</script>

		<meta property="og:image" og:title="Book of Relations | Preserving Your Family History for Future Generations" 
			og:url="http://bookofrelations.com/" content="http://bookofrelations.com/wp-content/images/book-logo-h150.png"/>
		<link rel="image_src" href="http://bookofrelations.com/wp-content/images/book-logo-h150.png"/>

		<script>
			(function() {
			  var cx = '014338141873828747484:czygx5qv2xw';
			  var gcse = document.createElement('script'); gcse.type = 'text/javascript';
			  gcse.async = true;
			  gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			      '//www.google.com/cse/cse.js?cx=' + cx;
			  var s = document.getElementsByTagName('script')[0];
			  s.parentNode.insertBefore(gcse, s);
			})();
		</script>
		
	</head>

	<body <?php body_class(); ?> id="bp-default">

		<!-- <img id="wood-bg" src="/wp-content/images/content/wood.jpg" /> -->

		<?php do_action( 'bp_before_header' ); ?>

		<div id="header" style="background: -moz-linear-gradient(center left , #000000 0%, #111111 15%, #800000 100%) repeat scroll 0 0 transparent !important; ">

				<?php do_action( 'bp_search_login_bar' ); ?>

			<?php if (! is_user_logged_in() ) { ?>
				<div id="header-login-register">
					<a class="header-register" href="<?php echo esc_url( home_url( '/' ) ); ?>membership-options/" tabindex="10">REGISTER </a>
					<a class="header-login-left" href="/"> LOGIN</a>
					<div class="iphone-header-text-only">or </div><a class="iphone-header-text-only" href="#sidebar-login-form"> Login Below</a>
				</div>
			<?php } else { ?>
				<div id="header-login-register">
					<a href="">Welcome, <?php global $current_user; get_currentuserinfo(); echo $current_user->display_name; ?> </a>
					<a class="logout" href="<?php echo 
						esc_url( home_url( '/' ) ); ?>wp-login.php?action=logout&redirect_to=http%3A%2F%2Fbookofrelations.com%2F%3Faction%3Dlogout%26redirect_to%3Dhttp%253A%252F%252Fbookofrelations.com%252Factivity%26_wpnonce%3D8a3adccefa&_wpnonce=8a3adccefa">Log Out</a>
				</div>
			<?php } ?>

			<div id="navigation" role="navigation">
				<div id="header-logo-cont">
					<a class="logo-url" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<!-- <img id="header-book-logo" src="<//?php echo esc_url( home_url( '/' ) ); ?>wp-content/images/book-logo-h150.png" /> -->
						<img id="header-logo" src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/images/logo.png" />
					</a>
				</div>
				<img id="tag-line" src="<?php echo esc_url(home_url('/')); ?>wp-content/images/tag-line.png" />
				<div id="header-search-cont">
					<div class="search-text">Begin your search here...</div>
					<div class="search-select">
						<div class="select-google-search">Search All</div>
						<div class="select-site-search">Search BOR Only</div>
					</div>
					<div id="site-search"><?php get_search_form(); ?></div>
					<div id="google-search"><gcse:search></gcse:search></div>
				</div>
				<?php wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => 'bp_dtheme_main_nav' ) ); ?>
			</div>

			<?php do_action( 'bp_header' ); ?>

		</div><!-- #header -->

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div id="container">
			<!-- <img id="cont-bg" src="/wp-content/images/background.png" /> -->
