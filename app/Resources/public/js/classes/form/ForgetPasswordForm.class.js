var ForgetPasswordForm = BaseForm.extend({
    constructor: function (id)
    {
        this.base(id);

        this._form.find("#password_recovery_user").rules('add', {
            email: true,
            messages: { email: "Не верный адрес." }
        });
    }
});