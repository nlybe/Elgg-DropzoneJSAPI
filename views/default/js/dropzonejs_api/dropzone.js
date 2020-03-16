define(function (require) {

    var elgg = require("elgg");
    var $ = require('jquery');
    require('dropzonejs_api');

    // Initialise the variable required for file object
    // var file_obj;
    
    Dropzone.autoDiscover = false;

    // initialize status
    $(document).ready(function(){
 
        // Dropzone: The recommended way from within the init configuration:
        $(function() {
            var subtype = $('#subtype').val();
            var container_guid = $('#container_guid').val();
            var action_url = elgg.security.addToken(elgg.normalize_url("action/dropzonejs_api/upload?subtype="+subtype+"&container_guid="+container_guid));
            // console.log(action_url);
            $("#dropzone_upload").dropzone({
                maxFiles: 2000,
                url: action_url,
                success: function (files, response) {
                    if (!$.isArray(files)) {
                        files = [files];
                    }
                    $.each(files, function (index, file) {
                        var preview = file.previewElement;
                        if (response && response.output) {
                            var filedata = response.output[index];
                            // if (filedata.success) {
                            //     $(preview).addClass('elgg-dropzone-success').removeClass('elgg-dropzone-error');
                            // } else {
                            //     $(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
                            // }
                            if (filedata.html) {
                                $(preview).append($(filedata.html));
                            }
                            if (filedata.guid) {
                                $(preview).attr('data-guid', filedata.guid);

                                // trigger the hidden input change below in order to activate trigger to other plugins
                                $(".dropzone_upload_trigger").val(filedata.guid);
                                $(".dropzone_upload_trigger").trigger("change");
                            }
                            if (filedata.messages.length) {
                                if (response.output && response.output.success) {
                                    $(preview).find('.elgg-dropzone-messages').html(response.output.messages.join('<br />'));
                                }
                            }
        
                        } else {
                            $(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
                            $(preview).find('.elgg-dropzone-messages').html(elgg.echo('dropzone:server_side_error'));
                        }
                        // elgg.trigger_hook('upload:success', 'dropzone', {file: file, data: response});
                    });                     
                },
            });
        })

        $( "#dropzone_upload p" ).click(function() {
            $( "#dropzone_upload" ).click();
        });
        $( "#dropzone_upload .elgg-icon" ).click(function() {
            $( "#dropzone_upload" ).click();
        });
    });   
});
