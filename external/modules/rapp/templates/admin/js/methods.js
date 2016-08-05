//---www.360cd.cn mosquito---

/**
 * url 获取、设置、删除
 */
$.url = function(url, name, value, del) {

    if (url === undefined || url == '') {
        url = location.href;
    }
    else if (url === 'host') {
        url = location.host;
    }
    if (name === undefined) {
        return url;
    }
    if (value === undefined) {
        value = '';
    }
    if (del === undefined) {
        del = '';
    }

    var reg = new RegExp("(\\\?|&)" + name + "=([^&]+)(&|$)", "i");
    var str = url.match(reg);

    if (del != '') {

        if (str[1] == '?') {
            return url.replace(str[0], "?");
        } else if (str[3] == '&') {
            return url.replace(str[0], "&");
        } else {
            return url.replace(str[0], "");
        }
    } else {
        if (value != '') {
            if (str) {
                return (url.replace(reg, function($a, $b, $c) {
                    return ($a.replace($c, value));
                }));
            } else {
                if (url.indexOf('?') == -1) {
                    return (url + '?' + name + '=' + value);
                } else {
                    return (url + '&' + name + '=' + value);
                }
            }
        } else {
            if (str) {
                return str[2];
            } else {
                return '';
            }
        }
    }
}

function is_object(obj) {
    return toString.apply(obj) === '[object Object]';
}

function is_array(obj) {
    return toString.apply(obj) === '[object Array]';
}

/**
 * 0不判断为空
 * @param obj
 * @returns {Boolean}
 */
function is_empty(obj) {
    if (obj === '' || obj === null || obj === undefined) {
        return true;
    }
    return false;
}
