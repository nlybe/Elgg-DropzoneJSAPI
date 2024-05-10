define(['jquery', 'elgg', 'elgg/i18n', 'elgg/system_messages', 'elgg/security', 'dropzonejs_api'], function ($, elgg, i18n, system_messages, security) {
    Dropzone.autoDiscover = false;
    $(function() {
        var subtype = $('#subtype').val();
        var container_guid = $('#container_guid').val();
        var action_url = security.addToken(elgg.normalize_url("action/dropzonejs_api/upload?subtype="+subtype+"&container_guid="+container_guid));
        
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
            error: function error(file, message) {
              if (file.previewElement) {
                system_messages.error(i18n.echo('dropzonejs_api:upload:error'));  
                file.previewElement.classList.add("dz-error");
          
                if (typeof message !== "string" && message.error) {
                  message = message.error;
                }
          
                var _iterator6 = options_createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-errormessage]"), true),
                    _step6;
          
                try {
                  for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
                    var node = _step6.value;
                    node.textContent = message;
                  }
                } catch (err) {
                  _iterator6.e(err);
                } finally {
                  _iterator6.f();
                }
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
