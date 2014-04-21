var SearchParentPage = Base.extend({

    _page: null,
    _searchString: null,

    _isLoading: null,

    _searchParentAjaxUrl: null,

    _userContainer: null,
    _showMoreLink: null,
    _loadingIndicator: null,

    constructor: function()
    {
        this.base();

        this._page = 0;
        this._searchString = $("#searchString").val();
        this._isLoading = false;

        this._userContainer = $("#userContainer");
        this._loadingIndicator = $("#loadingIndicator");

        this._showMoreLink = $("#moreUsers");
        this._showMoreLink.click(handler(this, "_loadNextPage"));

        this._initScrollLoading();
        this._initFilterSelect();

        $('.filters-block').followTo(150);
        this._textToEllipsis();
    },

    setSearchUserAjaxUrl: function(url)
    {
        this._searchParentAjaxUrl = url;
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
        var containerHeight = this._userContainer.height();
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

        $.post(this._searchParentAjaxUrl, data, handler(this, '_onLoadUsersComplete'), 'json');
    },

    _onLoadUsersComplete: function(response)
    {
        this._loadingIndicator.addClass("hidden");
        this._userContainer.append(response.html);
        if (!response.isFinalPage)
        {
            this._isLoading = false;

            this._showMoreLink.removeClass("hidden");
        }
        this._textToEllipsis();
    }

});

$(function()
{
    //var page = new SearchParentPage();
});
