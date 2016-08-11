$(function()
{
    $(".select_js ul li").hover(
        function()
        {
            $(this).addClass('search_nonce');
        },
        function()
        {
            $(this).removeClass();
        }
    );

    $(".select_js").click(block_fn);

    $(".select_js ul li").click(function(){
        var text = $(this).text();
        $(".select_js p").text(text);

        var act  = $(this).attr("ectype");
        $(".select_js input").val(act);
    });

    $('body').click(mouseLocation);
    
});

function block_fn()
{
    $(".select_js ul").toggle();
}

function mouseLocation(e)
{
    if (e.pageX < $('.select_js').position().left ||
        e.pageX > $('.select_js').position().left + $('.select_js').outerWidth() ||
        e.pageY < $('.select_js').position().top ||
        e.pageY > $('.select_js').position().top + $('.select_js').outerHeight())
    {
        $('.select_js ul').hide();
    }
}