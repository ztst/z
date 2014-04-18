var ViewTeacherQuestionsPage = Base.extend({

    constructor: function()
    {
        this.base();

        $(".show-answer-form-link").click(function(){
            var link = $(this);
            link.closest("li").find(".question-answer-form-container").removeClass("hidden");
            link.addClass("hidden");
            $(".comment-text-field").val("");
        });

        $(".question-answer-form-container form").ajaxForm({
            success: handler(this, "_onAddAnswerSuccess"),
            dataType: 'json'
        });

        this._initOpenQuestionsLinks();
    },

    _onAddAnswerSuccess: function(response)
    {
        if(response.success)
        {
            var questionId = response.questionId;
            var videoId = response.videoId;
            $("#questionContainer" + questionId).remove();
            var openQuestionLink = $("#openVideoQuestionLink" + videoId);

            this._decrementCountQuestions($(".profile-sidebar-menu .user-questions-count"));
            this._decrementCountQuestions(openQuestionLink.find(".user-questions-count"));
            var hasQuestions = this._decrementCountQuestions($("#videoQuestions" + videoId + " .list-count-container"));

            if (!hasQuestions)
            {
                var hasVideoWithQuestions = this._decrementCountQuestions($(".video-tab .tab-header .list-count-container"));
                if (!hasVideoWithQuestions)
                {
                    $(".not-empty-questions-container").addClass("hidden");
                    $(".empty-questions-message-container").removeClass("hidden");
                }
                openQuestionLink.closest("li").remove();
                $(".questions-tab").addClass("hidden");
                $("#videoTab").click();
                $(".user-profile-page-list li").first().addClass("first");
            }
        }
    },

    _decrementCountQuestions: function(elem)
    {
        var text = elem.text();
        var count = text.replace("+", "");
        --count;
        text = (count > 0) ? "+" + count: "";
        elem.text(text);

        return count > 0;
    },

    _initOpenQuestionsLinks: function()
    {
        $(".open-video-question-link").click(function(){
            var link = $(this);
            var videoId = link.attr("id").replace("openVideoQuestionLink", "");
            var countQuestionText = link.find(".user-questions-count").html();
            var questionTab = $("#questionTab");
            questionTab.find(".user-questions-count").html(countQuestionText);
            $(".questions-tab").removeClass("hidden");
            $(".video-questions-container").addClass("hidden");
            $("#videoQuestions" + videoId).removeClass("hidden");
            questionTab.click();
        });
    }
});

$(function(){
    new ViewTeacherQuestionsPage();
});
