var ChapterListItem = EventDispatcher.extend({

    _container: null,

    _id: null,
    _title: null,
    _titleLink: null,
    _titleInput: null,
    _order: null,
    _isNew: null,

    constructor: function(id)
    {
        this.base();

        this._id = id;
        this._container = $("#chapter_container_" + id);
        this._container.click(handler(this, "_onItemClick"));

        this._titleLink = $("#show_chapter_" + id);
        this._order = $("#chapter_order_" + id).val();
        this._initTitleInput();
        this._title = this._titleInput.val();
        this._isNew = false;
    },

    getPrevContainer: function()
    {
        return this._container.prev(".chapter-description-container:visible");
    },


    getNextContainer: function()
    {
        return this._container.next(".chapter-description-container:visible");
    },

    switchOrder: function(item)
    {
        if (item)
        {
            var currentOrder = this._order;
            this.setOrder(item.getOrder());
            item.setOrder(currentOrder);
        }
    },

    getContainer: function()
    {
        return this._container;
    },

    getOrder: function()
    {
        return this._order;
    },

    setOrder: function(order)
    {
        this._order = order;
        $("#chapter_order_" + this._id).val(order);
    },

    setIsNew: function(isNew)
    {
        this._isNew = isNew;
    },

    startEdit: function()
    {
        this._titleLink.addClass("hidden");
        this._titleInput.attr("type", "text");
        this._titleInput.focus();
    },

    getId: function()
    {
        return this._id;
    },

    getTitle: function()
    {
        return this._title;
    },

    selectItem: function()
    {
        this._container.addClass("selected");
        $(".chapter-videos-count").html($("#chapter_videos_" + this._id + " li").length);
    },

    deselectItem: function()
    {
        this._container.removeClass("selected");
    },

    showVideoList: function()
    {
        $("#chapter_videos_" + this._id).removeClass("hidden");
        new VideosList();
    },

    hideVideoList: function()
    {
        $("#chapter_videos_" + this._id).addClass("hidden");
    },

    remove: function()
    {
        this._container.remove();
    },

    toArray: function()
    {
        var array = {};
        array['id'] = this._id;
        array['title'] = this._title;
        array['order'] = this._order;

        return array;
    },

    _initTitleInput: function()
    {
        this._titleInput = $("#chapter_title_input_" + this._id);
        this._titleInput.keypress(function(e) {
            if(e.which == 13) //enter button
            {
                $(this).focusout();
            }
        });
        this._titleInput.focusout(handler(this, "_onFocusOut"));
    },

    _onItemClick: function()
    {
        this.showVideoList();
        this.selectItem();

        this.dispatchEvent(ChapterListItem.event.ITEM_CLICKED, this);
    },

    _onFocusOut: function()
    {
        var title = this._titleInput.val();
        if (!title)
        {
            if (this._isNew)
            {
                this.dispatchEvent(ChapterListItem.event.NEED_DELETE, this);
            }
            return false;
        }

        this._titleLink.removeClass("hidden");
        this._titleLink.html(title);
        this._titleLink.attr("title", title);
        this._title = title;
        this._titleInput.attr("type", "hidden");
        this.dispatchEvent(ChapterListItem.event.EDIT_ENDING, this);

        return true;
    }
},{
    event:
    {
        EDIT_ENDING: "edit_ending",
        ITEM_CLICKED: "item_clicked",
        NEED_DELETE: "need_delete"
    },

    newItemsCount: 0,

    addNewItemList: function()
    {
        var elementPattern = $("#chaptersListRowPattern");

        var order = $(".chapters-list .chapter-description-container").not(".hidden")
            .last().find(".chapter-order-input").val();
        order = order ? order : 1;

        var newElement = elementPattern.clone();
        var newId = "new_" + ChapterListItem.newItemsCount++;
        newElement.attr("id", "chapter_container_" + newId);
        newElement.find(".chapter-order-input").attr("id", "chapter_order_" + newId).val(+order + 1);
        newElement.find(".chapter-title-link").attr("id", "show_chapter_" + newId);
        newElement.find(".chapter-title-input").attr("id", "chapter_title_input_" + newId);
        newElement.removeClass("hidden");
        $(".chapters-list").append(newElement);

        var newItem = new ChapterListItem(newId);
        newItem.setIsNew(true);
        return newItem;
    }
});
