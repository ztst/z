$(function()
{
    jQuery.validator.addMethod("passwordSymbols", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9\!\"\№\;\%\:\?/*\(\)\-\=\_\+\\\/\<\>\,\.\?\~]{1,}$/.test(value);
    }, "Необходимо сменить язык. Пароль должен содержать только английские буквы, цифры и знаки");
});
