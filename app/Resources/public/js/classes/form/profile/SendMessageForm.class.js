var SendMessageForm = BaseForm.extend({

    constructor: function(id)
    {
        this.base(id);

        this._form.find("textarea").rules('add', {
            maxlength: SendMessageForm.MESSAGE_MAX_LENGTH,
            messages: { maxlength: "Превышен размер сообщения (" + SendMessageForm.MESSAGE_MAX_LENGTH + " знаков)" }
        });
        this._form.find("textarea").rules('add', {
            required: true,
            messages: { required: "Сообщение не может быть пустым." }
        });
    },

    clear: function()
    {
        this._form.find("textarea").val("");
    }
},{
    MESSAGE_MAX_LENGTH: 4096
});