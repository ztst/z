var LoginForm = BaseForm.extend({
    constructor: function(id)
    {
        this.base(id);

        var that = this;
        this._form.find("input[id!=rememberMe]").each(function () {
            $(this).rules('add', {
                required: true,
                messages: { required: "Это поле обязательно для заполнения" }
            });
        });
        this._form.find("#username").rules('add', {
            email: true,
            messages: { email: handler(that, "_getInvalidEmailMessage") }
        });
        this._form.find("#password").rules('add', {
            minlength: BaseForm.MIN_PASSWORD_LENGTH,
            password: true,
            messages: {
                minlength: "Пароль должен содержать более " + (BaseForm.MIN_PASSWORD_LENGTH - 1) + " символов",
                password: handler(that, "_getInvalidPasswordMessage")
            }
        });

        $('input').iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'iradio'
            });
    }
});