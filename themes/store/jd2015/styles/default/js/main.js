$(function() {
    $(".top-search li").click(function() {
        $(".top-search li").each(function() {
            $(this).removeClass("current");
        });
        $(this).addClass("current");
        $(".top-search-box input[name='act']").val(this.id);

        if ($.trim($(".top-search-box input[name='keyword']").val()) == "") {
            $(".top-search-box input[name='keyword']").attr("class", "");
            $(".top-search-box input[name='keyword']").addClass(this.id + "_bj kw_bj keyword");
        }
    });

    $(".top-search-box input[name='keyword']").focus(function() {
        $(this).attr("class", "keyword");
    }).blur(function() {
        if ($.trim($(this).val()) == "") {
            $(this).attr("class", $(this).parent().find("input[name='act']").val() + "_bj kw_bj keyword");
        }

    });


    $('.header_cart').hover(function() {
        $(this).addClass('active');
    }, function() {
        $(this).removeClass('active');
    })

    $('.mall-nav .allcategory').hover(function() {
        $(this).find('.allcategory-list').show();
    }, function() {
        $(this).find('.allcategory-list').hide();
    });






});