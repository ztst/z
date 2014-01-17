var SidebarMenu = EventDispatcher.extend({

    _classesButtons: null,
    _subjectsButtons: null,

    _updateSubjectsUrl: null,

    constructor: function()
    {
        this.base();

        this._classesButtons = $(".class_menu li");
        this._classesButtons.click(handler(this, "_onClassChanged"));

        this._subjectsButtons = $(".subject_menu li");
        this._subjectsButtons.click(handler(this, "_onSubjectClick"));
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
        if ( !grade )
        {
            return;
        }

        var data = {
            'class': grade
        };

        $.post( this._updateSubjectsUrl, data, handler( this, '_onUploadSubjectsComplete' ), 'json' );
    },

    _onSubjectClick: function(event)
    {
        var grade = this.getClass();
        var subject = $(event.target).parent().attr("id");
        var url = Routing.generate('show_catalogue', {'class': grade, 'subjectName': subject});

        window.location.href = url;
    },

    _onClassChanged: function(event)
    {
        if ( event.target.tagName != "SPAN" ) //TODO: change this fix
        {
            return false;
        }
        this._classesButtons.removeClass("selected");
        $(event.target).parent("li").addClass("selected");

        this.updateSubjects();
    },

    _onUploadSubjectsComplete: function(response)
    {
        this._subjectsButtons.addClass("hidden");
        for ( i in response.subjectsNames )
        {
            this._subjectsButtons.filter("[id=" + response.subjectsNames[i] + "]").removeClass("hidden");
        }

    }

});

$(function(){
    var sidebarMenu = new SidebarMenu();

    sidebarMenu.setSubjectsUrl(Routing.generate('get_class_subjects'));
});
