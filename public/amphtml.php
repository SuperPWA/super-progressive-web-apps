<?php

function amp_service_worker_template(){
	$url = superpwa_site_url();
    $home_url = superpwa_home_url();
    $swjs_path_amp     = $url.'superpwa-sw'.superpwa_multisite_filename_postfix().'.js';
	?>
      <!doctype html>
		<html>
		    <head>
		        <title>Installing service worker</title>
		        <script type="text/javascript">
		            var swsource = "<?php echo esc_url($swjs_path_amp); ?>";
		            if("serviceWorker" in navigator) {			               
		                navigator.serviceWorker.register(swsource, {scope: '<?php echo esc_url($home_url); ?>'}).then(function(reg){
		                    <?php if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) : ?>console.log('Congratulations!!Service Worker Registered ServiceWorker scope: ', reg.scope);<?php endif; ?>
		                }).catch(function(err) {
		                    <?php if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) : ?>console.log('ServiceWorker registration failed: ', err);<?php endif; ?>
		                });			                			                                                                                                                                    
		            };

		            window.addEventListener('beforeinstallprompt', (e) => {
		                {{swdefaultaddtohomebar}}
		                deferredPrompt = e;
		            })

		        </script>
		        <meta name="robots" content="noindex">
		    </head>
		    <body>
		    	Installing service worker...
		    </body>
		</html>

    <?php
}