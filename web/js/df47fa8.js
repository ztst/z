var EventDispatcher = Base.extend({
    _listeners: null,
    
    constructor: function()
    {
        this._listeners = new Object(); 
    },
    
    /**
     * Adds listener to object's event.
     */
    addListener: function(eventName, listener, method)
    {
        if (!this._listeners)
        {
            this._listeners = new Object();
        }
        
        if (!this._listeners[eventName])
        {
            this._listeners[eventName] = new Array();
        }
        
        this._listeners[eventName].push({listener: listener, method: method});
    },
    
    /**
     * Removes listener from object's event.
     */
    removeListener: function(eventName, listener)
    {
        if (!this._listeners[eventName])
        {
            return;
        }
        
        var countListeners = this._listeners[eventName].length;
        for (var i = 0; i < countListeners; ++i)
        {
            if (this._listeners[eventName][i].listener == listener)
            {
                this._listeners[eventName].splice(i, 1);        
                break;
            }
        }
    },
    
    /**
     * Checkes is object already has specified listener to event.
     * 
     * @return boolean
     */
    hasListener: function(eventName, listener)
    {
        if (!this._listeners[eventName])
        {
            return false;
        }
        
        var countListeners = this._listeners[eventName].length;
        for (var i = 0; i < countListeners; ++i)
        {
            if (this._listeners[eventName][i].listener == listener)
            {
                return true;
            }
        }
        return false;
    },
    
    /**
     * Dispatches event.
     */
    dispatchEvent: function(eventName)
    {
        if (!this._listeners || !this._listeners[eventName])
        {
            return;
        }
    
        var eventArguments = Array.prototype.slice.call(arguments);
        //remove eventName from arguments list
        eventArguments.splice(0, 1);
        
        var countListeners = this._listeners[eventName].length;
        for (var i = 0; i < countListeners; ++i)
        {
            var listener = this._listeners[eventName][i].listener;
            var method   = this._listeners[eventName][i].method;

            //workaround bug with calling handler from other window
            //NOTE: this workaround woudn't work if you want to pass additional parameters to handler
            if (eventArguments.length == 0)
            {
                method.apply(listener);
            }
            else
            {
                method.apply(listener, eventArguments);
            }
        }
    }
});
var SidebarMenu = EventDispatcher.extend({

    _classesButtons: null,
    _subjectsButtons: null,

    _gradesWithSubjects: null,

    constructor: function()
    {
        this.base();

        var that = this;
        this._classesButtons = $(".class-menu li");
        this._classesButtons.click(function(){
            that._onClassChanged($(this));
        });

        this._subjectsButtons = $(".subject-menu li");
        this._subjectsButtons.click(function(){
            that._onSubjectClick($(this));
        });
    },

    setGradesWithSubjects: function(gradesWithSubjects)
    {
        this._gradesWithSubjects = JSON.parse(gradesWithSubjects);
    },

    getClass: function()
    {
        return this._classesButtons.filter(".selected").prop("id");
    },

    updateSubjects: function()
    {
        this._subjectsButtons.addClass("hidden");

        var grade = this.getClass();
        var subjects = this._gradesWithSubjects[grade];

        for (var i in subjects)
        {
            this._subjectsButtons.filter("[id=" + subjects[i] + "]").removeClass("hidden");
        }
    },

    _onSubjectClick: function(item)
    {
        var grade = this.getClass();
        var subject = item.attr("id");
        var url = Routing.generate('show_catalogue', {'class': grade, 'subjectName': subject});

        window.location.href = url;
    },

    _onClassChanged: function(item)
    {
        this._classesButtons.removeClass("selected");
        item.addClass("selected");

        this.updateSubjects();
    }
});

$(function()
{
    var sidebarMenu = new SidebarMenu();

    sidebarMenu.setGradesWithSubjects($('#gradesSubjects').val());
});

