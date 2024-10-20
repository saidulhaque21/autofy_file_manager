 
<div id="afm_model_vue" >
    <h2>Example</h2>
    <script>
        var base_url = "http://localhost/autofy_file_manager/";
        var root_directory = "uploads/";
//        alert(base_url); 
    </script>
    <button @click="vueafm">Upload Image</button>
    <p>
        <img width="200" v-for="selectedItem in selectedItems" :src="selectedItem.file_path" />
    </p>

    <h2 id="with_vuejs_result">Result: After Select</h2>
    <pre>
    {{selectedItems}}
    </pre>
</div>
<h2 id="with_vuejs_code">Code</h2>
<h3>JS</h3>
<pre><?php show_source("js/with_vuejs.js"); ?>
</pre>

<script src="js/with_vuejs.js?v=202411"></script>