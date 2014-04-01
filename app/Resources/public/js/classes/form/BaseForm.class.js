var BaseForm = EventDispatcher.extend({

    _form: null,

    constructor: function(id)
    {
        this.base();

        this._form = $("#" + id);

        var that = this;
        this._form.validate({
            onkeyup: false,
            errorClass: "form-error-field",
            errorElement: "div",
            errorPlacement: function($error, $element)
            {
                var formGroup = $element.closest(".form-group");
                formGroup.after($error);
            },
            showErrors: function(errorMap, errorList) {
                if (errorList.length)
                {
                    var firstError = errorList.shift();
                    var newErrorList = [];
                    newErrorList.push(firstError);
                    this.errorList = newErrorList;
                }
                that._form.find("div.form-error-field").remove();
                this.defaultShowErrors();
            },
            invalidHandler: function()
            {
                setTimeout(function()
                {
                    $('input, select').trigger('refresh');
                }, 1)
            }
        });

        this._form.submit(handler(this, "_onFormSubmitted"));
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

    _initShowPasswordLink: function()
    {
        var showPasswordLink = this._form.find(".show-password-icon");
        if (navigator.userAgent.search("MSIE") >= 0)
        {
            showPasswordLink.remove();
            showPasswordLink.width(1);
        }
        else
        {
            showPasswordLink.click(handler(this, "_onShowPasswordLinkClick"));
        }
    },

    _onShowPasswordLinkClick: function(event)
    {
        var link = $(event.target);
        var container = link.parent();

        var passwordInput = container.find(".password-input");
        var currentType = passwordInput.attr("type");
        if (currentType == "text")
        {
            passwordInput.attr("type", "password");
            link.removeClass("opened");
        }
        else
        {
            passwordInput.attr("type", "text");
            link.addClass("opened");
        }
    },
    _getInvalidEmailMessage: function(parameters, element)
    {
        var email = $(element).val();

        var errorMessage = "Не верный емайл.";
        if (email.indexOf("@") == -1)
        {
            errorMessage = "Адрес должен содержать символ \"@\". Адрес \"" + email + "\" не содержит символ \"@\"";
        }
        else if (email.indexOf("@") == email.length - 1)
        {
            errorMessage = "Введите часть адреса после \"@\". Адрес \"" + email + "\" неполный"
        }
        else if (/^.+\@\.$/.test(email))
        {
            errorMessage = "Недопустимое положение \".\"";
        }
        else if (email.indexOf(".") == email.length - 1)
        {
            errorMessage = "Введите часть адреса после \".\". Адрес \"" + email + "\" неполный";
        }

        return errorMessage;
    },
    _getInvalidPasswordMessage: function(parameters, element)
    {
        var password = $(element).val();

        var errorMessage = "Не верный пароль.";
        if (password.length < BaseForm.MIN_PASSWORD_LENGTH)
        {
            errorMessage = "Пароль должен содержать более " + (BaseForm.MIN_PASSWORD_LENGTH - 1) + " символов";
        }
        else if (/[^a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]/.test(password))
        {
            errorMessage = "Пароль должен содержать только английские буквы, цифры и знаки";
        }

        return errorMessage;
    }
},
{
    MIN_PASSWORD_LENGTH: 8,

    event: {
        SUBMITTED: "submitted"
    }
});