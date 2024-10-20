<script>
    var base_url = "http://localhost/autofy_file_manager/";
    var root_directory = "uploads/";
</script>
<script src="js/tinymce.min.js"></script>
<!--<script src="js/tinymce.config.js"></script>-->


<textarea class="tinymce_editor">Hello, World!</textarea>

<script>

    var base_url = "http://localhost/autofy_file_manager/";
    var images_upload_base_path = base_url + "uploads/";

    const t = {
        updateValue: function (content) {
           // console.log('Updated content:', content);  // Handle content updates
        },
        objTinymce: null  // This will store the TinyMCE instance
    };
    var e = tinymce.init({
        selector: '.tinymce_editor',
        height: 500,
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help ',
        toolbar: 'afm | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tiny.cloud/css/codepen.min.css'
        ],
        link_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_class_list: [
            {title: 'None', value: ''},
            {title: 'Some class', value: 'class-name'}
        ],
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', {alt: 'My alt text'});
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', {source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg'});
            }
        },
        templates: [
            {title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'},
            {title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...'},
            {title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'}
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_drawer: 'sliding',
        contextmenu: "link image imagetools table",
        images_upload_base_path: images_upload_base_path,
        images_upload_url: base_url + 'file_manager/files/upload_tinymce_image',
        automatic_uploads: true,
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            // Provide file and text for the link dialog
            if (meta.filetype == 'file') {
                callback('mypage.html', {text: 'My text'});
            }

            // Provide image and alt text for the image dialog
            if (meta.filetype == 'image') {
                callback('myimage.jpg', {alt: 'My alt text'});
            }

            // Provide alternative source and posted for the media dialog
            if (meta.filetype == 'media') {
                callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
            }
        },
        setup: function (editor) {
            /* Basic button that just inserts the date */
            editor.ui.registry.addButton('afm', {
                text: 'Upload',
                tooltip: 'Upload image or file',
                onAction: function (_) {
                    //editor.insertContent("Fuck");
                    customImageHandler(editor);
                }
            });




        },
        init_instance_callback: function (e) {
            e.on("Change KeyUp Undo Redo", function (n) {
                t.updateValue(e.getContent())
            }
            ),
                    t.objTinymce = e;
        }



    });
    function customImageHandler(editor) {
        var options = {
            base_url: "http://localhost/autofy_file_manager/",
            title: "File Manager ",
            file_type: "image", // image | document (doc, pdf) | video | *
            afterFileSelect: function (params) {
                afterSmartFileSelect(params, editor);
                //   editor.insertContent("Fuck");
            }
        };
        smartFileManager(options);
    }
    function afterSmartFileSelect(params, editor) {
       // console.log(params);
        if (params.icon === "fa-picture-o") {
            editor.insertContent("<img src='" + params.file_path + "'/>");
        } else {

            if (params.extension === "pdf") {
                var text = '<div style="width:100%">' +
                        '<h2>Pdf viewer testing</h2>' +
                        '<p>If problem in Pdf viewer- <a target="_blank" href="' + params.file_path + '">Download</a></p>' +
                        '<iframe src="https://docs.google.com/viewer?url=' + params.file_path + '&embedded=true" frameborder="0" height="500px" width="100%"></iframe>' +
                        '</div>';
            } else {
                var text = '<div style="width:100%">' +
                        '<h3>File: ' + params.filename + '- <a target="_blank" href="' + params.file_path + '">Download </a></h3>' +
                        '</div>';
            }
            editor.insertContent(text);

        }



    }



</script> 
