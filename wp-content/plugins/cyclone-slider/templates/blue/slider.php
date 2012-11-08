<?php if($slider_count>0) $slider_id = $slider_id.'-'.$slider_count; ?>
<div class="cycloneslider cycloneslider-template-blue" id="cycloneslider-<?php echo $slider_id; ?>">
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
	<?php if ($slider_settings['show_nav']) : ?>
	<div class="cycloneslider-pager"></div>
	<?php endif; ?>
	<?php if ($slider_settings['show_prev_next']) : ?>
	<a href="#" class="cycloneslider-prev">Prev</a>
	<a href="#" class="cycloneslider-next">Next</a>
	<?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	(function() {
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
			pause:true
		});
	})();
});
</script>