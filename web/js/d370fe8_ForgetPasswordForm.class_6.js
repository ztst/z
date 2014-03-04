var ForgetPasswordForm = BaseForm.extend({
    constructor: function (id)
    {
        this.base(id);

        this._form.find("input[type='email']").rules('add', {
            email: true,
            messages: { email: "Не верный адрес." }
        });
    }
});