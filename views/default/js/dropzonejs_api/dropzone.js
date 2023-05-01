define(function (require) {

    var elgg = require("elgg");
    var $ = require('jquery');
    require('dropzonejs_api');

    Dropzone.autoDiscover = false;
    $(function() {
        var subtype = $('#subtype').val();
        var container_guid = $('#container_guid').val();
        var action_url = elgg.security.addToken(elgg.normalize_url("action/dropzonejs_api/upload?subtype="+subtype+"&container_guid="+container_guid));
        
        $("#dropzone_upload").dropzone({
            maxFiles: 2000,
            url: action_url,
            success: function (files, response) {
                if (response) {
                    let filedata = response['value'][0];
                    if (filedata.guid) {
                        // trigger the hidden input change below in order to activate trigger to other plugins
                        $(".dropzone_upload_trigger").val(filedata.guid);
                        $(".dropzone_upload_trigger").trigger("change");
                    }
                    
                    if (filedata.messages.length) {
                        if (filedata.success) {
                            $(preview).find('.elgg-dropzone-messages').html(filedata.messages.join('<br />'));
                        }
                    }

                } else {
                    $(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
                    $(preview).find('.elgg-dropzone-messages').html(elgg.echo('dropzone:server_side_error'));
                }
            },
        });
    })

    $( "#dropzone_upload p" ).on('click',function () {
        $( "#dropzone_upload" ).trigger( "click" );
    });
    $( "#dropzone_upload .elgg-icon" ).on('click',function () {
        $( "#dropzone_upload" ).trigger( "click" );
    });
});
