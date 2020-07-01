jQuery(document).ready(function($) {
    $("button.read-all").click(function() {
        console.log($(".review.read-more").css("display"));
        if($(".review.read-more").css("display") === "none") {
            $("button.read-all").text("Close all");
            $(".review.read-more").css("display", "block");
        } else {
            $(".review.read-more").css("display", "none");
            $("button.read-all").text("Read all");
        }
    });
});