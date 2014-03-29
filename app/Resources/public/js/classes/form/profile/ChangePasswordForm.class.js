var ChangePasswordForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        var that = this;
        this._form.find("input").each(function () {
            $(this).rules('add', {
                required: true,
                password: true,
                messages: { password: handler(that, "_getInvalidPasswordMessage") }
            });
        });
        this._initShowPasswordLink();
    }
});