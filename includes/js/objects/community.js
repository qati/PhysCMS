/**
 * Com object.
 */
function Com()
{
    /**
     * Private object: reg.
     */
    function reg()
    {
        /**
         * Private function check.
         * Check entered datas.
         * @return boolean
         */
        function check(obj, mode, str, str2)
        {
            /**
             * Private variable: ajax
             */
            var ajax = new Ajax({engine: "community", module: "reg", action: "check", noloading: true});
        
            /**
             * Private function: errorEffect
             */
            function errorEffect(liID, mode)
            {
                if (mode=="show"){
                    $(obj).parent().parent().css({"background": "#E80000"});
                    if ($("#error").css("display")=="none"){
                        $("#error").slideDown("fast");
                    }
                    $("#error ul li#"+liID).fadeIn("fast");
                    $("#error ul li#"+liID).css("display", "list-item");
                } else {
                    $(obj).parent().parent().css({"background": ""});
                    $("#error ul li#"+liID).fadeOut("fast");
                    $("#error ul li#"+liID).css("display", "none");
                    var nr = 0;
                    $("#error ul li").each(function(){
                        if (this.style.display=="list-item"){
                            nr++;
                        }
                    });
                    if ($("#error").css("display")=="none"){
                        if (nr>0){
                            $("#error").slideDown("fast");
                        }
                    } else {
                        if (nr<=0){
                            $("#error").slideUp("fast");   
                        }
                    }
                }
            }
        
            if (mode=="nick"){
                if (str.length<4 || str.length>20){
                    errorEffect("nv_nick", "show");
                    return;
                }
                ajax.set(AJAX.data, {nick: str});
                ajax.set(AJAX.callback, function(msg){
                    if (msg=="1"){
                        errorEffect("nv_nick");
                    } else if (msg=="0") {
                        errorEffect("nv_nick", "show");
                    } else {
                        alert(msg);
                    }
                    return;
                });
                ajax.query();
                return;
            
            } else if (mode=="name"){
                if (str.length<6 || str.length>32){
                    errorEffect("nv_name", "show");
                } else {
                    errorEffect("nv_name");
                }
                return;
        
            } else if (mode=="pass"){
                if (str.length<6 || str.length>32){
                    errorEffect("nv_pass", "show");
                } else if (str!=str2){
                    errorEffect("nv_pass", "show");
                } else {
                    errorEffect("nv_pass");
                }
                return;
        
            } else if (mode=="email"){
                if (!/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i.test(str)){
                    errorEffect("nv_email", "show");
                    return;
                }
                ajax.set(AJAX.data, {email: str});
                ajax.set(AJAX.callback, function(msg){
                    if (msg=="1"){
                        errorEffect("nv_email");
                    } else if (msg=="0") {
                        errorEffect("nv_email", "show");
                    } else {
                        alert(msg);
                    }
                    return;
                });
                ajax.query();
                return;
            } else if (mode=="bdate"){
                if ($("#bdate_year").val()=="0" || $("#bdate_month").val()=="0" || $("#bdate_day").val()=="0"){
                    errorEffect("nv_bdate", "show");
                    return;
                }
                errorEffect("nv_bdate");
                return;
            } else if (mode=="captcha") {
                if (Recaptcha.get_response().length<4){
                    errorEffect("nv_captcha", "show");
                    return;
                }
                errorEffect("nv_captcha");
                return;
            }
            return;   
        }
        
        /**
         * Public function: save
         */
        this.save = function()
        {
            var $nick      = $("input#reg_nick"),
                $name      = $("input#name"),
                $pass1     = $("input#pass1"),
                $pass2     = $("input#pass2"),
                $email     = $("input#mail"),
                $hideEmail = $("input#hidemail_yes"),
                $sex       = $("select#sex"),
                $bd_year   = $("select#bdate_year"),
                $bd_month  = $("select#bdate_month"),
                $bd_day    = $("select#bdate_day"),
                rcf        = Recaptcha.get_challenge(),
                rrf        = Recaptcha.get_response();
            check($nick, "nick", $nick.val());
            check($name, "name", $name.val());
            check($pass1, "pass", $pass1.val(), $pass2.val());
            check($email, "email", $email.val());
            check($bd_year, "bdate");
            check($("#recaptcha"), "captcha");
            var nr = 0;
            $("#error ul li").each(function(){
                if (this.style.display=="list-item"){
                    nr++;
                }
            });
            if (nr!=0){
                return;
            }
            var ajax = new Ajax({mode: AJAX.POST, engine: "community", module: "reg", action: "saveReg"});
            ajax.set(AJAX.data, {
                rcf:       rcf,
                rrf:       rrf,
                nick:      $nick.val(),
                name:      $name.val(),
                pass:      $pass1.val(),
                email:     $email.val(),
                hideEmail: ($hideEmail.is(":checked")==true) ? 1 : 0,
                sex:       $sex.val(),
                bdate:     $bd_year.val()+"-"+$bd_month.val()+"-"+$bd_day.val()
            });
            ajax.query();
            return;
        }
        
        /**
         * Public function: newCaptcha
         * Try registration, with new captcha
         */
        this.newCaptcha = function()
        {
            var ajax = new Ajax({mode: AJAX.POST, engine: "community", module: "reg", action: "saveReg"});
            ajax.set(AJAX.data, {
                rcf: Recaptcha.get_challenge(),
                rrf: Recaptcha.get_response()
            });
            ajax.query();
        }
        
        /**
         * Public function: events
         */
        this.events = function()
        {
            $("table.tbl input").focus(function(){
                $(this).parent().parent().css({"background": "#F5F5F5"});
            });
            $("table.tbl input").blur(function(){
                $(this).parent().parent().css({"background": "#F9F9F9"});
            });
    
            $("table.tbl input#hidemail_yes").click(function(){
                if ($("table.tbl input#hidemail_no").is(":checked")){
                    $("table.tbl input#hidemail_no").attr("checked", false);
                }
            });
            $("table.tbl input#hidemail_no").click(function(){
                if ($("table.tbl input#hidemail_yes").is(":checked")){
                    $("table.tbl input#hidemail_yes").attr("checked", false);
                }
            });
    
            $("table.tbl input#reg_nick").blur(function(){
                check(this, "nick", $(this).val()); 
            });
            $("table.tbl input#name").blur(function(){
                check(this, "name", $(this).val()); 
            });
            $("table.tbl input#pass2").blur(function(){
                check(this, "pass", $("#pass1").val(), $(this).val()); 
            });
            $("table.tbl input#mail").blur(function(){
                check(this, "email", $(this).val()); 
            });
            $("table.tbl input#bdate").blur(function(){
                checks(this, "bdate", $(this).val());
            });
    
            $("table.tbl input#bdate").focus(function(){
                var $$ = $("table.tbl div#makeData");
                $$.css({
                    "left": $(this).offset().left+5+"px",
                    "top": $(this).offset().top+35+"px"
                });
                $$.fadeIn("fast");
                $$.focus();
            });
            //$("#makeDate").blur(function(){alert("xd");});
        }
    } 
    
    /**
     * Private object: userWall
     */
    function userWall()
    {
        /**
         * Private variable: ajax
         */
        var ajax = new Ajax({mode: AJAX.POST, engine: "community", module: "userwall"});
        /**
         * Public function: addPost
         */
        this.addPost = function()
        {
            ajax.set(AJAX.action, "add");
            ajax.set(AJAX.data, {post: $("#userwall_post").val()});
            ajax.query();
        }
        
        /**
         * Public function: changePage
         */
        this.changePage = function(page)
        {
            ajax.set(AJAX.action, "changePage");
            ajax.set(AJAX.data, {page: page});
            ajax.query();
        }
    }
    
    /**
     * Private object: user
     */
    function user()
    {
        /**
         * Private variable: ajax
         */
        var ajax = new Ajax({mode: AJAX.POST, engine: "community", module: "user"});
        
        /**
         * Public function: addFriend
         */
        this.addFriend = function(id)
        {
            var tmp  = $("div#content").html();
            ajax.set(AJAX.action, "addFriend");
            ajax.set(AJAX.data, {id: id});
            ajax.set(AJAX.callback, function(msg){
                alert(msg);
                $("div#content").html(tmp);
                return;
            });
            ajax.query();
        }
        
        /**
         * Public function: confirmFriend
         */
        this.confirmFriend = function(id)
        {
            ajax.set(AJAX.action, "confirmFriend");
            ajax.set(AJAX.data, {id: id});
            ajax.query();
        }
        
        /**
         * Public function: newpass
         */
        this.newpass = function()
        {
            var $email = $("#mail").val();
            var $rcf   = Recaptcha.get_challenge();
            var $rrf   = Recaptcha.get_response();
            if (!/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i.test($email)){
                alert("Nem érvényes e-mail cím! ");
                return;
            }
            if ($rrf.toString().length<3){
                alert("Helytelen ellenőrzőkód!");
                return;
            }
            ajax.set(AJAX.action, "newpass");
            ajax.set(AJAX.data, {email: $email});
            ajax.set(AJAX.data, {rcf: $rcf});
            ajax.set(AJAX.data, {rrf: $rrf});
            ajax.query();
            return;
        }
    }
    
    /*
     * Private function: events.
     */
    function events()
    {
        $(".sidebar #nick").blur(function(){
            if ($(this).val()==""){
                $(this).val("Felhasználónév");
            }
        });
        $(".sidebar #nick").focus(function(){
            if ($(this).val()=="Felhasználónév"){
                $(this).val("");
            }
        });
        $(".sidebar #pass").focus(function(){
            $(this).replaceWith("<input type='password' name='pass' id='pass' maxlength='32' />");
            $(".sidebar #pass").focus();
        });
    }
    
    /**
     * Private function: chat
     */
    function chat()
    {
        /**
         * Private variable: ajax
         */
        var ajax = new Ajax({ajaxServer: "chat.php", noloading: true});
        
        /**
         * Public function: getPosts
         */
        this.getPosts = function(noCheck)
        {
            if (noCheck){
                ajax.set(AJAX.mode, AJAX.POST);
                ajax.set(AJAX.data, {getposts: 1});
                ajax.query();
                return;
            }
            ajax.set(AJAX.mode, AJAX.GET);
            ajax.set(AJAX.data, {check: 1, last: $("#chat_lastmsg").val()});
            ajax.set(AJAX.callback, function(msg){
                if (msg=="1"){
                    ajax.set(AJAX.mode, AJAX.POST);
                    ajax.set(AJAX.data, {getposts: 1});
                    ajax.query();
                }
                return;
            }); 
            ajax.query(); 
            return; 
        }
        
        /**
         * Public function: addPost
         */
        this.addPost = function(id)
        {
            ajax.set(AJAX.mode, AJAX.GET);
            ajax.set(AJAX.data, {addPost: $("#"+id).val()});
            ajax.query();
            
            this.getPosts(true);
            
            return;
        }
        
        if (window.location.toString().indexOf("community/chat")!==-1){
            setInterval(this.getPosts, 2500)
        }
        
    }
    
    
    /**
     * Public object: user
     */
    this.user = new user();
    
    /**
     * Atach reg object to user object.
     */
    this.user.reg = new reg();
    
    /**
     * Public object: userWall
     */
    this.userWall = new userWall();
    
    /**
     * Public function: events
     */
    this.events = events;
    
    /**
     * Public function: chat
     */
    this.chat = new chat();
}

/**
 * Variable: com
 */
var com = new Com();
