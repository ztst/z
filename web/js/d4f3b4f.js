$(document).ready(function() {
    $("#tab_grades").hover(function(){
        var gradesTab = $(this);
        gradesTab.find(".grades-list").stop(true, true).slideDown(300);
        gradesTab.find(".class-subject-link").addClass("selected");
    }, function(){
        var gradesTab = $(this);
        gradesTab.find(".grades-list").slideUp();
        gradesTab.find(".class-subject-link").removeClass("selected");
    });
    $("#tab_subjects").hover(function(){
        var subjectsTab = $(this);
        subjectsTab.find(".all-subjects-list").stop(true, true).slideDown(300);
        subjectsTab.find(".class-subject-link").addClass("selected");
    }, function(){
        var subjectsTab = $(this);
        subjectsTab.find(".all-subjects-list").slideUp();
        subjectsTab.find(".class-subject-link").removeClass("selected");
    });

    $("#grades_list li").mouseleave(function(){
        var gradesListItem = $(this);
        gradesListItem.find(".subject-lists-container").hide();
        gradesListItem.find(".grade-link").removeClass("selected");
    });
    $("#grades_list li").mouseover(function(){
        var gradesListItem = $(this);
        gradesListItem.find(".subject-lists-container").show();
        gradesListItem.find(".grade-link").addClass("selected");
    });

    $("#subjects_list_all li").mouseleave(function(){
        var subjectsListItem = $(this);
        subjectsListItem.find(".grades-in-subjects").hide();
        subjectsListItem.find(".subject-link").removeClass("selected");
    });
    $("#subjects_list_all li").mouseover(function(){
        var subjectsListItem = $(this);
        subjectsListItem.find(".grades-in-subjects").show();
        subjectsListItem.find(".subject-link").addClass("selected");
    });
});