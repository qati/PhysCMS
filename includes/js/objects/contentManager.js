/**
 * contentManager object.
 */
function contentManager()
{
    /**
     * Private variable: ajax.
     */
    var ajax = new Ajax({mode: AJAX.POST, engine: "contentmanager"});
    
    /**
     * Public function: showPost.
     */
    this.showPost = function(id, incat)
    {
        ajax.set(AJAX.module, "post");
        ajax.set(AJAX.action, "showPost");
        ajax.set(AJAX.data, {id: id});
        if (window.location.toString().indexOf("cikkek")!=-1){
            ajax.set(AJAX.data, {isArticle: 1});
        }
        if (incat==1){
            ajax.set(AJAX.data, {incat: 1});
        }
        ajax.set(AJAX.callback, function(msg){
            var resp = msg.split("__SELECTOR__");
            addURL(resp[0]);
            addTitle(resp[1]);
            addKeywords(resp[2]);
            addDescription(resp[3]);
            $("div#content").html(resp[4]);
            return;
        });
        ajax.query();
        return true;
    }
    
    /**
     * Public function: getComments
     */
    this.getComments = function(p)
    {
        if (typeof p == "undefined"){
            p = 1;  
        }
        ajax.set(AJAX.module, "comment");
        ajax.set(AJAX.action, "get");
        ajax.set(AJAX.data, {page: p});
        ajax.set(AJAX.data, {id: $("#ac_id").val()});
        ajax.set(AJAX.div, "#comments");
        ajax.query();
        return;
    }
    
    /**
     * Public function: addComment.
     */
    this.addComment = function()
    {
        var obj = this;
        
        ajax.set(AJAX.mode, AJAX.GET);
        ajax.set(AJAX.module, "comment");
        ajax.set(AJAX.action, "add");
        ajax.set(AJAX.div, "#comments");
        ajax.set(AJAX.data, {msg: $("#ac_msg").val()});
        ajax.set(AJAX.data, {id: $("#ac_id").val()});
        ajax.set(AJAX.callback, function(msg){
            if (msg=="" || msg.toString()=="" || msg.toString().length<5){
                obj.getComments();
            } else {
                $("#comments").html(msg);
            }
        });
        ajax.query();
        
        $("#ac_msg").val("");
    }
   
    /**
     * Public function: ajaxURL
     */
    this.ajaxURL = function()
    {
        if (window.location.hash && (window.location.toString().indexOf("home")!=-1 || window.location.toString().indexOf("cikkek")!=-1)){
            window.location.replace(window.location.hash.replace("#/",""));
        }
        return true;
    }
}

/**
 * Variable: cm
 */
var cm = new contentManager();
