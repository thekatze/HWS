$(function() {

    console.log("it works?");

    $("header ul li").hover(function() {
        $(this).stop().animate({backgroundColor: '#6f869d'}, 200);
    }, function() {
        $(this).stop().animate({backgroundColor: '#171a21'}, 500);
    });
});
