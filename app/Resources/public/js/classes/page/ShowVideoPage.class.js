var ShowVideoPage = Base.extend({

    _showPrevCommentsLink: null,

    _addCommentForm: null,

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
