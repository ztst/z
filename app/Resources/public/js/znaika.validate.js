$(function()
{
    jQuery.validator.addMethod("passwordSymbols", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$/.test(value);
    }, "Пароль должен содержать только английские буквы, цифры и знаки");
    jQuery.validator.addMethod("nicknameSymbols", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9\!\'\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$/.test(value);
    }, "Никнейм должен содержать только английские буквы, цифры и знаки");
});
