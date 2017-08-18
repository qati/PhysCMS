/**
 * AJAX const.
 */
const AJAX = {
    POST:      1,
    GET:       2,
    engine:    1,
    module:    2,
    action:    3,
    data:      4,
    callback:  5,
    mode:      6,
    noloading: 7,
    div:       8
};

/**
 * ajax object
 */
function Ajax(a)
{
    /**
     * Private variables.
     */
    var engine ="", module = "", action="", datas="", fn = null;
    var key = document.getElementById("auth").content;
    var options = {
        mode:       AJAX.GET,
        cache:      false,
        loading:    "<img src='"+SITEURL+"content/theme/images/ajax-loader.gif' class='loadinglarge' />",
        div:        "div#content",
        noloading:  false,
        ajaxServer: false
    };
    
    if (a && typeof a === "object"){
        /**
         * Update: engine, module, action
         */
        if (a.engine && typeof a.engine === "string"){
            engine = a.engine;
            delete a.engine;
        }
        if (a.module && typeof a.module === "string"){
            module = a.module;
            delete a.module;
        }
        if (a.action && typeof a.action === "string"){
            action = a.action;
            delete a.action;
        }
        
        /**
         * Update options.
         */
        $.extend(options, a);
    }
    
    /**
     * Public function set
     */
    this.set = function(a, b, c, d)
    {
        if (!a || typeof a !== "number"){
            return false;
        }
        if ((a === AJAX.engine || a === AJAX.module || a === AJAX.action) && (!b || typeof b !== "string")){
            return false;
        }
        if (a === AJAX.callback && (!b || typeof b !== "function")){
            return false;
        }
        if (a === AJAX.data && (!b || typeof b !== "object")){
           return false; 
        }
        if (a === AJAX.mode && !(b === AJAX.GET || b === AJAX.POST)){
            return false;
        }
        if (a === AJAX.noloading && !(b === true || b === false)){
            return false;
        }
        if (a === AJAX.div && (!b || typeof b !== "string")){
            return false;
        }
        
        if (a === AJAX.engine){
            engine = b;
        } else if (a === AJAX.module){
            module = b;
        } else if (a === AJAX.action){
            action = b;
        } else if (a === AJAX.callback){
            fn = b;
        } else if (a === AJAX.mode){
            options.mode = b;
        } else if (a === AJAX.noloading){
            options.noloading = b;
        } else if (a === AJAX.div){
            options.div = b;
        } else if (a === AJAX.data){
            for(var key in b){
                if (typeof b[key] === "string" || typeof b[key] === "number"){
                    if (datas === ""){
                        datas = key + "=" + b[key];
                    } else{
                        datas += "&" + key + "=" + b[key];
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        
        return true;
    }
    
    /**
     * Public function: setEMA
     */
    this.setEMA = function(a, b, c)
    {
        if (!a || !b || !c || typeof a !== "string" || typeof b !== "string" || typeof c !== "string"){
            return false;
        }
        
        if (!this.set(AJAX.engine, a)){
            return false;
        }
        if (!this.set(AJAX.module, b)){
            return false;
        }
        if (!this.set(AJAX.action, c)){
            return false;
        }
        
        return true;
    }
     
    
    /**
     * Public function: query
     */
    this.query = function()
    {
        if (key.length!=32 || engine.length<2 || module.length<2 || action.length<2){
            alert("Hiba! Nem küldhető el a lekérdezés");
            return;
        }
        $.ajax({
            type: (options.mode===AJAX.POST) ? "POST" : "GET",
            url: SITEURL+"communication.php",
            cache: options.mode,
            data: "auth="+key+"&engine="+engine+"&module="+module+"&action="+action+"&"+datas,
            success: function(msg){
                if (fn && typeof fn === "function"){
                    fn(msg);
                } else {
                    $(options.div).html(msg);
                }
                return;
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Hiba történt a lekérdezés során!");
                return;
            },
            beforeSend: function(jqXHR, settings){
                if (!options.noloading){
                    $(options.div).html(options.loading);
                }
            }
        });
        return;
    }
}
