$(function()
{
    jQuery.validator.addMethod("password", function(value, element)
    {
        var MIN_PASSWORD_LENGTH = 8;
        var reg = new RegExp("^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{" + MIN_PASSWORD_LENGTH + ",}$");

        return this.optional(element) || reg.test(value);
    });
    jQuery.validator.addMethod("nicknameSymbols", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$/.test(value);
    }, "Никнейм должен содержать только английские буквы, цифры и знаки");
});
