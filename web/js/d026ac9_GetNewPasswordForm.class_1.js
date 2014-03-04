var GetNewPasswordForm = BaseForm.extend({
    constructor: function (id)
    {
        this.base(id);
        this._initShowPasswordLink();
    },

    _initShowPasswordLink: function()
    {
        var showPasswordLink = this._form.find(".show-password-icon");
        if (navigator.userAgent.search("MSIE") >= 0)
        {
            showPasswordLink.remove();
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