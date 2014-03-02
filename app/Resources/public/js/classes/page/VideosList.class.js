var VideosList = Base.extend({

    constructor: function()
    {
        this.base();

        $(".video-title").ellipsis({
            row: 2
        });
    }
});

$(function(){
    var videosList = new VideosList();
});