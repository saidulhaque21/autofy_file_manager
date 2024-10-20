<h1>Autofy File Manager - Vuejs Example</h1>
<script src="http://localhost:8255/autofy_file_manager/afm/src/vue/vue.min.js"></script>
<script src="http://localhost:8255/autofy_file_manager/afm/src/js/mshmodel.js?v=20102024111"></script>
<script>
        var base_url = "http://localhost:8255/autofy_file_manager/";
        var root_directory = "uploads/";
//        alert(base_url); 
    </script>

 
<div id="afm_model_vue" >

    <button @click="vueafm">Upload Image</button>
    <p>
        <img width="200" v-for="selectedItem in selectedItems" :src="selectedItem.file_path" />
    </p>

    <h2 id="with_vuejs_result">Result: After Select</h2>
    <pre>
    {{selectedItems}}
    </pre>
</div>
  

<script type="text/javascript" >

var afm_model_vue = new Vue({
    el: "#afm_model_vue",
    data: {
        selectedItems: [],
    },
    methods: {
       vueafm: function () {
            this.processMessage = "Opening Modal";
            var options = {
                base_url:"http://localhost:8255/autofy_file_manager/",
                title: "File Manager ",
                file_type: "image", // image | document (doc, pdf) | video | *
                afterFileSelect:this.afterFileSelect

            };
             
           smartFileManager(options);
            this.processMessage = "Opened Modal";
        },
        afterFileSelect: function (paramas) {
            if (typeof paramas.isArray === 'undefined') {
                this.selectedItems.push(paramas);
            } else {
                for (let key in paramas) {
                    let file = paramas[key];
                    this.selectedItems.push(file);
                }

            }
        }

    }
})
    
</script>