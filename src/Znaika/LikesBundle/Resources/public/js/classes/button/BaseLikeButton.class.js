var BaseLikeButton = Base.extend({

    _button: null,

    constructor: function(id)
    {
        this.base();

        this._initButton(id)
    },

    _initButton: function(id)
    {
        this._button = $("#" + id);
        this._button.click(handler(this, "_onButtonClick"));
    },

    _onButtonClick: function()
    {
        if (this._canUserLike())
        {
            if (!this._isLoading)
            {
                this._saveLike();
            }
            this._isLoading = true;

        }
        else
        {
            this._openUnregisteredUserPopup();
        }

        return false;
    },

    _canUserLike: function()
    {
        return this._button.hasClass("can-like");
    },

    _saveLike: function()
    {
    },

    _onLikeSaved: function(response)
    {
        if (response.success)
        {
            var likesCountContainer = this._button.prev(".likes-count");
            var likesCount = parseInt(likesCountContainer.text());
            likesCount += response.liked ? 1 : -1;
            likesCountContainer.text(likesCount);
            var isLiked = response.liked;
            if (isLiked)
            {
                this._button.addClass("liked");
            }
            else
            {
                this._button.removeClass("liked");
            }

            this._isLoading = false;
        }
    },

    _openUnregisteredUserPopup: function()
    {
        var popup = UnregisteredUserLikePopup.getInstance();
        popup.open();
    }
});
