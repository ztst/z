var TeacherProfilePage = ProfilePage.extend({
    _getEditProfileFormId: function()
    {
        return "editTeacherProfileForm";
    }
});

$(function()
{
    new TeacherProfilePage();
});
