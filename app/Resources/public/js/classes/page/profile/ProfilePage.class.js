var ProfilePage = Base.extend({

    _editProfileForm: null,
    _userPhotoForm: null,
    _changeEmailForm: null,
    _changePasswordForm: null,
    _changePasswordOnRegisterCompleteForm: null,

    _showChangeEmailFormLink: null,
    _showChangePasswordFormLink: null,

    constructor: function()
    {
        this.base();

        this._editProfileForm = new EditProfileForm(this._getEditProfileFormId());
        this._userPhotoForm = new UserPhotoForm();

        this._changeEmailForm = new ChangeEmailForm("changeEmailForm");
        this._changeEmailForm.addListener(BaseForm.event.SUBMITTED, this, this._onChangeEmailFormSubmitted);

        this._changePasswordForm = new ChangePasswordForm("changePasswordForm");
        this._changePasswordForm.addListener(BaseForm.event.SUBMITTED, this, this._onChangePasswordFormSubmitted);

        this._initPageCloseHandler();
        this._initAccountSettingsFormSwitching();
        this._initChangePasswordOnSocialRegisterCompletePopup();
    },

    _getEditProfileFormId: function()
    {
        return "editUserProfileForm";
    },

    _initChangePasswordOnSocialRegisterCompletePopup: function()
    {
        if ($("#changePasswordOnRegisterCompletePopup").length)
        {
            this._changePasswordOnRegisterCompleteForm = new ChangePasswordForm("changePasswordOnRegisterCompleteForm");
            this._changePasswordOnRegisterCompleteForm.addListener(BaseForm.event.SUBMITTED, this, this._onChangePasswordOnCompleteRegisterFormSubmitted);

            $("#closeChangePasswordPopup").click($.magnificPopup.close);
            $.magnificPopup.open({
                items: {
                    src: '#changePasswordOnRegisterCompletePopup',
                    type: 'inline'
                }
            }, 0);
        }
    },

    _initAccountSettingsFormSwitching: function()
    {
        this._showChangeEmailFormLink = $("#editUserEmailLink");
        this._showChangePasswordFormLink = $("#editUserPasswordLink");

        this._showChangeEmailFormLink.click(handler(this, "_onShowChangeEmailFormLink"));
        this._showChangePasswordFormLink.click(handler(this, "_onShowChangePasswordFormLink"));

        $(".cancel-edit-account").click(handler(this, "_closeAccountSettingsForms"));
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

    _onChangePasswordOnCompleteRegisterFormSubmitted: function()
    {
        var url = this._changePasswordOnRegisterCompleteForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._changePasswordOnRegisterCompleteForm.serialize(),
            success: handler(this, "_onChangePasswordComplete")
        });

        return false;
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
    },

    _initPageCloseHandler: function()
    {
        var that = this;

        window.onbeforeunload = function (e) {
            if (!that._editProfileForm.isEdited())
            {
                return undefined;
            }

            var message = "Вы изменили информацию о себе. Вы уверены, что хотите покинуть эту страницу?";
            if (typeof e == "undefined") {
                e = window.event;
            }
            if (e) {
                e.returnValue = message;
            }
            return message;
        };
        $(document).submit(function(){
            window.onbeforeunload = null;
        });
    }
});
