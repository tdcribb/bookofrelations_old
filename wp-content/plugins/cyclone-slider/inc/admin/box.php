<div class="cycloneslider-box">
	<div class="cycloneslider-box-title ui-state-default">
		<span class="cycloneslider-box-title-left">
			<?php _e('Slide', 'cycloneslider'); ?>
		</span>
		<span class="cycloneslider-box-title-right">
			<span class="cycloneslider-box-drag"><?php _e('Drag', 'cycloneslider'); ?></span> | 
			<a href="#" class="cycloneslider-box-toggle"><?php _e('Toggle', 'cycloneslider'); ?></a> |
			<a href="#" class="cycloneslider-box-delete"><?php _e('Delete', 'cycloneslider'); ?></a>
		</span>
		<div class="clear"></div>
	</div>
	<div class="cycloneslider-box-body">
		<div class="cycloneslider-body-left">
			<img class="cycloneslider-slide-thumb" src="<?php echo esc_url($image_url); ?>" alt="" />
			<input class="cycloneslider-slide-meta-id" name="cycloneslider_metas[<?php echo $i; ?>][id]" type="hidden" value="<?php echo esc_attr($slider_metas[$i]['id']); ?>" />
			<input class="button-secondary cycloneslider-upload-button" type="button" value="<?php _e('Get Image', 'cycloneslider'); ?>" />
		</div>
		<div class="cycloneslider-body-right">
			<p class="cycloneslider-sub-title"><?php _e('Extra slide elements:', 'cycloneslider'); ?></p>
			<div class="cycloneslider-accordion">
				<div><a href="#"><?php _e('Slide Link', 'cycloneslider'); ?></a></div>
				<div>
				   <label for=""><?php _e('Link:', 'cycloneslider'); ?></label>
					<input class="widefat cycloneslider-slide-meta-link" name="cycloneslider_metas[<?php echo $i; ?>][link]" type="text" value="<?php echo esc_url($slider_metas[$i]['link']); ?>" />
				</div>
				<div><a href="#"><?php _e('Title', 'cycloneslider'); ?></a></div>
				<div>
				   <textarea class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][title]"><?php echo esc_attr($slider_metas[$i]['title']); ?></textarea>
				</div>
				<div><a href="#"><?php _e('Description', 'cycloneslider'); ?></a></div>
				<div>
				   <textarea class="widefat cycloneslider-slide-meta-description" name="cycloneslider_metas[<?php echo $i; ?>][description]"><?php echo esc_html($slider_metas[$i]['description']); ?></textarea>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>