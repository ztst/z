var MainMenu = EventDispatcher.extend({

    _loginPopupLink: null,
    _loginPopup: null,

    _registrationRoleForm: null,
    _registrationForm: null,
    _loginForm: null,
    _forgetPasswordForm: null,

    constructor: function()
    {
        this.base();

        this._initLoginPopup();
    },

    _initLoginPopup: function()
    {
        this._loginPopupLink = $(".login-button");
        this._loginPopupLink.magnificPopup({
            type: "ajax",
            ajax: {
                settings: {
                    url: Routing.generate('login'),
                    type : 'POST'
                }
            },
            callbacks: { ajaxContentAdded: handler(this, "_onLoginFormLoaded") }
        });

        this._registerPopupLink = $(".registration-button");
        this._registerPopupLink.magnificPopup({
            type: "ajax",
            ajax: {
                settings: {
                    url: Routing.generate('login'),
                    data: {showRegisterForm: true},
                    type : 'POST'
                }
            },
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
    },

    _initRegistrationRoleForm: function()
    {
        this._registrationRoleForm = $("#registrationRoleForm");
        this._registrationRoleForm.submit(handler(this, "_onRegistrationRoleSubmit"));
        this._registrationRoleForm.find("select").selectbox();
    },

    _onRegistrationRoleSubmit: function()
    {
        var url = this._registrationRoleForm.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: this._registrationRoleForm.serialize(),
            success: handler(this, "_onSendRegistrationRoleSuccess")
        });

        return false;
    },

    _onSendRegistrationRoleSuccess: function(response)
    {
        if (response.success)
        {
            $("#registrationFormContainer").html(response.html);
        }
    },

    _initRegistrationForm: function()
    {
        this._registrationForm = new RegistrationForm("registrationForm");
        this._registrationForm.addListener(BaseForm.event.SUBMITTED, this, this._onRegistrationFormSubmitted);
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
            this._initRegistrationRoleForm();
        }
        else
        {
            this._registrationForm.hide();
            $("#registrationFormContainer").find(".login-popup-header").after(response.html);

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
    }
});

$(function()
{
    new MainMenu();
});