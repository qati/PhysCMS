/**
 * Navigator object.
 */
function Navigator()
{
    /**
     * Private variable: browsers.
     */
    var browsers = {
        FF:     1,
        CHROME: 2,
        IE:     3,
        OPERA:  4
    };
    /**
     * Private variables: browser & version
     */
    var browser=false, version=false;
    
    /**
     * Private function: init
     */
    function init()
    {
        if (/Firefox[\/\s](\d+\.\d+)/i.test(navigator.userAgent)){
            browser = browsers.FF;
            version = new Number(RegExp.$1);
        } else if (/Chrome[\/\s](\d+\.\d+)/i.test(navigator.userAgent)){
            browser = browsers.CHROME;
            version = new Number(RegExp.$1);
        } else if (/MSIE (\d+\.\d+);/i.test(navigator.userAgent)){
            browser = browsers.IE;
            version = new Number(RegExp.$1);
        } else if (/Opera[\/\s](\d+\.\d+)/i.test(navigator.userAgent)){
            browser = browsers.OPERA;
            version = new Number(RegExp.$1);
        }
        return;
    }
    
    /**
     * Public function: is
     */
    this.is = function(name)
    {
        if (browser==browsers[name.toUpperCase()]){
            return true;
        }
        return false;
    }
        
    /**
     * Public function: version
     */
    this.version = function()
    {
        return version;
    }
    
    /**
     * Public function: getNav
     */
    this.getNav = function()
    {
        return navigator.userAgent;
    }
    
    /**
     * Run init function.
     */
    init();
}

/**
 * Variable nav.
 */
var nav = new Navigator();
