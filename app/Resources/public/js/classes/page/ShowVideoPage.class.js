var ShowVideoPage = Base.extend({

    _showPrevCommentsLink: null,

    _addCommentForm: null,

    constructor: function()
    {
        this.base();

        this._showPrevCommentsLink = $("#showPrevCommentsLink");
        this._showPrevCommentsLink.click(handler(this, "_onShowPrevCommentsLinkClick"));

        this._addCommentForm = new AddCommentForm("addCommentForm");
    },

    _onShowPrevCommentsLinkClick: function()
    {
        $.post($("#showPrevCommentsUrl").val(), null, handler(this, '_onPrevCommentsLoaded'), 'json');

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
