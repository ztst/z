var ChangePasswordForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        var that = this;
        this._form.find("input").each(function () {
            $(this).rules('add', {
                required: true,
                password: true,
                messages: {
                    password: handler(that, "_getInvalidPasswordMessage"),
                    required: "Это поле обязательно для заполнения"
                }
            });
        });

        this._form.find("#newPassword").rules('add', {
            minlength: BaseForm.MIN_PASSWORD_LENGTH,
            messages: { minlength: "Пароль должен содержать более " + (BaseForm.MIN_PASSWORD_LENGTH - 1) + " символов" }
        });
        this._initShowPasswordLink();
    },

    clear: function()
    {
        this._form.find("#newPassword, #oldPasswordField").val("");
        this._form.find("div.form-error-field").remove();
    }
});