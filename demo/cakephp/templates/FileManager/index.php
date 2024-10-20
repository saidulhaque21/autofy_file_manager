<h1>Autofy File Manager - Javascript Example</h1>
     
  
        <script src="http://localhost:8255/autofy_file_manager/afm/src/vue/vue.min.js"></script>
        <script src="http://localhost:8255/autofy_file_manager/afm/src/js/mshmodel.js?v=20102024111"></script>
 
        <div id="afm_model_vue" style="margin: 50px;">
           <button @click="showModal">Upload Image</button>
            <p> <img width="200" v-for="item in selectedItems" :src="item.file_path" />  </p>  
             <a href="http://localhost:8255/file-manager/vuejs">Vuejs  Example </a> 
        </div>
        <script>
            var base_url = "http://localhost:8255/autofy_file_manager/";
            var root_directory = "uploads/"; 
            </script>
        <script src="http://localhost:8255/autofy_file_manager/afm/src/js/afm_model_vue.js"></script>
     