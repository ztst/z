var MainMenu = EventDispatcher.extend({

    _loginPopupLink: null,
    _loginPopup: null,

    _showBadgesPopupLink: null,
    _badgesPopup: null,

    _userHasViewedBadgesUrl: null,

    _registrationForm: null,
    _loginForm: null,
    _forgetPasswordForm: null,

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
        this._loginPopupLink = $(".login-button");
        this._loginPopupLink.magnificPopup({
            type: "ajax",
            callbacks: { ajaxContentAdded: handler(this, "_onLoginFormLoaded") }
        });
    },

    _onLoginFormLoaded: function()
    {
        var switchLoginLink = $("#switchLoginLink");
        switchLoginLink.click(handler(this, "_showLoginForm"));

        var switchRegistrationLink = $("#switchRegistrationLink");
        switchRegistrationLink.click(handler(this, "_showRegistrationForm"));

        var switchForgetPasswordLink = $("#switchForgetPasswordLink");
        switchForgetPasswordLink.click(handler(this, "_showForgetPasswordForm"));

        this._initRegistrationForm();
        this._initLoginForm();
        this._initForgetPasswordForm();
    },

    _initLoginForm: function()
    {
        this._loginForm = new LoginForm("loginForm");
        this._loginForm.addListener(BaseForm.event.SUBMITTED, this, this._onLoginFormSubmitted);
        $("#switchForgetPasswordLink").click(function(){
            return false;
        });
    },

    _initRegistrationForm: function()
    {
        this._registrationForm = new RegistrationForm("registrationForm");
        this._registrationForm.addListener(BaseForm.event.SUBMITTED, this, this._onRegistrationFormSubmitted);

        this._initShowPasswordLink();
    },

    _initForgetPasswordForm: function()
    {
        this._forgetPasswordForm = new ForgetPasswordForm("forgetPasswordForm");
        this._forgetPasswordForm.addListener(BaseForm.event.SUBMITTED, this, this._onForgetPasswordFormSubmitted);
    },

    _showLoginForm: function()
    {
        $("#registrationFormContainer").addClass("hidden");
        $("#forgetPasswordFormContainer").addClass("hidden");

        $("#loginFormContainer").removeClass("hidden");
    },

    _showRegistrationForm: function()
    {
        $("#loginFormContainer").addClass("hidden");
        $("#forgetPasswordFormContainer").addClass("hidden");

        $("#registrationFormContainer").removeClass("hidden");
    },

    _showForgetPasswordForm: function()
    {
        $("#loginFormContainer").addClass("hidden");
        $("#registrationFormContainer").addClass("hidden");

        $("#forgetPasswordFormContainer").removeClass("hidden");
    },

    _onLoginFormSubmitted: function()
    {
        var url = this._loginForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._loginForm.serialize(),
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
        var url = this._registrationForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._registrationForm.serialize(),
            success: handler(this, "_onSendRegistrationComplete")
        });

        return false;
    },

    _onSendRegistrationComplete: function(response)
    {
        if (response.success)
        {
            $("#registrationFormContainer").html(response.html);
        }
        else
        {
            this._registrationForm.hide();
            $("#registrationFormContainer .login-popup-header").after(response.html);

            this._initRegistrationForm();
        }
    },

    _onForgetPasswordFormSubmitted: function()
    {
        var form = $("#forgetPasswordForm");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendPasswordSuccess")
        });

        return false;
    },

    _onSendPasswordSuccess: function(response)
    {
        if (response.success)
        {
            $("#forgetPasswordFormContainer").html(response.html);
        }
        else
        {
            alert('Неверный e-mail');
        }
    },

    _initBadgesPopup: function()
    {
        this._showBadgesPopupLink = $(".new-user-badge-link");
        this._showBadgesPopupLink.magnificPopup({
            type: "inline",
            midClick: true,
            callbacks: { open: handler(this, "_onShowUserBadgesLinkClick")}
        });
    },

    _onShowUserBadgesLinkClick: function()
    {
        this._showBadgesPopupLink.remove();

        $.post(this._userHasViewedBadgesUrl, null, handler(this, "_onSaveViewingBadgesComplete"), "json");
    },

    _initShowPasswordLink: function()
    {
        var showPasswordLink = $(".show-password-icon");
        if (navigator.userAgent.search("MSIE") >= 0)
        {
            $(".show-password-icon").remove();
            showPasswordLink.width(1);
        }
        else
        {
            showPasswordLink.click(handler(this, "_onShowPasswordLinkClick"));
        }
    },

    _onShowPasswordLinkClick: function(event)
    {
        var link = $(event.target);
        var container = link.parent();

        var passwordInput = container.find(".password-input");
        var currentType = passwordInput.attr("type");
        if (currentType == "text")
        {
            passwordInput.attr("type", "password");
            link.removeClass("opened");
        }
        else
        {
            passwordInput.attr("type", "text");
            link.addClass("opened");
        }
    },

    _onSaveViewingBadgesComplete: function(response)
    {
    }
});

$(function()
{
    var mainMenu = new MainMenu();

    mainMenu.setUserHasViewedBadgesUrl(Routing.generate("user_has_viewed_badges"))
});