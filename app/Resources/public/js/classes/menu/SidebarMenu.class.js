var SidebarMenu = EventDispatcher.extend({

    _classesButtons: null,
    _subjectsButtons: null,

    _updateSubjectsUrl: null,

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

    setSubjectsUrl: function(url)
    {
        this._updateSubjectsUrl = url;
    },

    getClass: function()
    {
        return this._classesButtons.filter(".selected").prop("id");
    },

    updateSubjects: function()
    {
        var grade = this.getClass();
        if (!grade)
        {
            return;
        }

        var data = {
            'class': grade
        };

        $.post(this._updateSubjectsUrl, data, handler(this, '_onUploadSubjectsComplete'), 'json');
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
    },

    _onUploadSubjectsComplete: function(response)
    {
        this._subjectsButtons.addClass("hidden");
        for (i in response.subjectsNames)
        {
            this._subjectsButtons.filter("[id=" + response.subjectsNames[i] + "]").removeClass("hidden");
        }

    }

});

$(function()
{
    var sidebarMenu = new SidebarMenu();

    sidebarMenu.setSubjectsUrl(Routing.generate('get_class_subjects'));
});
