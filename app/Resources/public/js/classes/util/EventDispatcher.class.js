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