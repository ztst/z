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

var PageUpDownScroller = Base.extend({

    _upButton: null,
    _downButton: null,
    _prevPos: null,

    constructor: function()
    {
        this.base();

        this._upButton =  $("#upButton");
        this._downButton =  $("#downButton");

        if ($(window).scrollTop() >= PageUpDownScroller.SHOW_UP_BUTTON_HEIGHT)
        {
            this._upButton.fadeIn();
        }

        $(window).scroll(handler(this, "_onWindowScroll"));

        this._upButton.click(handler(this, "_onUpButtonClick"));
        this._downButton.click(handler(this, "_onDownButtonClick"));
    },

    _onWindowScroll: function()
    {
        var windowScroll = $(window).scrollTop();
        if (windowScroll >= PageUpDownScroller.SHOW_UP_BUTTON_HEIGHT)
        {
            this._prevPos = null;
            this._upButton.fadeIn("slow");
        }
        else
        {
            this._upButton.fadeOut("slow")
        }

        if (windowScroll >= PageUpDownScroller.HIDE_DOWN_BUTTON_HEIGHT)
        {
            this._downButton.fadeOut("slow");
        }
        else if (this._prevPos)
        {
            this._downButton.fadeIn("slow");
        }
    },

    _onUpButtonClick: function()
    {
        this._prevPos = $(window).scrollTop();
        $("html,body").animate({scrollTop: 0}, 0);

        this._downButton.fadeIn("slow");
    },

    _onDownButtonClick: function()
    {
        this._downButton.fadeOut("slow");
        $("html,body").animate({scrollTop: this._prevPos}, 0)
    }
},{
    SHOW_UP_BUTTON_HEIGHT: 350,
    HIDE_DOWN_BUTTON_HEIGHT: 250
});

$(function()
{
    new PageUpDownScroller();
});
