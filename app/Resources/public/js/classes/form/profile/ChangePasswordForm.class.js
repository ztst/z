var ChangePasswordForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        this._form.find("input").each(function () {
            $(this).rules('add', {
                required: true,
                passwordSymbols: true,
                messages: { required: "Это поле обязательно для заполнения" }
            });
        });
        this._initShowPasswordLink();
    }
});