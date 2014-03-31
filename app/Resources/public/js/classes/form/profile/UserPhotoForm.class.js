var UserPhotoForm = Base.extend({

    _fields: null,

    _deleteButton: null,
    _uploadButton: null,
    _uploadButtonText: null,

    _uploadFileButton: null,

    _isUploading: null,

    constructor: function()
    {
        this._isUploading = false;

        this._uploadFileButton = $("#submit");
        this._uploadFileButton.click(handler(this, "_onUploadFileButtonClick"));
        this._uploadFileButton.change(function()
        {
            $("#editUserPhotoForm").submit();
        });

        this._uploadButton = $("#editUserPhotoButton");
        this._uploadButtonText = this._uploadButton.find(".button-label");

        $("#editUserPhotoForm").ajaxForm({
            beforeSubmit: handler(this, "_onBeforeSubmit"),
            success: handler(this, "_onSuccess"),
            dataType: 'json'
        });

        this._deleteButton = $("#deleteUserPhotoButton");
        this._deleteButton.click(handler(this, "_onUserPhotoDeleteClick"))
    },

    _onUploadFileButtonClick: function()
    {
        return !this._isUploading;
    },

    _onUserPhotoDeleteClick: function()
    {
        if (this._isUploading)
        {
            return;
        }
        this._isUploading = true;

        $("#uploadPhotoProgress").removeClass("hidden");

        var url = Routing.generate('delete_user_photo', {'userId': $("#userId").val()});

        $.post(url, null, handler(this, '_onPhotoDeleted'), 'json');
    },

    _onPhotoDeleted: function()
    {
        this._isUploading = false;
        this._uploadFileButton.val("");

        $("#uploadPhotoProgress").addClass("hidden");
        this._uploadButtonText.html("Добавить фото");
        this._uploadButtonText.attr("class", "semi-active-button-label");
        this._uploadButton.attr("class", "semi-active-button add-photo-button");
        this._updateBigPhoto($("#defaultPhotoUrl").val());
        this._updateSmallPhoto($("#defaultSmallPhotoUrl").val());
        this._deleteButton.addClass("hidden");
    },

    _onBeforeSubmit: function()
    {
        $.magnificPopup.close();
        $("#uploadPhotoProgress").removeClass("hidden");
        this._isUploading = true;
    },

    _onSuccess: function(response)
    {
        this._isUploading = false;

        $("#uploadPhotoProgress").addClass("hidden");
        if (response.success)
        {
            this._uploadButtonText.html("Изменить фото");
            this._uploadButtonText.attr("class", "non-active-button-label");
            this._uploadButton.attr("class", "non-active-button edit-photo-button");
            this._updateBigPhoto(response.photoUrl);
            this._updateSmallPhoto(response.photoUrl);
            this._deleteButton.removeClass("hidden");
        }
        else
        {
            $.magnificPopup.open({
                items: {
                    src: '#uploadErrorPopup',
                    type: 'inline'
                }
            }, 0);
        }
    },

    _updateBigPhoto: function(url)
    {
        $("#userAvatar").attr("src", url);
    },

    _updateSmallPhoto: function(url)
    {
        $("#userSmallAvatar").attr("src", url);
    }
});