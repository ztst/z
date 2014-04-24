var CompleteSocialRegistrationPopup = Base.extend({

    _createNewUserButton: null,
    _attachUserButton: null,
    _registrationRoleForm: null,

    constructor: function()
    {
        this.base();

        this._openPopup();

        this._createNewUserButton = $("#createNewUser");
        this._attachUserButton = $("#attachUser");

        this._createNewUserButton.click(handler(this, "_onCreateNewUserButtonClick"));
        this._attachUserButton.click(handler(this, "_onAttachUserButtonClick"));

        var switchForgetPasswordLink = $("#showForgetPasswordForm");
        switchForgetPasswordLink.click(handler(this, "_showForgetPasswordForm"));

        var loginForm = $("#loginForm");
        loginForm.submit(handler(this, "_onLoginFormSubmitted"));

        this._initRegistrationForm();
        this._initForgetPasswordForm();
    },

    _showForgetPasswordForm: function()
    {
        $("#attachUserForm").addClass("hidden");
        $("#createNewUserFormContainer").addClass("hidden");

        $("#forgetPasswordCompleteSocialFormContainer").removeClass("hidden");
    },

    _initForgetPasswordForm: function()
    {
        this._forgetPasswordForm = new ForgetPasswordForm("forgetPasswordSocialRegistrationForm");
        this._forgetPasswordForm.addListener(BaseForm.event.SUBMITTED, this, this._onForgetPasswordFormSubmitted);
    },

    _onForgetPasswordFormSubmitted: function()
    {
        var url = this._forgetPasswordForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._forgetPasswordForm.serialize(),
            success: handler(this, "_onSendPasswordSuccess")
        });

        return false;
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
            $("#createNewUserFormContainer").html(response.html);
        }
    },

    _onSendPasswordSuccess: function(response)
    {
        if (response.success)
        {
            $("#forgetPasswordCompleteSocialFormContainer").html(response.html);
        }
        else
        {
            alert('Неверный e-mail');
        }
    },


    _initRegistrationForm: function()
    {
        var registrationForm = $("#registrationForm");
        registrationForm.submit(handler(this, "_onRegistrationFormSubmitted"));
    },

    _onRegistrationFormSubmitted: function()
    {
        var form = $("#registrationForm");
        var url = form.attr("action");
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
            $("#createNewUserFormContainer").html(response.html);
            this._initRegistrationRoleForm();
        }
        else
        {
            $("#registrationForm").after(response.html).remove();

            this._initRegistrationForm();
        }
    },

    _onCreateNewUserButtonClick: function()
    {
        $(".complete-registration-popup-content").addClass("hidden");
        $("#createNewUserFormContainer").removeClass("hidden");
    },

    _onAttachUserButtonClick: function()
    {
        $(".complete-registration-popup-content").addClass("hidden");
        $("#attachUserForm").removeClass("hidden");
    },

    _openPopup: function()
    {
        $.magnificPopup.open({
            items: {
                src: '#completeSocialRegistrationPopup',
                type: 'inline'
            }
        }, 0);
    },

    _onLoginFormSubmitted: function()
    {
        var form = $("#loginForm");
        var url = form.attr("action");
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
    }
});

$(function(){
    if ($("#completeSocialRegistrationPopup").length)
    {
        var popup = new CompleteSocialRegistrationPopup();
    }
});