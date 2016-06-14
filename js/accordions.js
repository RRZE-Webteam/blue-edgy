jQuery(document).ready(function ($) {
    $(".accordion h2").addClass("closed");
    $(".accordion div").hide();
    $(".accordion h2").click(function(){
        $(this).next().slideToggle("400");
        $(this).toggleClass("closed").toggleClass("active");
        $(".accordion div").not($(this).next()).slideUp("400");
        $(".accordion h2").not($(this)).addClass("closed").removeClass("active"); 
    });    
});


