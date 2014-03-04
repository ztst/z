var ShareLinks = Base.extend({

    _statUrl: null,

    constructor: function()
    {
        this.base();

        this._initLinks();
    },

    setStatUrl: function(url)
    {
        this._statUrl = url;
    },

    _initLinks: function()
    {
        $(".social_share_link").click(handler(this, "_onShareButtonClick"));
    },

    _onShareButtonClick: function(event)
    {
        var link = $(event.target);
        var url = link.attr('href');
        var isOpened = window.open(url, '', 'toolbar=0,status=0,width=626,height=436');

        var network = link.attr("id").replace("video_social_link_", "");
        this._sendStatistics(network);

        return !isOpened;
    },

    _sendStatistics: function(network)
    {
        var data = {
            "network" : network
        };

        $.post(this._statUrl, data, handler(this, '_onStatisticsSaved'), 'json');
    },

    _onStatisticsSaved: function(response)
    {
    }
});

$(function()
{
    var links = new ShareLinks();

    var videoName = $("#videoName").val();
    links.setStatUrl(Routing.generate('post_video_to_social_network', {'videoName': videoName}));
});

var AddCommentForm = BaseForm.extend({

    _isQuestionButton: null,
    _isQuestionInput: null,

    constructor: function(id)
    {
        this.base(id);

        this._form.find("textarea").rules('add', {
            maxlength: AddCommentForm.COMMENT_MAX_LENGTH,
            messages: { maxlength: "Превышен размер комментария (" + AddCommentForm.COMMENT_MAX_LENGTH + " знаков)" }
        });
        this._form.find("textarea").rules('add', {
            required: true,
            messages: { required: "Комментарий не может быть пустым." }
        });

        this._isQuestionButton = $("#isQuestionButton");
        this._isQuestionButton.click(handler(this, "_onIsQuestionButtonClick"));

        this._isQuestionInput = $("#isQuestionInput");
    },

    _needToSubmit: function()
    {
        return true;
    },

    _onIsQuestionButtonClick: function()
    {
        this._isQuestionButton.toggleClass("selected");
        this._isQuestionInput.val(this._isQuestionButton.hasClass("selected") ? 1 : 0);
    }
},{
    COMMENT_MAX_LENGTH: 4096
});
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
            var current = $('.chapter-selected-video');
            current.prev().before(current);
        }
    },

    _onVideoMovedDown: function(response)
    {
        this._isMoving = false;
        if (response.success)
        {
            var current = $('.chapter-selected-video');
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
