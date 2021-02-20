/* ALL USER RELATED CODE HERE ------------------------ */

$(function(){
    
    //Set panel visible or hidden according to user preference
    //$(".userpanel").css('display', localStorage.getItem('display'));
    //$(".userzone").css('display', localStorage.getItem('display'));
    siteDesign();

    //sligth delay on site load
    setTimeout(function(){
        $(".sitezone").css('display', 'flex');
    }, 10);


    /*- PAGE UPDATES -------------------------------------------- */

    $("#cancel").hide();
    $("#save").hide();

    $("#update").click(function(){
        $('[contenteditable]').attr('contenteditable', 'true') ;
        $("#cancel").show();
        $("#save").show();
        $("#update").hide();
        $('[contenteditable]').css('outline', 'rgba(200,200,200,0.5) solid 1px');
    });

    $("#cancel").click(function(){
        $('[contenteditable]').attr('contenteditable', 'false') ;
        location.reload();
    });

    $("#save").click(function(){
                
        var page = {};
        var slug = $("#save").attr("value");

        $('[contenteditable]').each(function(){
            page[$(this).attr('id')] = $(this).text();
        });

        $.ajax({
            type: "post",
            url: ["page/ajaxupdate?slug=" + slug],
            data: {
                "Page" : page
            },
            success: function(data) {
                console.log(data); 
            }
        });

        $('[contenteditable]').attr('contenteditable', 'false') ;
        $('[contenteditable]').css('outline', 'none');
        $("#cancel").hide();
        $("#save").hide();
        $("#update").show();
    });

    // END OF PAGE UPDATE


    // USER CREATE AND UPDATE: ROLE CHECK -- */

    var otherUsers = $("#user-selectedroles label:first-of-type input, #user-selectedroles label:nth-of-type(2) input, #user-selectedroles label:nth-of-type(3) input");
    var member = $("#user-selectedroles label:last-of-type input");

    member.change(function(){
        if(member.is(':checked')){
            otherUsers.prop("checked", false);
        }
    });

    otherUsers.change(function(){
        if(otherUsers.is(':checked')){
            member.prop("checked", false);
        }
    });

    //END OF USER ROLE CHECK


    // POST CREATE AND UPDATE: CATEGORY CHECK: if "uncategorized is checked, no other categories can be checked!" -- */

    var otherCats = $("#post-selectedcat label:not(:first-of-type) input");
    var uncat = $("#post-selectedcat label:first-of-type input");

    uncat.change(function(){
        if(uncat.is(':checked')){
            otherCats.prop("checked", false);
        }
    });

    otherCats.change(function(){
        if(otherCats.is(':checked')){
            uncat.prop("checked", false);
        }
    });

    //END OF POST CAT CHECK


    //* DATEPICKER FOR SCHEDULING PAGES AND POST!! ----- */

    //Toggle the datepicker in and out of screen

    $(".toggleDatetimeContainer").click(function(){
        
        $(".datetimecontainer").toggleClass("slideout");

        $(this).children("span").toggle();

    });

    //deze nog aanpassen om multi te worden, of niet per se zodat elke datepicker een eigen stijl kan hebben?
    $("#pageschedule-schedule_date, #postschedule-schedule_date, #artistschedule-schedule_date, #articleschedule-schedule_date").datepicker(
        { 
            firstDay: 1,
            defaultDate: 1,
            dateFormat: "dd-mm-yy"
        }
    );

    //OWN DATE/TIME CHECK FUNCTION TO VALIDATE IF SCHEDULED DATE IS NOT IN THE PAST
    function checkDate(){
        var date = $(".datetimecontainer .form-group .form-control").val();
        var hour = $(".datetimecontainer .timeselect .form-group:first-of-type .form-control").val();
        var min = $(".datetimecontainer .timeselect .form-group:last-of-type .form-control").val();
        
        var year = date.substr(-4, 4);
        var month = date.substr(3, 2);
        var day = date.substr(0, 2);
        date = year + "-" + month + "-" + day + " " + hour + ":" + min + ":00";
        var inputDate = new Date(date);
        inputDate = inputDate.toISOString();
       
        var currentDate = new Date();
        currentDate = currentDate.toISOString();
        
        if ( inputDate < currentDate){
            $(".datetimecontainer .alert-error").show();
            $(".datetimecontainer .form-group .form-control").css('border', 'red 1px solid');
            $(".datetimecontainer .control-label").css('color', 'red');
        } else {
            $(".datetimecontainer .alert-error").hide();
            $(".datetimecontainer .form-group .form-control").css('border', 'green 1px solid');
            $(".datetimecontainer .control-label").css('color', 'green');
        }

    }

    $(".datetimecontainer .form-group .form-control").change(function(){checkDate();});

    // END OF DATEPICKER FUNCTION


}); // END OF GENERAL JQUERY FUNCTION ----- */



/*- USER PANEL FUNCTIONS ------------------------------------------------------- */

//USER SET PANEL VISIBILITY ONLOAD + ONCLICK
function siteDesign(){
    if($("#userpanel").is(":visible")){
        $("#toggleUserzone").text("Hide panel");
        $(".sitezone").css('margin-top', '25px');
        $(".sitezone").css('min-height', 'calc(100vh - 25px)');
        localStorage.setItem('display','flex');
    }

    if(!$("#userpanel").is(":visible")){
        $("#toggleUserzone").text("Show panel");
        $(".sitezone").css('margin-top', '0');
        $(".sitezone").css('min-height', '100vh');
        localStorage.setItem('display','none');
    }
}


//Button click on panel toggle
$("#toggleUserzone").click(function(){
    var btn = $(this).text();

    $("#userpanel").toggle();
    $(".userzone").toggle();

    siteDesign();
});


//Button click on member icon toggle
$("#toggleMemberzone").click(function(){
    
    $(".member-options").toggleClass("responsive");

});

$(window).scroll(function(){
    var x = $(".member-options");
    x.removeClass("responsive");
});



//Platform shows standard options "pages/user" when window > 830 + hide other visible options
$(window).resize(function(){
    if(window.innerWidth > 830){
        $(".sectionoptions:visible").hide();
        $(".sectionoptions.standard").show();
    }
});


//Platform button shows dropdown options + show options when enlarging window
$(".userdropdownbtn").click(function(event){
    if(window.innerWidth < 830){
        var options = $(this).next();
        var all = $(".sectionoptions:visible");
        all.hide();
        options.toggle();
    }

});

