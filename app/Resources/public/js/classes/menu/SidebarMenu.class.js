var SidebarMenu = EventDispatcher.extend({

    _currentGrade: null,
    _currentSubject: null,

    _classesButtons: null,
    _subjectsButtons: null,

    _gradesWithSubjects: null,

    _moreSubjectsLink: null,
    _subjectMenuMore: null,

    constructor: function()
    {
        this.base();

        this._initGradesWithSubjects($('#gradesSubjects').val());
        this._initClassesButtons();
        this._initSubjectsButtons();

        this._currentGrade = this._getClass();
        this._currentSubject = this._getSubject();

        this._initMoreSubjectsLink();
    },

    _initGradesWithSubjects: function(gradesWithSubjects)
    {
        this._gradesWithSubjects = $.parseJSON(gradesWithSubjects);
    },

    _initClassesButtons: function()
    {
        var that = this;
        this._classesButtons = $(".class-menu li");
        this._classesButtons.click(function()
        {
            that._onClassChanged($(this));
        });
    },

    _initSubjectsButtons: function()
    {
        this._subjectsButtons = $(".subject-menu-list li");
    },

    _initMoreSubjectsLink: function()
    {
        var that = this;
        this._subjectMenuMore = $(".subject-menu-more-container");
        $(window).click(function()
        {
            that._subjectMenuMore.addClass("hidden");
            that._moreSubjectsLink.removeClass("over");
            return true;
        });

        this._moreSubjectsLink = $(".subject-menu-more-link");
        this._moreSubjectsLink.click(handler(this, "_onMoreSubjectsLinkClick"));

        this._updateSubjects();
    },

    _onMoreSubjectsLinkClick: function()
    {
        this._moreSubjectsLink.toggleClass("over");
        this._subjectMenuMore.toggleClass("hidden");
        if ($(window).width() > SidebarMenu.MENU_WIDTH)
        {
            this._subjectMenuMore.css("left", this._moreSubjectsLink.position().left + "px");
            this._subjectMenuMore.css("right", "");
        }
        else
        {
            var right = (SidebarMenu.MENU_WIDTH - (this._moreSubjectsLink.position().left + this._moreSubjectsLink.outerWidth()));
            this._subjectMenuMore.css("right", right);
            this._subjectMenuMore.css("left", "");
        }

        return false;
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
        var lisWidth = 0;
        var showMore = false;

        for (var i in subjects)
        {
            var subject = $("#" + subjects[i]);
            subject.removeClass("hidden").removeClass("first").removeClass("last").removeClass("selected")
                .find("a").attr("href", Routing.generate('show_catalogue', {'class': grade, 'subjectName': subjects[i]}));

            if (subjects[i] == this._currentSubject && this._getClass() == this._currentGrade)
            {
                subject.addClass("selected");
            }
            if (i == 0)
            {
                subject.addClass("first");
            }

            $(".subject-menu-list").append(subject);
            lisWidth += subject.outerWidth();
            if (SidebarMenu.MENU_WIDTH - SidebarMenu.MORE_BUTTON_WIDTH < lisWidth)
            {
                showMore = true;
                $(".subject-menu-more-container ul").append(subject);
            }
        }
        subject.addClass("last");

        this._showMoreButton(showMore);
    },

    _showMoreButton: function(showMore)
    {
        if (showMore)
        {
            $(".subject-menu-list").append(this._moreSubjectsLink);
            this._moreSubjectsLink.removeClass("hidden");
        }
        else
        {
            this._moreSubjectsLink.addClass("hidden");
        }

        var selected = this._subjectsButtons.filter(".selected");
        if (selected.closest(".subject-menu-more-container").length)
        {
            this._moreSubjectsLink.prev().prependTo(".subject-menu-more-container ul");
            this._moreSubjectsLink.before(selected);
            selected.removeClass("last");
        }
    },

    _onClassChanged: function(item)
    {
        this._classesButtons.removeClass("selected");
        item.addClass("selected");
        this._updateSubjects();
    }
}, {
    MORE_BUTTON_WIDTH: 95,
    MENU_WIDTH: 1000
});

$(function()
{
    new SidebarMenu();
});
