var UserSettingsPage = Base.extend({

    _changeEmailForm: null,
    _changePasswordForm: null,
    _changePasswordOnRegisterCompleteForm: null,

    _showChangeEmailFormLink: null,
    _showChangePasswordFormLink: null,
    _isLoading: null,

    constructor: function()
    {
        this.base();

        this._changeEmailForm = new ChangeEmailForm("changeEmailForm");
        this._changeEmailForm.addListener(BaseForm.event.SUBMITTED, this, this._onChangeEmailFormSubmitted);

        this._changePasswordForm = new ChangePasswordForm("changePasswordForm");
        this._changePasswordForm.addListener(BaseForm.event.SUBMITTED, this, this._onChangePasswordFormSubmitted);
        this._initAccountSettingsFormSwitching();

        $("#saveSettingsButton").click(handler(this, "_onSaveSettingsButtonClick"));
        $('input').iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'iradio'
        });
    },

    _onSaveSettingsButtonClick: function()
    {
        if (this._isLoading)
        {
            return false;
        }
        this._isLoading = true;

        var showUserPage = $("[name='showUserPage']:checked").val();

        var data = {
            showUserProfile: showUserPage
        };
        var url = Routing.generate("edit_user_settings_ajax");
        $.post(url, data, handler(this, '_onSettingsSaved'), 'json');

        return false;
    },

    _onSettingsSaved: function(response)
    {
        this._isLoading = false;
        if (response.success)
        {
            alert("Изменения сохранены");
        }
    },

    _initAccountSettingsFormSwitching: function()
    {
        this._showChangeEmailFormLink = $("#editUserEmailLink");
        this._showChangePasswordFormLink = $("#editUserPasswordLink");

        this._showChangeEmailFormLink.click(handler(this, "_onShowChangeEmailFormLink"));
        this._showChangePasswordFormLink.click(handler(this, "_onShowChangePasswordFormLink"));
    },

    _onShowChangeEmailFormLink: function()
    {
        this._changeEmailForm.clear();
        $(".change-email-form-container").toggleClass("hidden");
        $(".change-password-form-container").addClass("hidden");
        $(".change-password-controller").toggleClass("hidden");

        this._showChangeEmailFormLink.toggleClass("activated");
        this._showChangePasswordFormLink.removeClass("activated");
    },

    _onShowChangePasswordFormLink: function()
    {
        this._changePasswordForm.clear();
        $(".change-email-form-container").addClass("hidden");
        $(".change-password-form-container").toggleClass("hidden");
        $(".change-password-controller").removeClass("hidden");

        this._showChangePasswordFormLink.toggleClass("activated")
    },

    _closeAccountSettingsForms: function()
    {
        $(".change-email-form-container").addClass("hidden");
        $(".change-password-form-container").addClass("hidden");
        $(".change-password-controller").removeClass("hidden");

        $(".edit-account-setting-button").removeClass("activated");
    },

    _onChangeEmailFormSubmitted: function()
    {
        var url = this._changeEmailForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._changeEmailForm.serialize(),
            success: handler(this, "_onChangeEmailComplete")
        });

        return false;
    },

    _onChangeEmailComplete: function(response)
    {
        if (response.success)
        {
            $.magnificPopup.open({
                items: {
                    src: '#changeEmailSendSuccessPopup',
                    type: 'inline'
                }
            }, 0);
            this._closeAccountSettingsForms();
        }
        else
        {
            if (response.emailBusy)
            {
                alert("Извините, емейл уже занят другим пользователем.");
            }
            else
            {
                alert("Введен неверный текущий пароль");
            }
        }
    },

    _onChangePasswordFormSubmitted: function()
    {
        var url = this._changePasswordForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._changePasswordForm.serialize(),
            success: handler(this, "_onChangePasswordComplete")
        });

        return false;
    },

    _onChangePasswordComplete: function(response)
    {
        if (response.success)
        {
            $.magnificPopup.close();
            this._closeAccountSettingsForms();
        }
        else
        {
            alert("Введен неверный текущий пароль");
        }
    }
});

$(function(){
    new UserSettingsPage();
});