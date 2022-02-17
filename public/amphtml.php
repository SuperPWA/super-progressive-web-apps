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
		            var swsource = "<?php echo $swjs_path_amp; ?>";
		            if("serviceWorker" in navigator) {			               
		                navigator.serviceWorker.register(swsource, {scope: '<?php echo $home_url; ?>'}).then(function(reg){
		                    console.log('Congratulations!!Service Worker Registered ServiceWorker scope: ', reg.scope);
		                }).catch(function(err) {
		                    console.log('ServiceWorker registration failed: ', err);
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