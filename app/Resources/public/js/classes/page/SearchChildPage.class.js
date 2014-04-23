var SearchChildPage = Base.extend({

    _searchTab: null,

    constructor: function()
    {
        this.base();

        this._searchTab = new SearchUserTab(Routing.generate('search_child_ajax'));
        this._searchTab.addListener(SearchUserTab.event.ADD_CHILD, this, this._onAddChild);
    },

    _onAddChild: function(id)
    {
        var url = Routing.generate("add_child", {'childId': id});
        window.location.href = url;
    }
});

$(function()
{
    new SearchChildPage();
});
