$(function()
{
    jQuery.validator.addMethod("password",
        function(value, element)
        {
            var reg = new RegExp("^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$");

            return this.optional(element) || reg.test(value);
        }
    );

    jQuery.validator.addMethod("nicknameSymbols",
        function(value, element)
        {
            return this.optional(element) || /^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$/.test(value);
        },
        "Никнейм должен содержать только английские буквы, цифры и знаки"
    );

    jQuery.validator.addMethod("nameSymbols",
        function(value, element)
        {
            return this.optional(element) || /^[A-ZА-ЯЁ\s]{1,}$/gi.test(value);
        },
        "Допустимы только русские и английские буквы"
    );
});
