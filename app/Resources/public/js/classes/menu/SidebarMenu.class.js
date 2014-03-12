var SidebarMenu = EventDispatcher.extend({

    _currentGrade: null,
    _currentSubject: null,

    _classesButtons: null,
    _subjectsButtons: null,

    _gradesWithSubjects: null,

    _moreSubjectsLink: null,

    constructor: function()
    {
        this.base();

        this._initClassesButtons();
        this._initSubjectsButtons();
        this._initMoreSubjectsLink();

        this._currentGrade = this._getClass();
        this._currentSubject = this._getSubject();
    },

    setGradesWithSubjects: function(gradesWithSubjects)
    {
        this._gradesWithSubjects = $.parseJSON(gradesWithSubjects);
    },

    _initClassesButtons: function()
    {
        var that = this;
        this._classesButtons = $(".class-menu li");
        this._classesButtons.click(function(){
            that._onClassChanged($(this));
        });
    },

    _initSubjectsButtons: function()
    {
        var that = this;

        this._subjectsButtons = $(".subject-menu li");
        this._subjectsButtons.click(function(event){
            if (event.which == 2) //middle button click
            {
                return true;
            }
            that._onSubjectClick($(this));
        });
    },

    _initMoreSubjectsLink: function()
    {
        this._moreSubjectsLink = $(".subject-menu-more-link");
        this._moreSubjectsLink.click(handler(this, "_onMoreSubjectsLinkClick"));

        this._toggleMoreSubjectLinkView();
    },

    _toggleMoreSubjectLinkView: function()
    {
        var lisWidth = 0;
        this._subjectsButtons.filter(":not(.hidden)").each(function(){
            lisWidth += $(this).width();
        });

        if ($(".subject-menu ul").width() > lisWidth)
        {
            this._moreSubjectsLink.addClass("hidden");
        }
        else
        {
            this._moreSubjectsLink.removeClass("hidden");
        }
    },

    _onMoreSubjectsLinkClick: function()
    {
        $(".subject-menu-more-container").html("");

        this._subjectsButtons.filter(":not(.hidden)").filter(":not(:visible)").each(function(){
            $(".subject-menu-more-container").append($(this));
        });
        $(".subject-menu-more-container").toggleClass("hidden");
    },

    _getClass: function()
    {
        return this._classesButtons.filter(".selected").prop("id");
    },

    _getSubject: function()
    {
        return this._subjectsButtons.filter(".selected").prop("id");
    },

    _updateSubjects: function()
    {
        this._subjectsButtons.addClass("hidden");

        var grade = this._getClass();
        var subjects = this._gradesWithSubjects[grade];

        this._subjectsButtons.removeClass("selected");

        for (var i in subjects)
        {
            var subject = $("#" + subjects[i]);
            subject.removeClass("hidden").find("a").attr("href", Routing.generate('show_catalogue', {'class': grade, 'subjectName': subjects[i]}));

            if (subjects[i] == this._currentSubject && this._getClass() == this._currentGrade)
            {
                subject.addClass("selected");
            }
        }
    },

    _onSubjectClick: function(item)
    {
        var grade = this._getClass();
        var subject = item.attr("id");
        window.location.href = Routing.generate('show_catalogue', {'class': grade, 'subjectName': subject});
    },

    _onClassChanged: function(item)
    {
        this._classesButtons.removeClass("selected");
        item.addClass("selected");
        this._updateSubjects();
        this._toggleMoreSubjectLinkView();
    }
});

$(function()
{
    var sidebarMenu = new SidebarMenu();

    sidebarMenu.setGradesWithSubjects($('#gradesSubjects').val());
});
