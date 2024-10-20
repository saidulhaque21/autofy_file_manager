<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="afm/src/vue/vue.min.js"></script>
        <script src="afm/src/js/mshmodel.js?v=20102024111"></script>
 
    </head>
    <body>
        <div id="afm_model_vue" style="margin: 50px;">
           <button @click="showModal">Upload Image</button>
            <p> <img width="200" v-for="item in selectedItems" :src="item.file_path" />  </p>  
            
            <a href="demo/index.php">Demo Example </a>
        </div>
        <script>
            var base_url = "http://localhost/autofy_file_manager/";
            var root_directory = "uploads/"; 
            </script>
        <script src="afm/src/js/afm_model_vue.js"></script>
          
    </body>
</html>
