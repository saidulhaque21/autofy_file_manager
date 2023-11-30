 
var sfm_model_vue = new Vue({
    el: '#sfm_model_vue',
    data: {
// messge and show hide 
        selectedItems: [],
        processMessage: " Loaded",

    },
    methods: {

        showModal: function () {
//            this.selectedItems =  []; 
            this.processMessage = "Opening Modal";
            var options = {
                base_url:base_url,
                title: "File Manager ",
                file_type: "image", // image | document (doc, pdf) | video | *
               afterFileSelect:this.afterFileSelect

            };
           smartFileManager(options);
            this.processMessage = "Opened Modal";
        },
        afterFileSelect: function (paramas) {
//             console.log(paramas);
            if (typeof paramas.isArray === 'undefined') {
                //console.log("String" + paramas);
                this.selectedItems.push(paramas);
            } else {
//                console.log("Array" + paramas);
                for (let key in paramas) {
                    let item = paramas[key];
                    this.selectedItems.push(item.file_path);
                }

            }
        }

    }
})

 