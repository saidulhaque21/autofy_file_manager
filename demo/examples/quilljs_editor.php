<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Create the editor container -->

    <script>
        var base_url = "http://localhost/smart_file_manager/";
        var root_directory = "uploads/";
    </script>
<div id="toolbar"> 
    <!--<button id="custom-button">&#8486;</button></div>-->
    <div id="editor">...</div>

    <!-- Include the Quill library -->

</div>
<!-- Initialize Quill editor -->
<script>

   

    var options = {
//            debug: 'info',
        modules: {
            toolbar: {
                container: [
                    [{'header': [1, 2, 3, 4, false]}],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    ['link', 'image', 'video'],
                    ['clean']
                ], // Selector for toolbar container
                handlers: {
                    image: customImageHandler

                }
            }
        },
        placeholder: 'Compose an epic...',
//            readOnly: true,
        theme: 'snow'
    };
    var quill = new Quill('#editor', options);
//        var customButton = document.querySelector('.ql-sfm');
//        customButton.addEventListener('click', function () {
//            var options = {
//                base_url: "http://localhost/smart_file_manager/",
//                title: "File Manager ",
//                file_type: "image", // image | document (doc, pdf) | video | *
//                afterFileSelect: afterFileSelect
//
//            };
//            smartFileManager(options);
//            
//        });

    function customImageHandler() {
        var options = {
            base_url: "http://localhost/smart_file_manager/",
            title: "File Manager ",
            file_type: "image", // image | document (doc, pdf) | video | *
            afterFileSelect: afterFileSelect

        };
        smartFileManager(options);
    }
    function afterFileSelect(params) {
        console.log(params);
        const range = quill.getSelection(true);

        if (params.icon === "fa-picture-o") {
            quill.insertEmbed(range.index, 'image', params.file_path);
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

            quill.clipboard.dangerouslyPasteHTML(5, text);
        }



    }
</script>
<style>

</style> 