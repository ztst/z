var SearchParentPage = Base.extend({

    _searchTab: null,

    constructor: function()
    {
        this.base();

        this._searchTab = new SearchUserTab(Routing.generate('search_parent_ajax'));
        this._searchTab.addListener(SearchUserTab.event.ADD_PARENT, this, this._onAddParent);
    },

    _onAddParent: function(id)
    {
        var url = Routing.generate("add_parent", {'parentId': id});
        window.location.href = url;
    }
});

$(function()
{
    new SearchParentPage();
});
