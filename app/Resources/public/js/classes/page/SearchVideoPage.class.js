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
        this._initFilterSelect();

        $('.filters-block').followTo(150);
        this._textToEllipsis();
    },

    setSearchVideoAjaxUrl: function(url)
    {
        this._searchVideoAjaxUrl = url;
    },

    _initFilterSelect: function()
    {
        var gradeSelect = $("#gradeSelect");
        gradeSelect.selectbox();
        gradeSelect.change(function(){
            $("#searchGradeInput").val($(this).val());
            $("#searchForm").submit();
        });

        var subjectSelect = $("#subjectSelect");
        subjectSelect.selectbox();
        subjectSelect.change(function(){
            $("#searchSubjectInput").val($(this).val());
            $("#searchForm").submit();
        });
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
            q: this._searchString,
            s: $("#searchSubjectInput").val(),
            g: $("#searchGradeInput").val(),
            page: this._page
        };

        $.post(this._searchVideoAjaxUrl, data, handler(this, '_onLoadVideosComplete'), 'json');
    },

    _onLoadVideosComplete: function(response)
    {
        this._loadingIndicator.addClass("hidden");
        this._videoContainer.append(response.html);
        if (!response.isFinalPage)
        {
            this._isLoading = false;

            this._showMoreLink.removeClass("hidden");
        }
        this._textToEllipsis();
    },

    _textToEllipsis: function()
    {
        this.base();

        $(".video-title").ellipsis({
            row: 2
        });

        $(".video-author-in-list").ellipsis({
            onlyFullWords: true
        })
    }

});

$(function()
{
    var page = new SearchVideoPage();

    page.setSearchVideoAjaxUrl(Routing.generate('search_video_ajax'));
});
