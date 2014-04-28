var VideoLikeButton = BaseLikeButton.extend({

    _saveLike: function()
    {
        var id = parseInt(this._button.attr("id").replace(/[^\d]+/, ""));
        var url = Routing.generate('like_video');
        var params = {
            videoId: id
        };

        $.post(url, params, handler(this, "_onLikeSaved"), "json");

        return false;
    }
});
