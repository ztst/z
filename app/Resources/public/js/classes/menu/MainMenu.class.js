var MainMenu = EventDispatcher.extend({

    _loginPopupLink: null,
    _loginPopup: null,

    _showBadgesPopupLink: null,
    _badgesPopup: null,

    _userHasViewedBadgesUrl: null,

    constructor: function()
    {
        this.base();

        this._initBadgesPopup();
        this._initLoginPopup();
    },

    setUserHasViewedBadgesUrl: function(url)
    {
        this._userHasViewedBadgesUrl = url;
    },

    _initLoginPopup: function()
    {
        this._loginPopupLink = $('.login_button');
        this._loginPopupLink.magnificPopup({
            type: 'ajax',
            callbacks: { ajaxContentAdded: handler(this, '_onLoginFormLoaded') }
        });
    },

    _onLoginFormLoaded: function()
    {
        var switchLoginLink = $('#switchLoginLink');
        switchLoginLink.click(handler(this, "_showLoginForm"));

        var switchRegistrationLink = $('#switchRegistrationLink');
        switchRegistrationLink.click(handler(this, "_showRegistrationForm"));

        this._initRegistrationForm();
        this._initLoginForm();
    },

    _initLoginForm: function()
    {
        var loginForm = $('#loginForm');
        loginForm.submit(handler(this, "_onLoginFormSubmitted"));
    },

    _initRegistrationForm: function()
    {
        var registrationForm = $('#registrationForm');
        registrationForm.submit(handler(this, "_onRegistrationFormSubmitted"));
    },

    _showLoginForm: function()
    {
        $('#registrationFormContainer').addClass('hidden');
        $('#loginFormContainer').removeClass('hidden');
    },

    _showRegistrationForm: function()
    {
        $('#loginFormContainer').addClass('hidden');
        $('#registrationFormContainer').removeClass('hidden');
    },

    _onLoginFormSubmitted: function()
    {
        var form = $('#loginForm');
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendLoginSuccess")
        });

        return false;
    },

    _onSendLoginSuccess: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert("Неверный логин/пароль");
        }
    },

    _onRegistrationFormSubmitted: function()
    {
        var form = $('#registrationForm');
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendRegistrationComplete")
        });

        return false;
    },

    _onSendRegistrationComplete: function(response)
    {
        if (response.success)
        {
            $('#registrationFormContainer').html(response.html);
        }
        else
        {
            $('#registrationForm').remove();
            $('#switchLoginLink').after(response.html);

            this._initRegistrationForm();
        }
    },

    _initBadgesPopup: function()
    {
        this._showBadgesPopupLink = $('.new_user_badge_link');
        this._showBadgesPopupLink.magnificPopup({
            type: 'inline',
            midClick: true,
            callbacks: { open: handler(this, '_onShowUserBadgesLinkClick')}
        });
    },

    _onShowUserBadgesLinkClick: function()
    {
        this._showBadgesPopupLink.remove();

        $.post(this._userHasViewedBadgesUrl, null, handler(this, '_onSaveViewingBadgesComplete'), 'json');
    },

    _onSaveViewingBadgesComplete: function(response)
    {
    }
});

$(function()
{
    var mainMenu = new MainMenu();

    mainMenu.setUserHasViewedBadgesUrl(Routing.generate('user_has_viewed_badges'))
});
