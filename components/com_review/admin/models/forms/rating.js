jQuery(function() {
    document.formvalidator.setHandler('rating',
        function (value) {
            return (value > 0 && value <= 10 && (value % 1 == 0))
        });
});