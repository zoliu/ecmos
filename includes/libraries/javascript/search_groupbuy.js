$(function(){
    $("[ectype='order_by_views']").click(function(){
        replaceParam('order', 'views desc');
        return false;
    });
    $("[ectype='order_by_lefttime']").click(function(){
        replaceParam('order', 'end_time asc');
        return false;
    });
    $("[ectype='state']").change(function(){
        replaceParam('state', this.value);
        return false;
    });
    
    // add by psmb
    $('.condition li a').click(function(){
        key = $(this).attr('ectype');
        if(this.id=='' || this.id==undefined){
            dropParam(key);
            return false;
        }
        replaceParam(key, this.id);
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