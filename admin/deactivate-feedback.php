<?php 
$reasons = array(
    		1 => '<li><label><input type="radio" name="superpwa_disable_reason" value="temporary"/>' . __('It is only temporary', 'superpwa-for-wp') . '</label></li>',
		2 => '<li><label><input type="radio" name="superpwa_disable_reason" value="stopped"/>' . __('I stopped using superPWA on my site', 'superpwa-for-wp') . '</label></li>',
		3 => '<li><label><input type="radio" name="superpwa_disable_reason" value="missing"/>' . __('I miss a feature', 'superpwa-for-wp') . '</label></li>
		<li><input class="mb-box missing" type="text" name="superpwa_disable_text[]" value="" placeholder="Please describe the feature"/></li>',
		4 => '<li><label><input type="radio" name="superpwa_disable_reason" value="technical"/>' . __('Technical Issue', 'superpwa-for-wp') . '</label></li>
		<li><textarea class="mb-box technical" name="superpwa_disable_text[]" placeholder="' . __('How Can we help? Please describe your problem', 'superpwa-for-wp') . '"></textarea></li>',
		5 => '<li><label><input type="radio" name="superpwa_disable_reason" value="another"/>' . __('I switched to another plugin', 'superpwa-for-wp') .  '</label></li>
		<li><input class="mb-box another" type="text" name="superpwa_disable_text[]" value="" placeholder="Name of the plugin"/></li>',
		6 => '<li><label><input type="radio" name="superpwa_disable_reason" value="other"/>' . __('Other reason', 'superpwa-for-wp') . '</label></li>
		<li><textarea class="mb-box other" name="superpwa_disable_text[]" placeholder="' . __('Please specify, if possible', 'superpwa-for-wp') . '"></textarea></li>',
    );
shuffle($reasons);
?>


<div id="superpwa-reloaded-feedback-overlay" style="display: none;">
    <div id="superpwa-reloaded-feedback-content">
	<form action="" method="post">
	    <h3><strong><?php _e('If you have a moment, please let us know why you are deactivating:', 'superpwa-for-wp'); ?></strong></h3>
	    <ul>
                <?php 
                foreach ($reasons as $reason){
                    echo $reason;
                }
                ?>
	    </ul>
	    <?php if ($email) : ?>
    	    <input type="hidden" name="superpwa_disable_from" value="<?php echo $email; ?>"/>
	    <?php endif; ?>
	    <input id="superpwa-reloaded-feedback-submit" class="button button-primary" type="submit" name="superpwa_disable_submit" value="<?php _e('Submit & Deactivate', 'superpwa-for-wp'); ?>"/>
		<input type="hidden" name="superpwa_deactivate_nonce" value="<?=wp_create_nonce( 'superpwa-deactivate-nonce' )?>">
	    <a class="button"><?php _e('Only Deactivate', 'superpwa-for-wp'); ?></a>
	    <a class="superpwa-for-wp-feedback-not-deactivate" href="#"><?php _e('Don\'t deactivate', 'superpwa-for-wp'); ?></a>
	</form>
    </div>
</div>