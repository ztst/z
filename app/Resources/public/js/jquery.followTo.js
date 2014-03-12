$.fn.followTo = function (pos) {
    var $this = this,
        $window = $(window);

    $window.scroll(function (e) {
        if ($window.scrollTop() < pos) {
            $this.css({
                position: 'relative'
            });
        } else {
            $this.css({
                position: 'fixed',
                top: 0
            });
        }
    });
};