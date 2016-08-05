  $(document).ready(function() {
    $(".allcate li").mouseover(function() {
      $(this).find('.sub-allcate').show();
      $(this).find('.jt').show();
    });
    $(".allcate li").mouseout(function() {
      $(this).find('.sub-allcate').hide();
      $(this).find('.jt').hide();
    });
  });
  
  
$(document).ready(function(){
  $(".address-list li").mouseover(function(){
	  $(this).find('.defaultadd').show();
  });
  $(".address-list li").mouseout(function(){
	  $(this).find('.defaultadd').hide();
  });
});