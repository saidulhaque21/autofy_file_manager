var sfm_model_vue = new Vue({
    el: "#sfm_model_vue",
    data: {
        selectedItems: [],
    },
    methods: {
       vueSFM: function () {
            this.processMessage = "Opening Modal";
            var options = {
                base_url:"http://localhost/smart_file_manager/",
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
    