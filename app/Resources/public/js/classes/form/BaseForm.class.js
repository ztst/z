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
            errorPlacement: function($error, $element)
            {
                $element.closest("div").after($error);
                var errorFields = $($element.parents().get(1)).find(".form-error-field");
                errorFields.append('<div class="arrow"></div>');
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
    }
}, {
    event: {
        SUBMITTED: "submitted"
    }
});

