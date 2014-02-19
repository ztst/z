var CompleteSocialRegistrationPopup = Base.extend({

    _createNewUserButton: null,
    _attachUserButton: null,

    constructor: function()
    {
        this.base();

        this._openPopup();

        this._createNewUserButton = $("#createNewUser");
        this._attachUserButton = $("#attachUser");

        this._createNewUserButton.click(handler(this, "_onCreateNewUserButtonClick"))
        this._attachUserButton.click(handler(this, "_onAttachUserButtonClick"))

        var loginForm = $("#loginForm");
        loginForm.submit(handler(this, "_onLoginFormSubmitted"));

        this._initRegistrationForm();
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
            $("#registrationFormContainer").html(response.html);
        }
        else
        {
            $(".registration-form").remove();
            $("#registrationFormContainer .form-header").after(response.html);

            this._initRegistrationForm();
        }
    },

    _onCreateNewUserButtonClick: function()
    {
        $(".complete-registration-popup-content").addClass("hidden");
        $("#registrationFormContainer").removeClass("hidden");
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