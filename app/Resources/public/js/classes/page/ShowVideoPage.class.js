var ShowVideoPage = Base.extend({

    _showPrevCommentsLink: null,

    constructor: function()
    {
        this.base();

        this._showPrevCommentsLink = $("#showPrevCommentsLink");
        this._showPrevCommentsLink.click(handler(this, "_onShowPrevCommentsLinkClick"));
    },

    _onShowPrevCommentsLinkClick: function()
    {
        $.post(this._showPrevCommentsLink.attr("href"), null, handler(this, '_onPrevCommentsLoaded'), 'json');

        return false;
    },

    _onPrevCommentsLoaded: function(response)
    {
        if (response.success)
        {
            $("#prevCommentsContainer").html(response.html)
            this._showPrevCommentsLink.closest(".alert").remove();
        }
    }
});

$(function(){
    var showVideoPage = new ShowVideoPage();
});
