const SITEURL = window.location.protocol+"//"+window.location.host+"/";

const reCaptchaOptions = {
    theme    : "clean", 
    callback : Recaptcha.focus_response_field,
    lang     : "hu",
    custom_translations : {
        instructions_visual : "Írd be a két szót:",
        instructions_audio  : "Írd be a hallot kódot:",
        play_again          : "Hang újrajátszása",
        cant_hear_this      : "Töltsd le a kódot mp3 formátumban",
        visual_challenge    : "Vizuális kód",
        audio_challenge     : "Kód meghalgatása",
        refresh_btn         : "Új kód",
        help_btn            : "Segítség",
        incorrect_try_again : "Rossz ellenörző kód! Próbáld újra!"
	}
};

const reCaptchaPublicKey = "6Le2u7sSAAAAAJjZ5hjUmBBJBPggn4UPioQrOxiN";
