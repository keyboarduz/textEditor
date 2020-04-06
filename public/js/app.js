document.addEventListener('DOMContentLoaded', function (e) {
    tineMCE = tinymce.init({
        // selector: '#mytextarea',
        mode: "exact",
        elements: "elm1",
        height: 500,
        language: 'ru',
        plugins: "image",
        images_upload_url: '/image/upload',
        image_dimensions: false,
        image_class_list: [
            {title: 'Responsive', value: 'img-fluid'}
        ],
        a11y_advanced_options: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', '/image/upload');
            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.data.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.data.location);
            };
            formData = new FormData();
            var fileName;
            if( typeof(blobInfo.blob().name) !== undefined )
                fileName = blobInfo.blob().name;
            else
                fileName = blobInfo.filename();
            formData.append('imageFile', blobInfo.blob(), fileName);
            xhr.send(formData);
        }
    });
});



