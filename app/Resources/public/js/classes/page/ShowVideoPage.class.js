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
        this._initNavMenu();
    },

    _initNavMenu: function()
    {
        $("#showVideoContainerButton").click(handler(this, "_showVideoContainer"));
        $("#showSynopsisContainerButton").click(handler(this, "_showSynopsisContainer"));
        $("#showQuizContainerButton").click(handler(this, "_showQuizContainer"));
    },

    _showVideoContainer: function()
    {
        $(".view-video-tab-menu li").removeClass("active");
        $(".view-video-tab-menu .video").addClass("active");
        $(".video-page-left-column-content").addClass("hidden");
        $(".video-content").removeClass("hidden");
    },

    _showSynopsisContainer: function()
    {
        $(".view-video-tab-menu li").removeClass("active");
        $(".view-video-tab-menu .abstract").addClass("active");
        $(".video-page-left-column-content").addClass("hidden");
        $(".synopsis-content").removeClass("hidden");
    },

    _showQuizContainer: function()
    {
        $(".view-video-tab-menu li").removeClass("active");
        $(".view-video-tab-menu .test").addClass("active");
        $(".video-page-left-column-content").addClass("hidden");
        $(".quiz-content").removeClass("hidden");
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
        if (this._isMoving)
        {
            return false;
        }
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
        if (this._isMoving)
        {
            return false;
        }
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
            var current = $(".chapter-selected-video");
            current.prev().before(current);
        }
    },

    _onVideoMovedDown: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $(".chapter-selected-video");
            current.next().after(current);
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
