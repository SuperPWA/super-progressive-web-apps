<?php 
$reasons = array(
    		1 => '<li><label><input type="radio" name="superpwa_disable_reason" value="temporary"/>' . esc_html__('It is only temporary', 'super-progressive-web-apps') . '</label></li>',
		2 => '<li><label><input type="radio" name="superpwa_disable_reason" value="stopped"/>' . esc_html__('I stopped using superPWA on my site', 'super-progressive-web-apps') . '</label></li>',
		3 => '<li><label><input type="radio" name="superpwa_disable_reason" value="missing"/>' . esc_html__('I miss a feature', 'super-progressive-web-apps') . '</label></li>
		<li><input class="mb-box missing" type="text" name="superpwa_disable_text[]" value="" placeholder="' . esc_html__('Please describe the feature', 'super-progressive-web-apps') . '"/></li>',
		4 => '<li><label><input type="radio" name="superpwa_disable_reason" value="technical"/>' . esc_html__('Technical Issue', 'super-progressive-web-apps') . '</label></li>
		<li><textarea class="mb-box technical" name="superpwa_disable_text[]" placeholder="' . esc_html__('How Can we help? Please describe your problem', 'super-progressive-web-apps') . '"></textarea></li>',
		5 => '<li><label><input type="radio" name="superpwa_disable_reason" value="another"/>' . esc_html__('I switched to another plugin', 'super-progressive-web-apps') .  '</label></li>
		<li><input class="mb-box another" type="text" name="superpwa_disable_text[]" value="" placeholder="' . esc_html__('Name of the plugin', 'super-progressive-web-apps') . '"/></li>',
		6 => '<li><label><input type="radio" name="superpwa_disable_reason" value="other"/>' . esc_html__('Other reason', 'super-progressive-web-apps') . '</label></li>
		<li><textarea class="mb-box other" name="superpwa_disable_text[]" placeholder="' . esc_html__('Please specify, if possible', 'super-progressive-web-apps') . '"></textarea></li>',
    );
shuffle($reasons);
?>


<div id="superpwa-reloaded-feedback-overlay" style="display: none;">
    <div id="superpwa-reloaded-feedback-content">
	<form action="" method="post">
	    <h3><strong><?php esc_html_e('If you have a moment, please let us know why you are deactivating:', 'super-progressive-web-apps'); ?></strong></h3>
	    <ul>
                <?php 
                foreach ($reasons as $reason){
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $reason;
                }
                ?>
	    </ul>
	    <?php if ($email) : ?>
    	    <input type="hidden" name="superpwa_disable_from" value="<?php esc_attr( $email ); ?>"/>
	    <?php endif; ?>
	    <input id="superpwa-reloaded-feedback-submit" class="button button-primary" type="submit" name="superpwa_disable_submit" value="<?php esc_html_e('Submit & Deactivate', 'super-progressive-web-apps'); ?>"/>
		<input type="hidden" name="superpwa_deactivate_nonce" value="<?php echo esc_attr(wp_create_nonce( 'superpwa-deactivate-nonce' ))?>">
	    <a class="button"><?php esc_html_e('Only Deactivate', 'super-progressive-web-apps'); ?></a>
	    <a class="superpwa-for-wp-feedback-not-deactivate" href="#"><?php esc_html_e('Don\'t deactivate', 'super-progressive-web-apps'); ?></a>
	</form>
    </div>
</div>