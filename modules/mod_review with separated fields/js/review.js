jQuery(document).ready(function($) {
    /* $("button.read-all").click(function() {
        console.log($(".review.read-all"));
        if($(".review-read-all").css("display") ==="none" ) {
            $(".review.read-all").slideDown(1);
        }
    }); */
    $("button.read-all").click(function() {
        console.log($(".review.read-all"));
        if($(".review.read-all").css("display") === "none") {
            $("button.read-all").text("Close all");
            $(".review.read-all").css("display", "block");
        } else {
            $(".review.read-all").css("display", "none");
            $("button.read-all").text("Read all");
        }
    });
});