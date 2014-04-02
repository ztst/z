var UserTimezone = Base.extend({

    constructor: function()
    {
        if (!CookieUtil.getCookie(UserTimezone.TIMEZONE_COOKIE_NAME))
        {
            var tz = jstz.determine(); // Determines the time zone of the browser client
            var name = tz.name(); // Returns the name of the time zone eg "Europe/Berlin"
            CookieUtil.setCookie("tz", name);
        }
    }
},{
    TIMEZONE_COOKIE_NAME: "tz",

    _instance: null,

    createInstance: function()
    {
        UserTimezone._instance = new UserTimezone();
    },

    getInstance: function()
    {
        if (!UserTimezone._instance)
        {
            UserTimezone.createInstance();
        }

        return UserTimezone._instance;
    }
});

$(function()
{
    UserTimezone.createInstance();
});
