var MainMenu = EventDispatcher.extend({

    _showBadgesPopupLink: null,
    _badgesPopup: null,

    _userHasViewedBadgesUrl: null,

    constructor: function ()
    {
        this.base();

        this._badgesPopup = $("#badgesPopup").dialog({
            autoOpen: false,
        });

        this._showBadgesPopupLink = $('.new_user_badge_link')
        this._showBadgesPopupLink.click(handler(this, '_onShowUserBadgesLinkClick'));
    },

    setUserHasViewedBadgesUrl: function(url)
    {
        this._userHasViewedBadgesUrl = url;
    },

    _onShowUserBadgesLinkClick: function ()
    {
        this._showBadgesPopupLink.remove();
        this._badgesPopup.dialog("open");

        $.post( this._userHasViewedBadgesUrl, null, handler( this, '_onSaveViewingBadgesComplete' ), 'json' );
    },

    _onSaveViewingBadgesComplete: function(response)
    {
    }
});

$(function ()
{
    var mainMenu = new MainMenu();

    mainMenu.setUserHasViewedBadgesUrl(Routing.generate('user_has_viewed_badges'))
});
