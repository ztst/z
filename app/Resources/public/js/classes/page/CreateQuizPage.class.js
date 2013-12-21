var CreateQuizPage = Base.extend({

    _form: null,

    constructor: function()
    {
        this.base();

        this._initForm();
    },

    _initForm: function()
    {
        this._form = new CreateQuizForm();
    }
});

$(function(){
    var page = new CreateQuizPage();
});
