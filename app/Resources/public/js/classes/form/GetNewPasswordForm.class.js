var GetNewPasswordForm = BaseForm.extend({
    constructor: function (id)
    {
        this.base(id);
        this._initShowPasswordLink();
    },

    _needToSubmit: function()
    {
        return true;
    },

    _onGetNewPasswordSuccess: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert('An error acquired while changing your password.');
        }
    }
});

$(function()
{
    var form = new GetNewPasswordForm("getNewPasswordForm");
});