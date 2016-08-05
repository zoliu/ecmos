$(
    function()
    {
        $("input[name='upload_types']").click(
            function()
            {
                $('#divSwfuploadContainer').hide();
                $('#divComUploadContainer').hide();
                $('#divRemUploadContainer').hide();
                switch($(this).val())
                {
                    case 'com_upload' :  $('#divComUploadContainer').show(); break;
                    case 'bat_upload' :  $('#divSwfuploadContainer').show(); break;
                    case 'rem_upload' :  $('#divRemUploadContainer').show(); break;
                }
            }
        );
    }
);