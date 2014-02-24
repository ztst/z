var AddCommentForm = BaseForm.extend({

    _isQuestionButton: null,
    _isQuestionInput: null,

    constructor: function(id)
    {
        this.base(id);

        this._form.find("textarea").rules('add', {
            maxlength: AddCommentForm.COMMENT_MAX_LENGTH,
            messages: { maxlength: "Превышен размер комментария (" + AddCommentForm.COMMENT_MAX_LENGTH + " знаков)" }
        });
        this._form.find("textarea").rules('add', {
            required: true,
            messages: { required: "Комментарий не может быть пустым." }
        });

        this._isQuestionButton = $("#isQuestionButton");
        this._isQuestionButton.click(handler(this, "_onIsQuestionButtonClick"));

        this._isQuestionInput = $("#isQuestionInput");
    },

    _needToSubmit: function()
    {
        return true;
    },

    _onIsQuestionButtonClick: function()
    {
        this._isQuestionButton.toggleClass("selected");
        this._isQuestionInput.val(this._isQuestionButton.hasClass("selected") ? 1 : 0);
    }
},{
    COMMENT_MAX_LENGTH: 4096
});