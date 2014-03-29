var VideosList = Base.extend({

    constructor: function()
    {
        this.base();

        var videoName = $(".video-title");
        videoName.ellipsis({
            row: 2
        });
        var videoAuthorName = $(".video-author-in-list");
        videoAuthorName.ellipsis({
            onlyFullWords: true
        })
    }
});

$(function(){
    new VideosList();
});