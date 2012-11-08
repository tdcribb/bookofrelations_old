<?php if($slider_count>0) $slider_id = $slider_id.'-'.$slider_count; ?>
<div class="cycloneslider cycloneslider-template-thumbnails" id="cycloneslider-<?php echo $slider_id; ?>">
	<div class="cycloneslider-slides">
		<?php foreach($slides as $i=>$slide): ?>
			<div class="cycloneslider-slide">
				<?php if ($slider_metas[$i]['link']!='') : ?><a href="<?php echo $slider_metas[$i]['link'];?>"><?php endif; ?>
				<img src="<?php echo $slide; ?>" alt="" width="<?php echo $slider_settings['width'];?>" height="<?php echo $slider_settings['height'];?>" />
				<?php if ($slider_metas[$i]['link']!='') : ?></a><?php endif; ?>
				<?php if(!empty($slider_metas[$i]['title']) or !empty($slider_metas[$i]['description'])) : ?>
				<div class="cycloneslider-caption">
					<div class="cycloneslider-caption-title"><?php echo $slider_metas[$i]['title'];?></div>
					<div class="cycloneslider-caption-description"><?php echo $slider_metas[$i]['description'];?></div>
				</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($slider_settings['show_prev_next']) : ?>
	<a href="#" class="cycloneslider-prev">Prev</a>
	<a href="#" class="cycloneslider-next">Next</a>
	<?php endif; ?>
</div>
<?php if ($slider_settings['show_nav']) : ?>
<div id="cycloneslider-thumbnails-<?php echo $slider_id; ?>" class="cycloneslider-template-thumbnails cycloneslider-thumbnails">
	<ul class="clearfix">
		<?php foreach($slider_metas as $i=>$slider_meta): ?>
		<li>
			<img src="<?php echo cycloneslider_thumb( $slider_meta['id'], 30, 30 ) ?>" alt="" />
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	(function() {
		var start = true;
		var slider = '#cycloneslider-<?php echo $slider_id; ?>';
		jQuery(slider).width(<?php echo $slider_settings['width']; ?>);
		jQuery(slider+' .cycloneslider-slides').height(<?php echo $slider_settings['height']; ?>);
		jQuery(slider+' .cycloneslider-slides').cycle({
			fx: "<?php echo $slider_settings['fx']; ?>",
			speed: <?php echo $slider_settings['speed']; ?>,
			timeout: <?php echo $slider_settings['timeout']; ?>,
			pager:jQuery(slider+' .cycloneslider-pager'),
			prev:jQuery(slider+' .cycloneslider-prev'),
			next:jQuery(slider+' .cycloneslider-next'),
			before:function(currSlideElement,nextSlideElement,options,forwardFlag){
				var i = options.nextSlide;/*the current active slide index*/
				if(start){
					i=0;
					start = false;
				};
				jQuery('#cycloneslider-thumbnails-<?php echo $slider_id; ?> li').removeClass('current').eq(i).addClass('current');
			}
		});
		jQuery('#cycloneslider-thumbnails-<?php echo $slider_id; ?> li').click(function(){
			var i = jQuery(this).index();
			jQuery("#cycloneslider-<?php echo $slider_id; ?> .cycloneslider-slides").cycle(i);
		});
	})();
});
</script>