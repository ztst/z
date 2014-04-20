var SearchUserTab = EventDispatcher.extend({

    _page: null,

    _isLoading: null,
    _isFinalPage: null,

    _searchAjaxUrl: null,

    _usersContainer: null,
    _showMoreLink: null,
    _loadingIndicator: null,

    _ageFromSelect: null,
    _ageToSelect: null,
    _regionSelect: null,
    _sexSelect: null,

    constructor: function()
    {
        this.base();

        this._searchAjaxUrl = Routing.generate('search_users_ajax');

        this._page = 0;
        this._isLoading = false;

        this._usersContainer = $("#usersContainer");
        this._loadingIndicator = $("#searchUsersLoadingIndicator");

        this._showMoreLink = $("#moreUsers");
        this._showMoreLink.click(handler(this, "_loadNextPage"));

        this._initScrollLoading();

        $('.filters-block').followTo(150);
        this._initFilters();

        $("#searchUsersButton").click(handler(this, "_onSearchUsersButtonClick"));
        this._onSearchUsersButtonClick();
    },

    _initFilters: function()
    {
        this._regionSelect = $("#regionSelect");
        this._regionSelect.selectbox();

        this._sexSelect = $("#sexSelect");
        this._sexSelect.selectbox();

        this._ageFromSelect =  $("#ageFromSelect");
        this._ageFromSelect.selectbox();
        this._ageToSelect =  $("#ageToSelect");
        this._ageToSelect.selectbox();

        this._ageFromSelect.change(handler(this, "_onAgeFromSelectChange"));
        this._ageToSelect.change(handler(this, "_onAgeToSelectChange"));
    },

    _onAgeFromSelectChange: function()
    {
        var ageFrom = this._ageFromSelect.val();
        var ageTo = this._ageToSelect.val();
        if (ageTo && ageFrom && ageFrom >= ageTo)
        {
            ageTo = +ageFrom + 1;
            this._ageToSelect.find("option").removeAttr("selected");
            this._ageToSelect.find("option[value='" + ageTo + "']").attr("selected", "selected");
            this._ageToSelect.trigger("refresh");
        }
    },

    _onAgeToSelectChange: function()
    {
        var ageFrom = this._ageFromSelect.val();
        var ageTo = this._ageToSelect.val();
        if (ageTo && ageFrom && ageFrom >= ageTo)
        {
            ageFrom = +ageTo - 1;
            this._ageFromSelect.find("option").removeAttr("selected");
            this._ageFromSelect.find("option[value='" + ageFrom + "']").attr("selected", "selected");
            this._ageFromSelect.trigger("refresh");
        }
    },

    _onSearchUsersButtonClick: function()
    {
        this._usersContainer.html("");
        $(".users-search-container").addClass("hidden");
        this._page = -1;
        this._isFinalPage = false;
        this._loadNextPage();
    },

    _initScrollLoading: function()
    {
        $(window).scroll(handler(this, "_onWindowScroll"));
    },

    _onWindowScroll: function()
    {
        var screenHeight = $(window).height();

        var scroll = $(window).scrollTop();
        var containerHeight = this._usersContainer.height();
        var totalHeight = screenHeight + scroll;

        if (containerHeight - totalHeight < 0)
        {
            this._loadNextPage();
        }
    },

    _loadNextPage: function()
    {
        if (this._isLoading || this._isFinalPage)
        {
            return;
        }
        this._isLoading = true;
        this._showMoreLink.addClass("hidden");
        this._loadingIndicator.removeClass("hidden");

        this._page++;
        var data = {
            q: $("#searchUserNameInput").val(),
            r: this._regionSelect.val(),
            af: this._ageFromSelect.val(),
            at: this._ageToSelect.val(),
            s: this._sexSelect.val(),
            rl: $("[name=userRoleRadio]").filter(':checked').val(),
            page: this._page
        };

        $.post(this._searchAjaxUrl, data, handler(this, '_onLoadUsersComplete'), 'json');
    },

    _onLoadUsersComplete: function(response)
    {
        this._loadingIndicator.addClass("hidden");
        this._usersContainer.append(response.html);
        $(".users-search-container").removeClass("hidden");
        if (!response.isFinalPage)
        {
            this._showMoreLink.removeClass("hidden");
        }
        this._isFinalPage = response.isFinalPage;
        this._isLoading = false;
        $("#foundedUsersCount").text(response.countUsers);

        this._initOpenThreadLinks();
    },

    _initOpenThreadLinks: function()
    {
        var that = this;
        $(".send-founded-user-message-button").click(function(){
            var link = $(this);
            var id = link.closest("li").attr("id").replace("userContainer", "");

            that.dispatchEvent(SearchUserTab.event.OPEN_THREAD, id);
        });
    }

},{
    event:
    {
        OPEN_THREAD: "open_thread"
    }
});