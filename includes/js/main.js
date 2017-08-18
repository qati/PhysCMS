$(document).ready(function(){
    com.events();
    cm.ajaxURL();
    if (window.location.toString().indexOf("community/reg")!==-1){
        com.user.reg.events();
    }
});