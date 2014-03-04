var SidebarMenu = EventDispatcher.extend({

    _classesButtons: null,
    _subjectsButtons: null,

    _gradesWithSubjects: null,

    constructor: function()
    {
        this.base();

        var that = this;
        this._classesButtons = $(".class-menu li");
        this._classesButtons.click(function(){
            that._onClassChanged($(this));
        });

        this._subjectsButtons = $(".subject-menu li");
        this._subjectsButtons.click(function(){
            that._onSubjectClick($(this));
        });
    },

    setGradesWithSubjects: function(gradesWithSubjects)
    {
        this._gradesWithSubjects = JSON.parse(gradesWithSubjects);
    },

    getClass: function()
    {
        return this._classesButtons.filter(".selected").prop("id");
    },

    updateSubjects: function()
    {
        this._subjectsButtons.addClass("hidden");

        var grade = this.getClass();
        var subjects = this._gradesWithSubjects[grade];

        for (var i in subjects)
        {
            this._subjectsButtons.filter("[id=" + subjects[i] + "]").removeClass("hidden");
        }
    },

    _onSubjectClick: function(item)
    {
        var grade = this.getClass();
        var subject = item.attr("id");
        var url = Routing.generate('show_catalogue', {'class': grade, 'subjectName': subject});

        window.location.href = url;
    },

    _onClassChanged: function(item)
    {
        this._classesButtons.removeClass("selected");
        item.addClass("selected");

        this.updateSubjects();
    }
});

$(function()
{
    var sidebarMenu = new SidebarMenu();

    sidebarMenu.setGradesWithSubjects($('#gradesSubjects').val());
});
