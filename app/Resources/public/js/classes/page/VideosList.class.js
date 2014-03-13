var VideosList = Base.extend({

    constructor: function()
    {
        this.base();
        var videoName = $(".video-title");

        videoName.ellipsis({
            row: 2
        });
    }
});

$(function(){
    new VideosList();
});