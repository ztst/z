var CreateQuizForm = Base.extend({

    _answersContainer: null,
    _addAnswerButton: null,

    _addAnswerButtonLi: null,
    _questionTypeSelect: null,

    constructor: function ()
    {
        this.base();

        this._addAnswerButton = $('<a href="#" class="add_answer_link">Добавить ответ.</a>');
        this._addAnswerButton.click(handler(this, "_addAnswerForm"));
        this._addAnswerButtonLi = $('<li class="list-group-item"></li>').append(this._addAnswerButton);

        this._answersContainer = $('#quizAnswers');
        this._answersContainer.append(this._addAnswerButtonLi);
        this._answersContainer.data('index', this._answersContainer.find(':input').length);

        this._questionTypeSelect = $("#quizTypeSelectContainer").find("select");
        this._questionTypeSelect.change(handler(this, "_onTypeChanged"));
    },

    _onTypeChanged: function()
    {
        var checkboxes = this._answersContainer.find('input[type=checkbox]');

        var type = parseInt(this._questionTypeSelect.val());
        switch (type)
        {
            case QuizQuestionTypes.CHOICE:
                checkboxes.removeAttr("checked");
                checkboxes.change(handler(this, '_onRightAnswerChange'));
                break;
            case QuizQuestionTypes.MULTIPLE_CHOICE:
                checkboxes.unbind('change');
                break;
        }
    },

    _onRightAnswerChange: function(event)
    {
        var checkboxes = this._answersContainer.find('input[type=checkbox]');
        checkboxes.removeAttr("checked");

        $(event.target).prop("checked", true);
    },

    _addAnswerForm: function ()
    {
        var prototype = this._answersContainer.data('prototype');

        var index = this._answersContainer.data('index');

        var newForm = prototype.replace(/__name__/g, index);

        this._answersContainer.data('index', index + 1);

        var newFormLi = $('<li class="list-group-item"></li>').append(newForm);
        this._addAnswerButtonLi.before(newFormLi);

        this._addDeleteAnswerLink(newFormLi);
        newFormLi.find('input[type=checkbox]').change(handler(this, '_onRightAnswerChange'));

        return false;
    },

    _addDeleteAnswerLink: function (answerForm)
    {
        var removeLink = $('<a href="#">Удалить ответ</a>');
        answerForm.append(removeLink);

        removeLink.click(function (){
            answerForm.remove();
            return false;
        });
    }
});

