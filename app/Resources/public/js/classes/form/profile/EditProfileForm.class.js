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
        $("#regionField").selectbox();

    },

    isEdited: function()
    {
        for (var i in this._fields)
        {
            var field = this._fields[i];
            if (field.field.val() != field.value)
            {
                return true;
            }
            if (field.field.prop("checked") != field.checked)
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
        this._form.find("input, select").each(function()
        {
            that._fields.push({
                field: $(this),
                value: $(this).val(),
                checked: $(this).prop("checked")
            });
        });
    },

    _initValidators: function()
    {
        this._form.find("#firstNameField").rules('add', {
            maxlength: EditProfileForm.MAX_FIELD_LENGTH,
            nameSymbols: true,
            messages: { maxlength: "Максимальная длина имени - " + EditProfileForm.MAX_FIELD_LENGTH + " символов" }
        });
        this._form.find("#lastNameField").rules('add', {
            maxlength: EditProfileForm.MAX_FIELD_LENGTH,
            nameSymbols: true,
            messages: { maxlength: "Максимальная длина фамилии - " + EditProfileForm.MAX_FIELD_LENGTH + " символов" }
        });
        this._form.find("#nicknameField").rules('add', {
            maxlength: EditProfileForm.MAX_FIELD_LENGTH,
            minlength: 3,
            required: true,
            nicknameSymbols: true,
            messages: {
                required: "Это поле обязательно для заполнения",
                maxlength: "Максимальная длина никнейма - " + EditProfileForm.MAX_FIELD_LENGTH + " символов",
                minlength: "Никнейм должен быть минимум 3 символа"
            }
        });
        this._form.find("#regionField").rules('add', {
            maxlength: EditProfileForm.MAX_FIELD_LENGTH,
            messages: { maxlength: "Максимальная длина - " + EditProfileForm.MAX_FIELD_LENGTH + " символов" }
        });
        this._form.find("#cityField").rules('add', {
            maxlength: EditProfileForm.MAX_FIELD_LENGTH,
            messages: { maxlength: "Максимальная длина - " + EditProfileForm.MAX_FIELD_LENGTH + " символов" }
        });

        var middleNameField = this._form.find("#middleNameField");
        if (middleNameField.length)
        {
            middleNameField.rules('add', {
                maxlength: EditProfileForm.MAX_FIELD_LENGTH,
                nameSymbols: true,
                messages: { maxlength: "Максимальная длина отчества - " + EditProfileForm.MAX_FIELD_LENGTH + " символов" }
            });
        }

        this._initBirthdayValidation();
    },

    _initBirthdayValidation: function()
    {
        this._form.find("#birthDateField select").each(function(){
            $(this).rules('add', {
                required: function(element){
                    var required = false;
                    $("#birthDateField select").each(function(){
                        required = required ? required : $(this).val() != "";
                    });
                    return required;
                },
                messages: { required: "Необходимо заполнить все поля даты рождения" }
            });
        });
    },

    _needToSubmit: function()
    {
        return true;
    }
},{
    MAX_FIELD_LENGTH: 32
});