$(function(){
	$('[ectype="region"]').click(function(){
		if(this.id=='' || this.id==undefined){
			dropParam('region_id');
			return false;
		}
		replaceParam('region_id', this.id);
        return false;
	});
	$('.select-param .tan li').click(function(){
		var key = $(this).parent().attr('ectype');
		var value = $(this).attr('v');
		if(value=='' || value==undefined){
			dropParam(key);
			return false;
		}
		replaceParam(key,value);
        return false;
	});
	
});


/* 替换参数 */
function replaceParam(key, value)
{
    var params = location.search.substr(1).split('&');
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params[i] = key + '=' + value;
            found = true;
        }
    }
    if (!found)
    {
        params.push(key + '=' + encodeURIComponent(value));
    }
    
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}
/* 删除参数 */
function dropParam(key)
{
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if (pKey == 'page')
        {
            params[i] = 'page=1';
        }
        if (pKey == key)
        {
            params.splice(i, 1);
        }
    }
    location.assign(SITE_URL + '/index.php?' + params.join('&'));
}