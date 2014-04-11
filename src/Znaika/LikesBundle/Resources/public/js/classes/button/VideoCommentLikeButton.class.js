var VideoCommentLikeButton = BaseLikeButton.extend({

    _saveLike: function()
    {
        var id = parseInt(this._button.attr("id").replace(/[^\d]+/, ""));
        var url = Routing.generate('like_video_comment');
        var params = {
            videoCommentId: id
        };

        $.post(url, params, handler(this, "_onLikeSaved"), "json");

        return false;
    }
});
