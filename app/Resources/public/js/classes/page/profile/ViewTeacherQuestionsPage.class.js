var ViewTeacherQuestionsPage = Base.extend({

    constructor: function()
    {
        this.base();

        $(".show-answer-form-link").click(function(){
            var link = $(this);
            link.closest("li").find(".question-answer-form-container").removeClass("hidden");
            link.addClass("hidden");
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
            $("#questionContainer" + response.questionId).remove();

            this._decrementCountQuestions($(".questions-tab .user-questions-count"));
            this._decrementCountQuestions($(".profile-sidebar-menu .user-questions-count"));
            this._decrementCountQuestions($("#openVideoQuestionLink" + response.videoId + " .user-questions-count"));
        }
    },

    _decrementCountQuestions: function(elem)
    {
        var text = elem.text();
        var count = text.replace("(+", "").replace(")", "");
        --count;
        text = (count > 0) ? "(+" + count + ")" : "";
        elem.text(text);
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
