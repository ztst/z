var ProfilePage = Base.extend({

    _editProfileForm: null,
    _userPhotoForm: null,
    _changePasswordOnRegisterCompleteForm: null,

    constructor: function()
    {
        this.base();

        this._editProfileForm = new EditProfileForm(this._getEditProfileFormId());
        this._userPhotoForm = new UserPhotoForm();

        this._initPageCloseHandler();
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
