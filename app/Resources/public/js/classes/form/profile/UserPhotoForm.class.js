var UserPhotoForm = Base.extend({

    _fields: null,

    _deleteButton: null,
    _uploadButtonText: null,

    _isUploading: null,

    constructor: function()
    {
        this._isUploading = false;

        var uploadFileButton = $("#submit");
        uploadFileButton.click(handler(this, "_onUploadFileButtonClick"));
        uploadFileButton.change(function()
        {
            $("#editUserPhotoForm").submit();
        });

        this._uploadButtonText = $("#editUserPhotoButton .button-label");

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
        if (this._isUploading)
        {
            return false;
        }
        this._isUploading = true;

        return true;
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

        $("#uploadPhotoProgress").addClass("hidden");
        this._uploadButtonText.html("Добавить фото");
        this._updatePhotos($("#defaultPhotoUrl").val());
        this._deleteButton.addClass("hidden");
    },

    _onBeforeSubmit: function()
    {
        $.magnificPopup.close();
        $("#uploadPhotoProgress").removeClass("hidden");
    },

    _onSuccess: function(response)
    {
        this._isUploading = false;

        $("#uploadPhotoProgress").addClass("hidden");
        if (response.success)
        {
            this._uploadButtonText.html("Изменить фото");
            this._updatePhotos(response.photoUrl);
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

    _updatePhotos: function(url)
    {
        $("#userAvatar").attr("src", url);
        $("#userSmallAvatar").attr("src", url);
    }
});