<div class="form-group">
    <div class="row"><span style="color:red">*</span> Select Product Thumble Image
    <input required type="file" id="photo_addthumble" class="form-control" name="thumble">
    <div class="pthumb"></div>
    </div>
</div>
<div class="form-group">
    <div class="row"><span style="color:red">*</span> Select Product All Image
    <input required type="file" id="photo_add" class="form-control" name="images[]" placeholder="Select Multiple Photo" multiple>
    <div class="gallery"></div>
    </div>
</div>
<script>
    $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img width="80" height="50">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };
    var imagesPreviewTh = function(input, placeToInsertImagePreview) {
            var reader = new FileReader();

            reader.onload = function(event) {
                $($.parseHTML('<img width="80" height="50">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            }

            reader.readAsDataURL(input.files[0]);

    };
    

    $('#photo_add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
    $('#photo_addthumble').on('change', function() {
        imagesPreviewTh(this, 'div.pthumb');
    });
});
</script>