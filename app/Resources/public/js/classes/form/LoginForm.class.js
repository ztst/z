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
            password: true,
            messages: {
                password: handler(that, "_getInvalidPasswordMessage")
            }
        });

        $('input').iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'iradio'
            });
    }
});