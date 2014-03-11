var BaseForm = EventDispatcher.extend({

    _form: null,

    constructor: function(id)
    {
        this.base();

        this._form = $("#" + id);

        this._form.validate({
            onkeyup: false,
            errorClass: "form-error-field",
            errorElement: "div",
            errorPlacement: function ($error, $element) {
                $element.closest("div").after($error);
                var errorFields = jQuery($element.parents().get(1)).find(".form-error-field")
                errorFields.append('<div class="arrow"></div>');
            }
        });

        this._form.submit(handler(this, "_onFormSubmitted"));
    },

    _onFormSubmitted: function()
    {
        if (this._form.valid())
        {
            this.dispatchEvent(BaseForm.event.SUBMITTED);

            return this._needToSubmit();
        }

        return false;
    },

    _needToSubmit: function()
    {
        return false;
    },

    hide: function()
    {
        this._form.hide();
    },

    serialize: function()
    {
        return this._form.serialize();
    },

    getAction: function()
    {
        return this._form.attr("action");
    },

    _getInvalidEmailMessage: function(parameters, element)
    {
        var email = $(element).val();

        var errorMessage = "Не верный емайл.";
        if(email.indexOf("@") == -1)
        {
            errorMessage = "Адрес должен содержать символ \"@\". Адрес \"" + email + "\" не содержит символ \"@\"";
        }
        else if(email.indexOf("@") == email.length - 1)
        {
            errorMessage = "Введите часть адреса после \"@\". Адрес \"" + email + "\" неполный"
        }
        else if(/^.+\@\.$/.test(email))
        {
            errorMessage = "Недопустимое положение \".\"";
        }
        else if(email.indexOf(".") == email.length - 1)
        {
            errorMessage = "Введите часть адреса после \".\". Адрес \"" + email + "\" неполный";
        }

        return errorMessage;
    }
},{
    event:
    {
        SUBMITTED: "submitted"
    }
});

