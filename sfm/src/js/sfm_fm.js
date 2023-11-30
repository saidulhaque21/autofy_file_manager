

var given_base_url = typeof base_url !== 'undefined' ? base_url : "";
var sfm_upload_directory = typeof upload_directory !== 'undefined' ? upload_directory : "";
var sfm_root_directory = typeof root_directory !== 'undefined' ? root_directory : "uploads/";
var sfm_debug = typeof debug !== 'undefined' ? debug : false;
var select_action = typeof select_action !== 'undefined' ? select_action : "";

var type_list = {"image": "Image", "document": "Document", "video": "Video"};
var MAX_UPLOAD_SIZE = "2048";


//var directory_path="";
var api_url = given_base_url + "sfm/src/server/php/actions/";


Vue.use(vue2Dropzone);
var sfm_app = new Vue({
    el: '#sfm_app',
    data: {
        // path 
        //  sr: server_root, //server root for uploading file  | fixed 
        sfm_base_url: given_base_url, // sfm_base_url for browsing file  | fixed 
        up_dir: sfm_root_directory, // initial upload directory | fixed 
        browsing_dir: sfm_upload_directory, // upload directory is tags which will add with  server_root or sfm_base_url and changeable 

        sfm_api_url: api_url, // file access API URL  | fixed 

// messge and show hide 
        errorMessage: "",
        successMessage: "",
        sfm_debug: sfm_debug,
        is_new_folder: false,
        is_rename_folder: false,
        is_preview: false,
        view_type: "Grid", //List,    Grid 
        select_action: select_action, //direct,   "" 

        current_action: "library",
        // paging and Reguest Query 
        paging: {limit: 10, page: 1, pages: 0, currentPageItem: 0},
        headers: {
            'Content-Type': 'multipart/form-data',
            'Content-type': 'application/json; charset=utf-8',
            'Accept': 'application/json; charset=utf-8',
            "Access-Control-Allow-Origin": "*",
        },
        query_string: {},
        // list initial 
        type_list: type_list,
        items: [],
        item: {},
        current_folder: {},
        folder: {},
        // other 
        navigate_path: {},
        root_browsing_dir: [{label: "DASHBOARD", name: "dashboard", "type": "folder"}],
        browsing_dir_array: [],
        uploadedTotalFiles: 0,
        // dropzoneOptions 
        dropzoneOptions: {
            // url: this.sfm_api_url + "upload.php",
            // params: this.query_string,
            thumbnailWidth: 150,
            addRemoveLinks: true,
            maxFilesize: 50.5,
//            dictDefaultMessage: "<i class='fa fa-cloud-upload'></i>UPLOAD ME ",
            dictDefaultMessage: '<h3 class="dropzone-custom-title"><i class="fa fa-cloud-upload"></i>  Drag and drop to upload content!</h3><div class="subtitle">...or  click to select a file from your computer</div>',
            init: function () {
                var totalFiles = 0, completeFiles = 0;
                this.on("addedfile", function (file) {
                    totalFiles += 1;
                });
                this.on("removedfile", function (file) {
                    totalFiles -= 1;
                    sfm_app.updateUploadCounter(totalFiles);
                });
                this.on("complete", function (file) {
                    //console.log(totalFiles);
                    sfm_app.updateUploadCounter(totalFiles);
                });
                this.on("queuecomplete", function (file) {

                });
                // Using a closure.
                var _this = this;
                // Setup the observer for the button.
//                document.querySelector("#remove_all_dropzone_files").addEventObserver("click", function () {
//                    // Using "_this" here, because "this" doesn't point to the dropzone anymore
//                    _this.removeAllFiles();
//                    // If you want to cancel uploads as well, you
//                    // could also call _this.removeAllFiles(true);
//                });


            },
            removeFile: function (file) {
                console.log(file);
            }
        }
    },
    computed: {

//        updateUploadCounter: function (counter) {
//            console.log(counter);
//        },
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    mounted: function () {
        let sss = this.root_browsing_dir;
        this.browsing_dir_array = [{label: "DASHBOARD", name: "dashboard", "type": "folder"}];
//         console.log(this.root_browsing_dir);
        this.dropzoneOptions.url = this.sfm_api_url + "upload.php";
        this.initBrowsingDir();
        this.fetchObjects();
    },
    methods: {
        removeAllDropzoneFiles: function () {
            //this.dropzoneOptions.init.removeAllFiles(true)
        },
        updateUploadCounter: function (counter) {

            this.uploadedTotalFiles = counter;
        },
        reset_message: function () {
            this.successMessage = "";
            this.errorMessage = "";
        },
        changeView: function (view_type) {
            this.view_type = view_type;
        },
        initBrowsingDir: function () {
            if (this.browsing_dir) {
                let splitDirs = this.browsing_dir.split("/");
                for (let key in splitDirs) {
                    let object = splitDirs[key];
                    if (object) {
                        this.current_folder = {"name": object, "label": object, "type": "folder"};
                        this.browsing_dir_array.push(this.current_folder);
                    }
                }
                this.query_string.c_dir = this.up_dir + "/";
                this.folder.is_directory = true;
                this.folder.folder_name = this.browsing_dir;
                this.saveFolder();
            }
        },
        set_query_string: function () {
            this.is_new_folder = false;
            this.is_rename_folder = false;
            this.current_action = "library";
            this.is_preview = false;
            this.browsing_dir = "";
            for (let key in this.browsing_dir_array) {
                let object = this.browsing_dir_array[key];

                if (object.name !== undefined && object.name !== "") {
                    if (object.name === 'dashboard') {
                    } else {
                        this.browsing_dir = this.browsing_dir + object.name + "/";
                    }
                }
            }

            this.query_string.c_dir = this.up_dir + "/";
            if (this.browsing_dir) {
                this.query_string.c_dir = this.up_dir + this.browsing_dir;
            }
            this.dropzoneOptions.params = this.query_string;
        },
        fetchObjects: function () {
            this.set_query_string();
            this.query_string.rid = Math.random().toString(36).substr(2, 9);
            let qs = this.makeSerialize(this.query_string);
            let action_url = this.sfm_api_url + 'get.php?' + qs;
            axios.get(action_url).then(res => {
                if (res.data.status === 200) {
                    this.items = res.data.items;
                    this.paging = res.data.paging;
                } else {
                    this.errorMessage = res.data.error;
                }
            });
        },
        library: function (item) {
//            console.log(this.browsing_dir_array);
//            console.log(this.current_folder);
            this.reset_message();
            //this.browsing_dir = this.up_dir;

            if (item === undefined || item.type === undefined) {
                this.browsing_dir_array = [{label: "DASHBOARD", name: "dashboard", "type": "folder"}];
                this.fetchObjects();
            } else if (item.type === 'folder') {

                let is_exist = false;
                let temp_dir = [];
                for (let key in this.browsing_dir_array) {
                    let object = this.browsing_dir_array[key];
                    temp_dir.push(object);
                    if (object.name === item.name) {
                        is_exist = true;
                        break;
                    }
                }

                //  console.log(temp_dir);
                this.browsing_dir_array = temp_dir;
                if (is_exist === false) {
                    this.browsing_dir_array.push(item);

                }
                this.current_folder = item;
                this.fetchObjects();
            } else if (item.type === 'file') {
               // console.log(this.select_action);
               // if(this.select_action==="direct"){  
                     if(this.select_action===""){  
                 this.selectItem(item);
                }
                else {
                this.previewItem(item);
                }
            }
        },
        X_backAndRemove: function (item) {
            let temp = []; //        this.browsing_dir_array; 
            for (let key in this.browsing_dir_array) {
                let object = this.browsing_dir_array[key];
                temp.push(object);
            }

            temp.reverse();
            for (let key in temp) {
                let object = temp[key];
                if (object.name === item.name) {
                    break;
                } else {
                    // back 
                    this.back();
                }
            }
        },
        fetchObject: function (item_id) {
            axios.get(this.sfm_api_url + "get/" + item_id).then(res => {
                if (res.status === 200) {
                    this.item = res.data.item;
                }
            });
        },
        refresh: function () {
            this.fetchObjects();
        },
        openUpload: function () {
            this.current_action = "upload";
            this.is_preview = false;
            this.reset_message();
        },
        saveFolder: function () {
            this.reset_message();
            // let qs = this.makeSerialize(this.query_string);
            let action_url = this.sfm_api_url + 'save_new_folder.php?';
            this.folder.c_dir = this.query_string.c_dir;
            var formData = this.formData(this.folder);
            axios.post(action_url, formData, this.headers).then(res => {
//            console.log(res);
                if (res.status === 200) {
                    if (this.folder.is_directory) {
                    } else {
                        this.successMessage = "Folder is created.";
                        this.folder = {};
                        this.current_action = "library";
                        this.fetchObjects();
                    }


                } else {
                    this.errorMessage = "Something is wrong.";
                }

            });
        },
        search: function () {

        },
        selectItem: function (item) {
            //  console.log(item);
            mshAfterFileSelect(item);
        },
        deleteItem: function (item) {
            if (confirm("Do want to delete? ")) {
                // delete items 
                let action_url = this.sfm_api_url + 'delete.php?';
                item.c_dir = this.query_string.c_dir;
                var formData = this.formData(item);
                axios.post(action_url, formData, this.headers).then(res => {
//            console.log(res);
                    if (res.status === 200) {
                        for (let key in this.items) {
                            let object = this.items[key];
                            if (object.name !== undefined && object.name === item.name) {
                                this.items.splice(key, 1);
                                this.errorMessage = "<strong> '" + object.name + " </strong>' has been deleted successfully.";
                            }
                        }

                        this.is_preview = false;
                    } else {
                        this.errorMessage = "Something is wrong.";
                    }

                });
            }
        },
        previewItem: function (file) {
            this.reset_message();
            this.is_preview = true;
            this.item = file;
        },
        backTo: function () {
            this.reset_message();
            this.back();
//             console.log( this.browsing_dir_array[2]); 
            this.fetchObjects();
        },
        back: function () {
            this.browsing_dir_array.splice(this.browsing_dir_array.length - 1, 1);
            this.current_folder = this.browsing_dir_array[this.browsing_dir_array.length - 1];
        },
        openNewItem: function () {
            this.set_query_string();
            this.is_new_folder = true;
            this.folder.folder_name = "";
            this.folder.is_directory = false;
            this.reset_message();
        },
        renameItem: function (file) {
            this.reset_message();
            this.is_preview = true;
            this.errorMessage = "Rename: Not Implemented Yet.... ";
            if (file === 'all') {

            } else {

            }
        },
        copyItem: function (file) {
            this.reset_message();
            this.is_preview = true;
            this.errorMessage = "Copy: Not Implemented Yet.... ";
        },
        downloadDirectory: function () {
            this.reset_message();
            this.errorMessage = "downloadDirectory: Not Implemented Yet.... ";
        },
        renameDirectory: function () {
            this.set_query_string();
            this.is_rename_folder = true;
            this.reset_message();
            this.folder.folder_name = this.current_folder.name;
            this.folder.previous_name = this.current_folder.name;
            
            // this.errorMessage = "renameDirectory: Not Implemented Yet.... ";
        },
        updateFolder: function () {
            this.reset_message();
            // let qs = this.makeSerialize(this.query_string);
            let action_url = this.sfm_api_url + 'update_folder.php?';
            this.folder.c_dir = this.query_string.c_dir;
            var formData = this.formData(this.folder);
            axios.post(action_url, formData, this.headers).then(res => {
//            console.log(res);
                if (res.status === 200) {
                    this.successMessage = "Folder is updated.";
                    this.browsing_dir_array.splice(this.browsing_dir_array.length - 1, 1);
                    this.browsing_dir_array.push(res.item);
                    this.current_folder = res.item;
                    //console.log(this.current_folder);
//                    this.folder = {};
//                    this.current_action = "library";
//                    this.library(this.current_folder);
//                    this.is_new_folder = false;
                } else {
                    this.errorMessage = "Something is wrong.";
                }

            });
        },
        closeFolder: function () {
            this.set_query_string();
            this.reset_message();
            this.is_rename_folder = false;
        },
        deleteDirectory: function () {
            this.reset_message();
            this.current_folder.is_derectory = true;
            this.deleteItem(this.current_folder);
            this.backTo();
            // this.errorMessage = "deleteDirectory: Not Implemented Yet.... ";
        },
        downloadItem: function (file) {
            this.reset_message();
            this.is_preview = true;
            this.errorMessage = "Download: Not Implemented Yet.... ";
            this.item = file;
        },
        formData: function (obj) {
            var formData = new FormData();
            for (let key in obj) {
                let file = obj[key];
                formData.append(key, file);
            }
            return formData;
        },
        makeSerialize: function (obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        },
        formatBytes: function (bytes, decimals) {
            if (bytes == 0)
                return '0 Bytes';
            var k = 1024,
                    dm = decimals <= 0 ? 0 : decimals || 2,
                    sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                    i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        },
    }



}
)
 