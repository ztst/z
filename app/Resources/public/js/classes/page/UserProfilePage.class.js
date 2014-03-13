var UserProfilePage = Base.extend({

    _editProfileForm: null,
    _userPhotoForm: null,

    constructor: function()
    {
        this.base();

        this._editProfileForm = new EditProfileForm("editUserProfileForm");
        this._userPhotoForm = new UserPhotoForm();

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

$(function()
{
    var userProfilePage = new UserProfilePage();
});
