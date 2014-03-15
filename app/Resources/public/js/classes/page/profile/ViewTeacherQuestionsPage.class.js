var ViewTeacherQuestionsPage = Base.extend({

    constructor: function()
    {
        this.base();

        $(".open-video-question-link").click(function(){
            var link = $(this);
            var videoId = link.attr("id").replace("openVideoQuestionLink", "");
            var countQuestionText = link.find(".user-questions-count").html();
            var questionTab = $("#questionTab");
            questionTab.find(".user-questions-count").html(countQuestionText);
            $(".questions-tab").removeClass("hidden");
            $(".video-questions").addClass("hidden");
            $("#videoQuestions" + videoId).removeClass("hidden");
            questionTab.click();
        });
    }
});

$(function(){
    new ViewTeacherQuestionsPage();
});
