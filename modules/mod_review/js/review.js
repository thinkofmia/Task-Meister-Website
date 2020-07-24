jQuery(document).ready(function ($) {
    jQuery.fn.extend({
        mask : function() {
            $(this).each(function(index, element) {
                element.disabled = true;
                element.style.backgroundColor = "transparent";
                element.style.color = "transparent";
                element.style.cursor = "default";
                element.style.border = "none";
            });
            return $(this);
        }
    });

    jQuery.fn.extend({
        reveal : function() {
            $(this).each(function(index, element) {
                element.disabled = false;
                element.style.backgroundColor = "#8a8a8a";
                element.style.color = "black";
                element.style.cursor = "pointer";
                element.style.border = "solid white 1px";
            });
            return $(this);
        }
    });
    
    console.log($.fn.jquery);

    // check which review expansion/compression buttons should be shown
    // no reviews to expand
    if($(".reviews").children(".review.read-more").length == 0){
        // hide all the buttons
        /* console.log("Length:", $(".reviews").children(".review.read-more").length);
        console.log("Hiding all buttons"); */
        $("button.read-all").mask();
        $("button.read-more").mask();
        $("button.read-less").mask();
        $("button.close-all").mask();
    }
    else { // reviews to show
        // show buttons for expansion, hide for closing
        /* console.log("Length:", $(".reviews").children(".review.read-more").length);
        console.log("Hiding only right buttons"); */
        $("button.read-all").reveal();
        $("button.read-more").reveal();
        $("button.read-less").mask();
        $("button.close-all").mask();
    }

    $("button.read-all").click(function () {
        // show all reviews
        $(".reviews").children(".review.read-more").show();

        // hide read-all and read-more buttons since there are no more reviews to show
        $("button.read-more").mask();
        $("button.read-all").mask();

        // show close-all and read-less buttons since there are now reviews to hide
        $("button.close-all").reveal();
        $("button.read-less").reveal();
    });

    $("button.close-all").click(function () {
        // hide all reviews after 3rd review
        $(".reviews").children(".review.read-more").hide();

        // hide close-all and read-less buttons since there are no more reviews to hide
        $("button.close-all").mask();
        $("button.read-less").mask();

        // show read-all and read-more buttons since there are now reviews to show
        $("button.read-more").reveal();
        $("button.read-all").reveal();
    });

    $("button.read-more").click(function () {
        // show next 3 reviews
        $(".reviews").children(".review.read-more").filter(function (index, element) {
            return element.style.display === "none";
        }).slice(0, 3).show();

        // hide read-more and read-all buttons if there are no more reviews to display
        hidden = $(".reviews").children(".review.read-more").filter(function (index, element) {
            return element.style.display === "none";
        });
        if (hidden.length === 0) {
            $("button.read-more").mask();
            $("button.read-all").mask();
        }

        // show close-all and read-less buttons since there are now reviews to hide
        $("button.read-less").reveal();
        $("button.close-all").reveal();
    });

    $("button.read-less").click(function () {
        // hide last 3 reviews
        $(".reviews").children(".review.read-more").filter(function (index, element) {
            return element.style.display !== "none";
        }).slice(-3).hide();

        // hide close-all and read-less buttons if there are no more reviews to hide
        hidden = $(".reviews").children(".review.read-more").filter(function (index, element) {
            return element.style.display === "none";
        });
        if (hidden.length === $(".reviews").children(".review.read-more").length) {
            $("button.read-less").mask();
            $("button.close-all").mask();
        }

        // show read-all and read-more buttons since there are now reviews to show
        $("button.read-more").reveal();
        $("button.read-all").reveal();
    });
});

function validateReview() {
    review_rating = document.querySelector("#rating");
    if(review_rating.value < 1 || review_rating.value > 10) {
        // check if warning msg already exists in DOM
        if(!(review_warning_msg = document.querySelector("#review-warning-msg"))) {
            review_warning_msg = document.createElement("span");
            review_warning_msg.id = "review-warning-msg";
            review_warning_msg.style = "padding-left: 10px; color: #ff0000";
            review_warning_msg.textContent = "Please select a rating using the stars above the text box";
            document.querySelector(".review-submit").after(review_warning_msg);
        }
        return false;
    }
    return true;
}

function removeWarningMsg() {
    review_warning_msg = document.querySelector("#review-warning-msg");
    if(review_warning_msg) {
        review_warning_msg.remove();
    }
}