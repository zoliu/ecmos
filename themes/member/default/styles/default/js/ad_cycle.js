var tId;
startScroll();
$(function(){   
    $('.ad_cycle li').each(function(){
        $(this).click(function(){
            slideHere($(this));
        });
    });
});
function startScroll(){
    tId = setInterval(function(){
        var nextImg = $('.nonce').next('.initial');
        if(nextImg.length==0){
            nextImg = $($('.ad_cycle li')[0]);
        }
        slideHere($(nextImg));
    }, 3000);
}
function stopScroll(){
    clearInterval(tId);
}
function slideHere(imgObj){
    $('.ad_cycle li').removeClass('nonce');
    $('.ad_cycle li').addClass('initial');
    
    imgObj.removeClass('initial');
    imgObj.addClass('nonce');
    $('.ad_cycle img')
        .attr('src', imgObj.attr('target_src'))
        .css('display', 'none')
        .fadeIn('normal')
        .parent().attr('href', imgObj.attr('target_link'))
        .attr('target', '_blank');
}