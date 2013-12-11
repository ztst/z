var VideoCatalogFilters = EventDispatcher.extend({

    _classFilter: null,
    _subjectFilter: null,

    _updateButton: null,

    constructor: function()
    {
        this.base();

        this._classFilter = $("#classFilter");
        this._subjectFilter = $("#subjectFilter");

        this._updateButton = $("#filterUpdateButton");
        this._updateButton.click(handler(this, '_onUpdateButtonClick'))
    },

    enable: function()
    {
        this._updateButton.removeProp("disabled");
    },

    disable: function()
    {
        this._updateButton.prop("disabled", "disabled");
    },

    getClass: function()
    {
        return this._classFilter.val();
    },

    getSubject: function()
    {
        return this._subjectFilter.val();
    },

    _onUpdateButtonClick: function()
    {
        this.dispatchEvent(VideoCatalogFilters.event.UPDATED);
    }
},{
    event:
    {
        UPDATED: "updated"
    }
});