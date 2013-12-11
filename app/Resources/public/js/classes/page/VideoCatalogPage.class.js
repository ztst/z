var VideoCatalogPage = Base.extend({

    _catalogContainer: null,
    _filters: null,
    _updateCatalogUrl: null,

    constructor: function()
    {
        this.base();

        this._catalogContainer = $("#videoCatalogContainer");
        this._initFilters();
    },

    setUpdateCatalogUrl: function(url)
    {
        this._updateCatalogUrl = url;
    },

    _initFilters: function()
    {
        this._filters = new VideoCatalogFilters();
        this._filters.addListener(VideoCatalogFilters.event.UPDATED, this, this._onFiltersUpdated);
    },

    _onFiltersUpdated: function()
    {
        this._filters.disable();

        var data = {
            'subject': this._filters.getSubject(),
            'class': this._filters.getClass()
        }

        $.post( this._updateCatalogUrl, data, handler( this, '_onUploadVideosComplete' ), 'json' );
    },

    _onUploadVideosComplete: function(response)
    {
        this._filters.enable();
        this._catalogContainer.html(response.content);
    }
});

$(function(){
    var videoCatalogPage = new VideoCatalogPage();
    videoCatalogPage.setUpdateCatalogUrl($("#updateCatalogUrl").val());
});
