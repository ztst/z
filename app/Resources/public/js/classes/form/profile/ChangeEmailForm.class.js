var ChangeEmailForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        this._form.find("input").each(function () {
            $(this).rules('add', {
                required: true,
                messages: { required: "Это поле обязательно для заполнения" }
            });
        });

        var that = this;
        this._form.find("#newEmail").rules('add', {
            email: true,
            messages: { email: handler(that, "_getInvalidEmailMessage") }
        });
        this._form.find("#oldPasswordNewEmailField").rules('add', {
            password: true,
            messages: { password: handler(that, "_getInvalidPasswordMessage") }
        });
    },

    clear: function()
    {
        this._form.find("#newEmail, #oldPasswordNewEmailField").val("");
        this._form.find("div.form-error-field").remove();
    }
});