var MainMenu = EventDispatcher.extend({

    _loginPopupLink: null,
    _loginPopup: null,

    _showBadgesPopupLink: null,
    _badgesPopup: null,

    _userHasViewedBadgesUrl: null,

    constructor: function()
    {
        this.base();

        this._initBadgesPopup();
        this._initLoginPopup();
    },

    setUserHasViewedBadgesUrl: function(url)
    {
        this._userHasViewedBadgesUrl = url;
    },

    _initLoginPopup: function()
    {
        this._loginPopupLink = $(".login_button");
        this._loginPopupLink.magnificPopup({
            type: "ajax",
            callbacks: { ajaxContentAdded: handler(this, "_onLoginFormLoaded") }
        });
    },

    _onLoginFormLoaded: function()
    {
        var switchLoginLink = $("#switchLoginLink");
        switchLoginLink.click(handler(this, "_showLoginForm"));

        var switchRegistrationLink = $("#switchRegistrationLink");
        switchRegistrationLink.click(handler(this, "_showRegistrationForm"));

        var switchForgetPasswordLink = $("#switchForgetPasswordLink");
        switchForgetPasswordLink.click(handler(this, "_showForgetPasswordForm"));

        this._initRegistrationForm();
        this._initLoginForm();
        this._initForgetPasswordForm();
    },

    _initLoginForm: function()
    {
        var loginForm = $("#loginForm");
        loginForm.submit(handler(this, "_onLoginFormSubmitted"));
        $("#switchForgetPasswordLink").click(function(){
                return false;
            }
        )
    },

    _initRegistrationForm: function()
    {
        var registrationForm = $(".registration-form");
        registrationForm.submit(handler(this, "_onRegistrationFormSubmitted"));

        this._initShowPasswordLink();
    },

    _initForgetPasswordForm: function()
    {
        var forgetPasswordForm = $(".forget-password-form");
        forgetPasswordForm.submit(handler(this, "_onForgetPasswordFormSubmitted"));
    },

    _showLoginForm: function()
    {
        $("#registrationFormContainer").addClass("hidden");
        $("#forgetPasswordFormContainer").addClass("hidden");

        $("#loginFormContainer").removeClass("hidden");
    },

    _showRegistrationForm: function()
    {
        $("#loginFormContainer").addClass("hidden");
        $("#forgetPasswordFormContainer").addClass("hidden");

        $("#registrationFormContainer").removeClass("hidden");
    },

    _showForgetPasswordForm: function()
    {
        $("#loginFormContainer").addClass("hidden");
        $("#registrationFormContainer").addClass("hidden");

        $("#forgetPasswordFormContainer").removeClass("hidden");
    },

    _onLoginFormSubmitted: function()
    {
        var form = $("#loginForm");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendLoginSuccess")
        });

        return false;
    },

    _onSendLoginSuccess: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert("Неверный логин/пароль");
        }
    },

    _onRegistrationFormSubmitted: function()
    {
        var form = $(".registration-form");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendRegistrationComplete")
        });

        return false;
    },

    _onSendRegistrationComplete: function(response)
    {
        if (response.success)
        {
            $("#registrationFormContainer").html(response.html);
        }
        else
        {
            $(".registration-form").remove();
            $("#registrationFormContainer .login-popup-header").after(response.html);

            this._initRegistrationForm();
        }
    },

    _onForgetPasswordFormSubmitted: function()
    {
        var form = $("#forgetPasswordForm");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendPasswordSuccess")
        });

        return false;
    },

    _onSendPasswordSuccess: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert('Неверный e-mail');
        }
    },

    _initBadgesPopup: function()
    {
        this._showBadgesPopupLink = $(".new-user-badge-link");
        this._showBadgesPopupLink.magnificPopup({
            type: "inline",
            midClick: true,
            callbacks: { open: handler(this, "_onShowUserBadgesLinkClick")}
        });
    },

    _onShowUserBadgesLinkClick: function()
    {
        this._showBadgesPopupLink.remove();

        $.post(this._userHasViewedBadgesUrl, null, handler(this, "_onSaveViewingBadgesComplete"), "json");
    },

    _initShowPasswordLink: function()
    {
        var showPasswordLink = $(".show-password-icon");
        if (navigator.userAgent.search("MSIE") >= 0)
        {
            $(".show-password-icon").remove();
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

    _onSaveViewingBadgesComplete: function(response)
    {
    }
});

$(function()
{
    var mainMenu = new MainMenu();

    mainMenu.setUserHasViewedBadgesUrl(Routing.generate("user_has_viewed_badges"))
});
var CompleteSocialRegistrationPopup = Base.extend({

    _createNewUserButton: null,
    _attachUserButton: null,

    constructor: function()
    {
        this.base();

        this._openPopup();

        this._createNewUserButton = $("#createNewUser");
        this._attachUserButton = $("#attachUser");

        this._createNewUserButton.click(handler(this, "_onCreateNewUserButtonClick"))
        this._attachUserButton.click(handler(this, "_onAttachUserButtonClick"))

        var loginForm = $("#loginForm");
        loginForm.submit(handler(this, "_onLoginFormSubmitted"));

        this._initRegistrationForm();
    },

    _initRegistrationForm: function()
    {
        var registrationForm = $("#registrationForm");
        registrationForm.submit(handler(this, "_onRegistrationFormSubmitted"));
    },

    _onRegistrationFormSubmitted: function()
    {
        var form = $("#registrationForm");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendRegistrationComplete")
        });

        return false;
    },

    _onSendRegistrationComplete: function(response)
    {
        if (response.success)
        {
            $("#registrationFormContainer").html(response.html);
        }
        else
        {
            $(".registration-form").remove();
            $("#registrationFormContainer .form-header").after(response.html);

            this._initRegistrationForm();
        }
    },

    _onCreateNewUserButtonClick: function()
    {
        $(".complete-registration-popup-content").addClass("hidden");
        $("#registrationFormContainer").removeClass("hidden");
    },

    _onAttachUserButtonClick: function()
    {
        $(".complete-registration-popup-content").addClass("hidden");
        $("#attachUserForm").removeClass("hidden");
    },

    _openPopup: function()
    {
        $.magnificPopup.open({
            items: {
                src: '#completeSocialRegistrationPopup',
                type: 'inline'
            }
        }, 0);
    },

    _onLoginFormSubmitted: function()
    {
        var form = $("#loginForm");
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: handler(this, "_onSendLoginSuccess")
        });

        return false;
    },

    _onSendLoginSuccess: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert("Неверный логин/пароль");
        }
    }
});

$(function(){
    if ($("#completeSocialRegistrationPopup").length)
    {
        var popup = new CompleteSocialRegistrationPopup();
    }
});