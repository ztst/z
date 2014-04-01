var ShowVideoPage = Base.extend({

    _showPrevCommentsLink: null,

    _addCommentForm: null,

    _moveVideoUpButton: null,
    _moveVideoDownButton: null,
    _isMoving: null,

    constructor: function()
    {
        this.base();

        this._showCurrentTab();

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

        $("#showMoreChapterVideos").click(handler(this, "_showMoreChapterVideos"));

        $("#currentVideoSynopsisLink").click(handler(this, "_onChapterVideoSynopsisTabLinkClick"));
        $("#currentVideoQuizLink").click(handler(this, "_onChapterVideoQuizTabLinkClick"));
    },

    _onChapterVideoSynopsisTabLinkClick: function()
    {
        $('.nav-tabs a[href="' +  $("#currentVideoSynopsisLink").attr("href") + '"]').tab('show');
        return false;
    },

    _onChapterVideoQuizTabLinkClick: function()
    {
        $('.nav-tabs a[href="' +  $("#currentVideoQuizLink").attr("href") + '"]').tab('show');
        return false;
    },

    _showMoreChapterVideos: function()
    {
        $(".chapter-videos-list li.hidden").removeClass("hidden");
        $(".show-more-videos").hide();
    },

    _showCurrentTab: function()
    {
        if(window.location.hash)
        {
            var hash = window.location.hash.substring(1);
            $('.nav-tabs a[href="#' + hash + '"]').tab('show');
        }
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
        return true;
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
        return true;
    },

    _onVideoMovedUp: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $(".chapter-selected-video");
            this._changeVideoTitleIndexUp(current);
            current.prev().before(current);
        }
    },

    _changeVideoTitleIndexUp: function(elem)
    {
        var currentTitle = elem.find(".video-title");
        var currentTitleText = currentTitle.text();
        var currentOrder = currentTitleText.split('.')[0];
        currentTitleText = (+currentOrder - 1) + "." + currentTitleText.split('.')[1];
        currentTitle.text(currentTitleText);

        var prevTitle = elem.prev().find(".video-title");
        var prevTitleText = prevTitle.text();
        prevTitleText = (currentOrder) + "." + prevTitleText.split('.')[1];
        prevTitle.text(prevTitleText);
    },

    _onVideoMovedDown: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $(".chapter-selected-video");
            this._changeVideoTitleIndexDown(current);
            current.next().after(current);
        }
    },

    _changeVideoTitleIndexDown: function(elem)
    {
        var currentTitle = elem.find(".video-title");
        var currentTitleText = currentTitle.text();
        var currentOrder = currentTitleText.split('.')[0];
        currentTitleText = (+currentOrder + 1) + "." + currentTitleText.split('.')[1];
        currentTitle.text(currentTitleText);

        var nextTitle = elem.next().find(".video-title");
        var nextTitleText = nextTitle.text();
        nextTitleText = (currentOrder) + "." + nextTitleText.split('.')[1];
        nextTitle.text(nextTitleText);
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
            $("#prevCommentsContainer").html(response.html);
            this._showPrevCommentsLink.closest(".show-more-link").remove();
        }
    }
});

$(function(){
    new ShowVideoPage();
});
