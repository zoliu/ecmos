$(function()
{
    $('select').change (
        function(){choose(this)}
    );
    gcategoryInit("select_gcategory");
    //hide all menu
    hide('select_gcategory');
    hide('acategory_cate_id');

    $('#gcategory').click (
        function()
        {
            on('select_gcategory');
            $('#link').attr('disabled',true);
            $('#link').val(' ');
            $('#gcategory_cate_id').val('');
        }
    );
    $('#acategory').click (
        function()
        {
            on('acategory_cate_id');
            $('#link').attr('disabled',true);
            $('#link').val(' ');
            $('#gcategory_cate_id').val('');
        }
    );

 }
);


    //hide some object by id
function hide(id)
{
    $('#'+id).hide();
}

    //show some object by id
function show(id)
{
    $('#'+id).show();
}

function choose(obj){
    var link;
    var title;
        title = $('#'+obj.id+' option:selected') .text();
        title = title.replace(/\u00a0/g,' ');
        title = $.trim(title);
    $('#title').val(title);
    if(!obj.id)
    {
        $('#gcategory_cate_id').val(obj.value);
    }
}
function on(id)
{
    hide('select_gcategory');
    hide('acategory_cate_id');
    show(id);
}