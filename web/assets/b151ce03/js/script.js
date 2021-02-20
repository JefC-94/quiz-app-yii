/* SITE CODE HERE --------------------------------- */

$(function(){
    
    //setTimeout(function(){slidePopup('newsletter-signup', 200);}, 3000);

    // Mobile menu show and hide
    $("#toggleMenu").click(function(){
        var x = $("#topnav");
        $("#toggleMenu").toggleClass("responsive");
        x.toggleClass("responsive");
    });

    $('body').scroll(function(){
        var x = $("#topnav");
        x.removeClass("responsive");
    });

}); // END OF GENERAL JQUERY FUNCTION

/*- OPEN POP-UP FUNCTION -------------------- */

//function accepts the slug of the pop-up, the animation style, and one animation parameter
function openPopup(slug, animation, param){
    var url = 'popup/' + slug;
    var popup = $("#" + slug);
    $.get(url, function(content){
        popup.html(content);
    });
    
    if(animation == 'show'){
        popup.show();
    }
    
    if(animation == 'fade'){
        popup.fadeIn(param);
    }

    if(animation == 'slide'){
        popup.height(param);
    }

}

/*- CUSTOM VALIDATE EMAIL FUNCTION - */

function validateEmail(valueToTest){
	var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if (testEmail.test(valueToTest)){
        return true;
    } else {
        return false;
    }
}