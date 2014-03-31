var AddSupportForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        var that = this;
        this._form.find("input").each(function () {
            $(this).rules('add', {
                required: true,
                messages: { required: "Это поле обязательно для заполнения" }
            });
        });
        this._form.find("#supportUserEmail").rules('add', {
            email: true,
            messages: { email: handler(that, "_getInvalidEmailMessage") }
        });
        this._form.find("textarea").rules('add', {
            maxlength: AddSupportForm.MESSAGE_MAX_LENGTH,
            required: true,
            messages: {
                maxlength: "Превышен размер комментария (" + AddSupportForm.MESSAGE_MAX_LENGTH + " знаков)",
                required: "Комментарий не может быть пустым."
            }
        });
    },

    _needToSubmit: function()
    {
        return true;
    }
},{
    MESSAGE_MAX_LENGTH: 4096
});

$(function(){
    new AddSupportForm("addSupportForm");
});