var SearchVideoPage = Base.extend({

    _page: null,
    _searchString: null,

    _isLoading: null,

    _searchVideoAjaxUrl: null,

    _videoContainer: null,
    _showMoreLink: null,
    _loadingIndicator: null,

    constructor: function()
    {
        this.base();

        if (!$("#isFinalPage").val())
        {
            this._page = 0;
            this._searchString = $("#searchString").val();
            this._isLoading = false;

            this._videoContainer = $("#videoContainer");
            this._loadingIndicator = $("#loadingIndicator");

            this._showMoreLink = $("#moreVideos");
            this._showMoreLink.click(handler(this, "_loadNextPage"));

            this._initScrollLoading();
        }
    },

    setSearchVideoAjaxUrl: function(url)
    {
        this._searchVideoAjaxUrl = url;
    },

    _initScrollLoading: function()
    {
        $(window).scroll(handler(this, "_onWindowScroll"));
    },

    _onWindowScroll: function()
    {
        var screenHeight = $(window).height();

        var scroll = $(window).scrollTop();
        var containerHeight = this._videoContainer.height();
        var totalHeight = screenHeight + scroll;

        if (containerHeight - totalHeight < 0)
        {
            this._loadNextPage();
        }
    },

    _loadNextPage: function()
    {
        if (this._isLoading)
        {
            return;
        }
        this._isLoading = true;
        this._showMoreLink.addClass("hidden");
        this._loadingIndicator.removeClass("hidden");

        this._page++;
        var data = {
            searchString: this._searchString,
            page: this._page
        };

        $.post(this._searchVideoAjaxUrl, data, handler(this, '_onLoadVideosComplete'), 'json');
    },

    _onLoadVideosComplete: function(response)
    {
        this._loadingIndicator.addClass("hidden");
        if (!response.isFinalPage)
        {
            this._videoContainer.append(response.html);

            this._isLoading = false;

            this._showMoreLink.removeClass("hidden");
        }
    }

});

$(function()
{
    var page = new SearchVideoPage();

    page.setSearchVideoAjaxUrl(Routing.generate('search_video_ajax'));
});
