var ChapterList = Base.extend({

    _items: null,
    _baseItems: null,

    _currentItem: null,

    _addButton: null,
    _editButton: null,
    _upButton: null,
    _downButton: null,
    _saveButton: null,
    _isEditing: null,

    constructor: function()
    {
        this.base();

        this._initItems();

        this._addButton = $(".add-chapter-button");
        this._addButton.click(handler(this, "_onAddButtonClick"));

        this._editButton = $(".edit-chapter-button");
        this._editButton.click(handler(this, "_onEditButtonClick"));

        this._upButton = $(".chapter-up");
        this._upButton.click(handler(this, "_onUpButtonClick"));

        this._downButton = $(".chapter-down");
        this._downButton.click(handler(this, "_onDownButtonClick"));

        this._saveButton = $(".save-changes-button");
        this._saveButton.click(handler(this, "_onSaveButtonClick"));
        this._isEditing = false;
    },

    _onSaveButtonClick: function()
    {
        if (this._isEditing || this._saveButton.hasClass("disabled"))
        {
            return false;
        }
        this._saveButton.addClass("disabled");
        var data = {"items": this._prepareSaveData()};

        var url = $("#editChaptersUrl").val();
        $.post(url, data, handler(this, '_onChaptersSaved'), 'json');

        return true;
    },

    _prepareSaveData: function()
    {
        var data = {};
        for (var i in this._items)
        {
            data[this._items[i].getOrder()] = this._items[i].toArray();
        }
        return data;
    },

    _onChaptersSaved: function(response)
    {
        if (response.success)
        {
            location.reload();
        }
        else
        {
            alert("При сохранении возникли ошибки. Попробуйте перезагрузить страницу.");
        }
    },

    _updateSaveButton: function()
    {
        this._saveButton.toggleClass("disabled", !this._hasChanges());
    },

    _hasChanges: function()
    {
        for (var i in this._items)
        {
            if (this._baseItems[i] == undefined
                || this._baseItems[i].getOrder() != this._items[i].getOrder()
                || this._baseItems[i].getTitle() != this._items[i].getTitle())
            {
                return true;
            }
        }
        return false;
    },

    _updateUpDownButtons: function()
    {
        if (this._currentItem.getPrevContainer().length == 0)
        {
            this._upButton.addClass("disabled");
        }
        else
        {
            this._upButton.removeClass("disabled");
        }
        if (this._currentItem.getNextContainer().length == 0)
        {
            this._downButton.addClass("disabled");
        }
        else
        {
            this._downButton.removeClass("disabled");
        }
    },

    _onUpButtonClick: function()
    {
        var currentContainer = this._currentItem.getContainer();
        var prevContainer = this._currentItem.getPrevContainer();
        if (prevContainer.length > 0)
        {
            prevContainer.before(currentContainer);
            var prevId = prevContainer.attr("id").replace("chapter_container_", "");
            var prevItem = this._items[prevId];
            this._currentItem.switchOrder(prevItem);
        }
        this._updateUpDownButtons();
        this._updateSaveButton();
    },

    _onDownButtonClick: function()
    {
        var currentContainer = this._currentItem.getContainer();
        var nextContainer = this._currentItem.getNextContainer();
        if (nextContainer.length > 0)
        {
            nextContainer.after(currentContainer);
            var nextId = nextContainer.attr("id").replace("chapter_container_", "");
            var nextItem = this._items[nextId];
            this._currentItem.switchOrder(nextItem);
        }
        this._updateUpDownButtons();
        this._updateSaveButton();
    },

    _initItems: function()
    {
        this._items = {};
        this._baseItems = {};
        var that = this;

        var isFirst = true;
        $(".chapters-list .chapter-description-container").not(".hidden").each(function()
        {
            var id = $(this).attr("id").replace("chapter_container_", "");

            var item = new ChapterListItem(id);
            if (isFirst)
            {
                that._currentItem = item;
            }
            that._addItem(item);
            that._baseItems[id] = jQuery.extend({}, item);
            isFirst = false;
        });
    },

    _onAddButtonClick: function()
    {
        if (this._isEditing)
        {
            return;
        }

        this._isEditing = true;
        var item = ChapterListItem.addNewItemList();
        this._addItem(item);
        item.startEdit();
        this._updateUpDownButtons();
    },

    _onEditButtonClick: function()
    {
        if (this._isEditing)
        {
            return;
        }

        this._isEditing = true;
        this._currentItem.startEdit();
    },

    _addItem: function(item)
    {
        item.addListener(ChapterListItem.event.EDIT_ENDING, this, this._onItemEdited);
        item.addListener(ChapterListItem.event.ITEM_CLICKED, this, this._onItemClicked);
        item.addListener(ChapterListItem.event.NEED_DELETE, this, this._onItemDelete);
        this._items[item.getId()] = item;
    },

    _onItemEdited: function(item)
    {
        if (!this._checkTitle(item.getTitle()))
        {
            item.startEdit();
            return;
        }

        this._isEditing = false;
        this._updateSaveButton();
    },

    _onItemDelete: function(item)
    {
        this._isEditing = false;

        delete this._items[item.getId()];
        item.remove();
    },

    _checkTitle: function(title)
    {
        var count = 0;
        for (var i in this._items)
        {
            if (this._items[i].getTitle() == title)
            {
                count++;
            }
            if (count > 1)
            {
                return false;
            }
        }
        return true;
    },

    _onItemClicked: function(item)
    {
        this._currentItem = item;
        for (var i in this._items)
        {
            if (i == item.getId())
            {
                continue;
            }
            var currentItem = this._items[i];
            currentItem.deselectItem();
            currentItem.hideVideoList();
        }
        this._updateUpDownButtons();
    }
});
