ztab = (function(){
	var config = {};

	var change = function( $tab, $title ){
		//当前这组tab的配置
		var cfg = config[ $tab.attr('_ztab') ] || {};
		//当前这组tab的所有title
		var $titles = $tab.find('.zt-title');
		//当前这组tab的所以cont
		var $conts = $tab.find('.zt-cont');
		//当前tab
		var curInd = $titles.index( $tab.find('.zt-selected')[0] ) || 0;
		//要切换到的tab
		var toInd = $titles.index( $title[0] );

		//title 选中态切换
		$titles.removeClass('zt-selected');
		$title.addClass('zt-selected');

		//cont 切换
		$conts.each(function(k,v){
			if( curInd == k ){
				if( cfg.duration ){
					$(this).stop(true, true).fadeOut( cfg.duration );
				}else{
					$(this).hide();
				}
				return;
			}

			if( toInd == k ){
				if( cfg.duration ){
					$(this).stop(true, true).fadeIn( cfg.duration );
				}else{
					$(this).show();
				}
				return;
			}

			$(this).hide();
		});
	};

	//注册hover事件
	$(document).on('mouseover', '.zt-title', function(){
		if( $(this).hasClass('zt-selected') ){
			//如果当前已是选中态, 则无视
			return;
		}

		var $tab = $(this).closest('[_ztab]');
		var tabName = $tab.attr('_ztab');
		var cfg = config[tabName] || {};

		if( 'hover' == cfg.triggerEvent ){
			change( $tab, $(this) );
		}
	});

	//注册click事件
	$(document).on('click', '.zt-title', function(){
		if( $(this).hasClass('zt-selected') ){
			//如果当前已是选中态, 则无视
			return;
		}

		var $tab = $(this).closest('[_ztab]');
		var tabName = $tab.attr('_ztab');
		var cfg = config[tabName] || {};

		if( !cfg.triggerEvent || 'click' == cfg.triggerEvent ){
			change( $tab, $(this) );
		}
	});

	return {
		config : function( opts ){
			config = $.extend( config, opts );
		}
	};
})();