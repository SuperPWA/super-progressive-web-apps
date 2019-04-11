var mediaUploader;

function superPWACalculateImageSelectOptions(attachment, controller) {

    var control = controller.get( 'control' );

    var flexWidth = !! parseInt( control.params.flex_width, 10 );
    var flexHeight = !! parseInt( control.params.flex_height, 10 );

    var realWidth = attachment.get( 'width' );
    var realHeight = attachment.get( 'height' );

    var xInit = parseInt(control.params.width, 10);
    var yInit = parseInt(control.params.height, 10);

    var ratio = xInit / yInit;

    controller.set( 'canSkipCrop', ! control.mustBeCropped( flexWidth, flexHeight, xInit, yInit, realWidth, realHeight ) );

    var xImg = xInit;
    var yImg = yInit;

    if ( realWidth / realHeight > ratio ) {
        yInit = realHeight;
        xInit = yInit * ratio;
    } else {
        xInit = realWidth;
        yInit = xInit / ratio;
    }

    var x1 = ( realWidth - xInit ) / 2;
    var y1 = ( realHeight - yInit ) / 2;

    var imgSelectOptions = {
        handles: true,
        keys: true,
        instance: true,
        persistent: true,
        imageWidth: realWidth,
        imageHeight: realHeight,
        minWidth: xImg > xInit ? xInit : xImg,
        minHeight: yImg > yInit ? yInit : yImg,
        x1: x1,
        y1: y1,
        x2: xInit + x1,
        y2: yInit + y1
    };

    return imgSelectOptions;
}

function superPWAsetImageFromURL(url, attachmentId, width, height, inputName) {
    $("input[name='" + inputName + "']").val( url );
}

function superPWAsetImageFromAttachment(attachment, inputName) {
    $("input[name='" + inputName +"']").val( attachment.url );
}

function selectAndCropBtnHandler(e) {
    e.preventDefault();

    var inputFieldsList = {
        'superpwa-icon-upload': {name: 'superpwa_settings[icon]', width: 192, height: 192},
        'superpwa-splash-icon-upload': {name: 'superpwa_settings[splash_icon]', width: 512, height: 512},
    }, inputName, fieldKey;

    if($(e.target).hasClass('superpwa-icon-upload')) {
        fieldKey = 'superpwa-icon-upload';
        inputName = inputFieldsList[fieldKey]['name']
    } else if ($(e.target).hasClass('superpwa-splash-icon-upload')) {
        fieldKey = 'superpwa-splash-icon-upload';
        inputName = inputFieldsList[fieldKey]['name'];
    } else {
        inputName = '';
    }

    /* We need to setup a Crop control that contains a few parameters
       and a method to indicate if the CropController can skip cropping the image.
       In this example I am just creating a control on the fly with the expected properties.
       However, the controls used by WordPress Admin are api.CroppedImageControl and api.SiteIconControl
    */

    var cropControl = {
        id: "control-id",
        params: {
            flex_width: false,  // set to true if the width of the cropped image can be different to the width defined here
            flex_height: true, // set to true if the height of the cropped image can be different to the height defined here
            width: inputFieldsList[fieldKey]['width'],  // set the desired width of the destination image here
            height: inputFieldsList[fieldKey]['height'], // set the desired height of the destination image here
        }
    };

    cropControl.mustBeCropped = function (flexW, flexH, dstW, dstH, imgW, imgH) {

        // If the width and height are both flexible
        // then the user does not need to crop the image.

        if (true === flexW && true === flexH) {
            return false;
        }

        // If the width is flexible and the cropped image height matches the current image height,
        // then the user does not need to crop the image.
        if (true === flexW && dstH === imgH) {
            return false;
        }

        // If the height is flexible and the cropped image width matches the current image width,
        // then the user does not need to crop the image.
        if (true === flexH && dstW === imgW) {
            return false;
        }

        // If the cropped image width matches the current image width,
        // and the cropped image height matches the current image height
        // then the user does not need to crop the image.
        if (dstW === imgW && dstH === imgH) {
            return false;
        }

        // If the destination width is equal to or greater than the cropped image width
        // then the user does not need to crop the image...
        if (imgW <= dstW) {
            return false;
        }

        return true;
    };

    /* NOTE: Need to set this up every time instead of reusing if already there
             as the toolbar button does not get reset when doing the following:

            mediaUploader.setState('library');
            mediaUploader.open();

    */

    mediaUploader = wp.media({
        button: {
            text: 'Select and Crop', // l10n.selectAndCrop,
            close: false
        },
        states: [
            new wp.media.controller.Library({
                title: 'Select and Crop', // l10n.chooseImage,
                library: wp.media.query({type: 'image'}),
                multiple: false,
                date: false,
                priority: 20,
                suggestedWidth: inputFieldsList[fieldKey]['width'],
                suggestedHeight: inputFieldsList[fieldKey]['height']
            }),
            new wp.media.controller.CustomizeImageCropper({
                imgSelectOptions: superPWACalculateImageSelectOptions,
                control: cropControl
            })
        ]
    });

    mediaUploader.on('cropped', function (croppedImage) {

        var url = croppedImage.url,
            attachmentId = croppedImage.attachment_id,
            w = croppedImage.width,
            h = croppedImage.height;

        superPWAsetImageFromURL(url, attachmentId, w, h, inputName);

    });

    mediaUploader.on('skippedcrop', function (selection) {

        var url = selection.get('url'),
            w = selection.get('width'),
            h = selection.get('height');

        superPWAsetImageFromURL(url, selection.id, w, h, inputName);

    });

    mediaUploader.on("select", function () {

        var attachment = mediaUploader.state().get('selection').first().toJSON();

        if (cropControl.params.width === attachment.width
            && cropControl.params.height === attachment.height
            && !cropControl.params.flex_width
            && !cropControl.params.flex_height) {
            superPWAsetImageFromAttachment(attachment, inputName);
            mediaUploader.close();
        } else {
            mediaUploader.setState('cropper');
        }

    });

    mediaUploader.open();
}

jQuery(document).ready(function($){
    $('.superpwa-colorpicker').wpColorPicker();	// Color picker

    $('.superpwa-icon-upload').click(selectAndCropBtnHandler);

    $('.superpwa-splash-icon-upload').click(selectAndCropBtnHandler);

	$('.superpwa-app-short-name').on('input', function(e) {	// Warn when app_short_name exceeds 12 characters.
		if ( $('.superpwa-app-short-name').val().length > 12 ) {
			$('.superpwa-app-short-name').css({'color': '#dc3232'});
			$('#superpwa-app-short-name-limit').css({'color': '#dc3232'});
		} else {
			$('.superpwa-app-short-name').css({'color': 'inherit'});
			$('#superpwa-app-short-name-limit').css({'color': 'inherit'});
		}
	});
});