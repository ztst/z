var ShowVideoPage = Base.extend({

    _showPrevCommentsLink: null,

    _addCommentForm: null,

    _moveVideoUpButton: null,
    _moveVideoDownButton: null,
    _isMoving: null,

    constructor: function()
    {
        this.base();

        this._showPrevCommentsLink = $("#showPrevCommentsLink");
        this._showPrevCommentsLink.click(handler(this, "_onShowPrevCommentsLinkClick"));

        if ($("#addCommentForm").length)
        {
            this._addCommentForm = new AddCommentForm("addCommentForm");
        }

        if($("#teacherAnswerBlock").length)
        {
            this._initTeacherAnswerBlock();
        }
        this._initVideoOrderButtons();
    },

    _initVideoOrderButtons: function()
    {
        this._isMoving = false;
        this._moveVideoUpButton = $(".video-up");
        this._moveVideoDownButton = $(".video-down");

        this._moveVideoUpButton.click(handler(this, "_onMoveVideoUpClick"));
        this._moveVideoDownButton.click(handler(this, "_onMoveVideoDownClick"));
    },

    _onMoveVideoUpClick: function()
    {
        this._isMoving = true;

        var url = Routing.generate("move_video");

        var data = {
            "videoName": $("#currentVideoName").val(),
            "direction": "up"
        };

        $.post(url, data, handler(this, "_onVideoMovedUp"), "json");
    },

    _onMoveVideoDownClick: function()
    {
        this._isMoving = true;

        var url = Routing.generate("move_video");

        var data = {
            "videoName": $("#currentVideoName").val(),
            "direction": "down"
        };

        $.post(url, data, handler(this, "_onVideoMovedDown"), "json");
    },

    _onVideoMovedUp: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $('.chapter-selected-video');
            current.prev(".chapter-video").before(current);
        }
    },

    _onVideoMovedDown: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $('.chapter-selected-video');
            current.next(".chapter-video").after(current);
        }
    },

    _initTeacherAnswerBlock: function()
    {
        var that = this;
        $(".question-button").click(function(){
            var commentId = $(this).attr("id").replace("questionButton", "");

            that._showQuestionForm(commentId);
        })
    },

    _showQuestionForm: function(commentId)
    {
        $(".user-question-container").show();

        $(".question-button").removeClass("active");
        $("#questionButton" + commentId).addClass("active");

        $(".question-block").addClass("hidden");
        $("#questionContent" + commentId).removeClass("hidden");

        $("#questionIdInput").val(commentId);
    },

    _onShowPrevCommentsLinkClick: function()
    {
        $.post($("#showPrevCommentsUrl").val(), null, handler(this, "_onPrevCommentsLoaded"), "json");

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
