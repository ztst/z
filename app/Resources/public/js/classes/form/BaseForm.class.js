var BaseForm = EventDispatcher.extend({

    _form: null,

    constructor: function(id)
    {
        this.base();

        this._form = $("#" + id);

        this._form.validate({
            errorClass: "form-error-field",
            errorElement: "div",
            errorPlacement: function ($error, $element) {
                $element.closest("div").after($error);
            }
        });

        this._form.submit(handler(this, "_onFormSubmitted"));
    },

    _onFormSubmitted: function()
    {
        if (this._form.valid())
        {
            this.dispatchEvent(BaseForm.event.SUBMITTED);
        }

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
    }
},{
    event:
    {
        SUBMITTED: "submitted"
    }
});

