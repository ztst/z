var EditProfileForm = BaseForm.extend({

    _fields: null,

    constructor: function(id)
    {
        this.base(id);

        this._initValidators();
        this._saveFieldsValues();

        $('input').iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'iradio'
        });

        var gradeField = $("#gradeField");
        gradeField.selectbox();

        $(".birthdate-container select").selectbox();

    },

    isEdited: function()
    {
        for (var i in this._fields)
        {
            if (this._fields[i].field.val() != this._fields[i].value)
            {
                return true;
            }
        }

        return false;
    },

    _onFormSubmitted: function()
    {
        if (!this.isEdited())
        {
            return false;
        }

        return this.base();
    },

    _saveFieldsValues: function()
    {
        var that = this;
        that._fields = [];
        this._form.find("input").each(function()
        {
            that._fields.push({
                field: $(this),
                value: $(this).val()
            });
        });
    },

    _initValidators: function()
    {
        this._form.find("#firstNameField").rules('add', {
            maxlength: 40,
            messages: { maxlength: "Максимальная длина имени - 40 символов" }
        });
        this._form.find("#lastNameField").rules('add', {
            maxlength: 80,
            messages: { maxlength: "Максимальная длина фамилии - 80 символов" }
        });
        this._form.find("#nicknameField").rules('add', {
            maxlength: 50,
            minlength: 3,
            required: true,
            nicknameSymbols: true,
            messages: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Максимальная длина никнейма - 50 символов",
                minlength: "Никнейм должен быть минимум 3 символа"
            }
        });
        this._form.find("#cityField").rules('add', {
            maxlength: 150,
            messages: { maxlength: "Максимальная длина - 150 символов" }
        });

        var middleNameField = this._form.find("#middleNameField");
        if (middleNameField.length)
        {
            middleNameField.rules('add', {
                maxlength: 40,
                messages: { maxlength: "Максимальная длина отчества - 40 символов" }
            });
        }
    },

    _needToSubmit: function()
    {
        return true;
    }
});