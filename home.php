
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Jekyll v3.8.6">
        <title>Smart File Manager - Developed by Saidul Haque</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="src/bootstrap/css/bootstrap.min.css" >
        <link rel="stylesheet" href="src/css/afm_modal.css" >
        <link rel="stylesheet" href="src/css/dropzone.min.css" >
        <meta name="theme-color" content="#563d7c">
        <link rel="stylesheet" href="src/css/afm.css"   >
 
    </head>
    <body>

        <?php
        include_once 'src/main.php';
        ?>
 
        <script src="src/bootstrap/js/jquery-3.2.1.slim.min.js" ></script>
        <script src="src/bootstrap/js/popper.min.js"></script>
        <script src="src/bootstrap/js/bootstrap.min.js"></script>

        <script src="src/vue/vue.min.js"></script>
        <script src="src/vue/axios.min.js"></script>
        <script src="src/vue/component/dropzone/vue2Dropzone.js"></script>
        <script src="src/js/fontawesome.js"  ></script>
         <script>

            var root_directory = "uploads"; 
            var upload_directory = "<?php echo date("Y/m/d"); ?>";
            var debug = true;
            var base_url = "http://localhost/autofy_file_manager/";
        </script> 
        <script src="src/js/afm_fm.js"></script>
 
    </body>
</html>
