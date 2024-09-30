 

<div class="section p-0 m-0" id="afm_app"  >

    <div class="afm_header px-2 py-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
        <h2 class="h2">Smart File Manager </h2>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button type="button" @click="openUpload"  class="btn btn-md btn-warning text-bold">Upload +</button>
            </div>
            <div class="  mr-2 pt-1">
                <button type="button" @click="openNewItem"  class="btn btn-sm btn-success ">New Folder +</button>
                <button class="btn btn-sm btn-info dropdown-toggle   " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{view_type}} 
                    <div class="dropdown-menu">
                        <a @click="changeView('Grid')"  class="dropdown-item" href="#">Grid</a> 
                        <a @click="changeView('List')"   class="dropdown-item" href="#">List</a> 
                    </div>
                </button>
            </div>
            <!--            <div class="btn-group mr-2">
                            <button class="btn  btn-sm btn-warning dropdown-toggle  py-0 px-1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Type 
                                <div class="dropdown-menu">
                                    <a v-for="(key, type) in type_list"  class="dropdown-item" href="#">{{type}}</a> 
                                </div>
                            </button>
                        </div>-->



            <div class="dropdown pt-1">
                <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Settings
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>

            <div class="pl-5 pr-2"> <span class="close" onclick="afmClose();">&times;</span></div> 
        </div>
    </div>
    <div class="afm_breadcrumb px-2 py-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center  border-bottom">
        <div class="">
            <!--{{browsing_dir_array}}-->
            <a v-for="( dir, key) in browsing_dir_array" @click="library(dir)" :class="browsing_dir_array.length===(key+1)?'last':'prev'" href="javascript:void(0);" >{{dir.label}}/</a> 

        </div>
        <div  class="btn-toolbar mb-2 mb-md-0">
            <button v-if="browsing_dir_array.length>1"   @click="backTo"  class="btn btn-sm btn-outline-success mr-2"> Back </button>
            <button    @click="refresh"  class="btn btn-sm btn-outline-info mr-2"> Refresh </button>
            <button v-if="browsing_dir_array.length>1"   @click="downloadDirectory"  class="btn btn-sm btn-outline-warning  mr-2">   Download </button>
            <button v-if="browsing_dir_array.length>1"   @click="renameDirectory"  class="btn btn-sm btn-outline-info  mr-2"> Rename Folder </button>
            <button v-if="browsing_dir_array.length>1"   @click="deleteDirectory"  class="btn btn-sm btn-outline-danger  mr-2"> Delete Folder </button>

        </div>
    </div>

    <div class="afm_app_body row p-4    ">
        <div class="col-12 pb-4"> 
            <div v-if="errorMessage"  v-html="errorMessage"  class="alert alert-danger m-1"></div>
            <div v-if="successMessage"  v-html="successMessage"  class="alert alert-success m-1"></div> 
        </div>
        <div v-if="is_new_folder"  class="col-12   p-2">
            <div class="row justify-content-md-center"> 
                <div class="col-md-12 px-5">
                    <div class="card align-items-center pb-3 pt-4">
                        <div class="row" style="width: 100%;"> 
                            <div class="col-md-4 text-right pt-2"> <strong>Path: </strong> {{up_dir}}{{browsing_dir}} </div>
                            <div class="col-md-6 ">
                                <form enctype="multipart/form-data"  @submit.prevent="saveFolder"  class="form form-vertical needs-validation"  method="post">
                                    <div class="input-group mb-3">
                                        <input  type="text" class="form-control" placeholder="Folder name..." required="true" v-model="folder.folder_name" >
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">
                                                <span v-if="is_new_folder">Create</span> 
                                                Folder</button>
                                            <div class="pt-2 pl-3">
                                                or <span @click="closeFolder" class="font-bold link cancel_action"> Cancel</span> 
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="mt-5">Files: </h3>
        </div>

        <div v-if="is_rename_folder"  class="col-12   p-2">
            <div class="row justify-content-md-center"> 
                <div class="col-md-12 px-5">
                    <div class="card align-items-center pb-3 pt-4">
                        <div class="row" style="width: 100%;"> 
                            <div class="col-md-4 text-right pt-2"> <strong>Path: </strong> {{up_dir}}{{browsing_dir.pop()}} </div>
                            <div class="col-md-6 ">
                                <form enctype="multipart/form-data"  @submit.prevent="updateFolder"  class="form form-vertical needs-validation"  method="post">
                                    <div class="input-group mb-3">
                                        <input   type="hidden" class="form-control"   v-model="folder.previous_name"  >
                                        <input  type="text" class="form-control" placeholder="Folder name..." required="true" v-model="folder.folder_name" >
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">
                                                <span v-if="is_rename_folder">Rename</span> 
                                                Folder</button>
                                            <div class="pt-2 pl-3">
                                                or <span @click="closeFolder" class="font-bold link"> Cancel</span> 
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--<div v-if="current_action=='library'" class="col pt-3" style="max-height: 400px; overflow: auto; "  >-->
        <div v-if="current_action=='library'" class="col pt-3 afm_item_contaimer"   >
            <div  v-if="view_type=='Grid'" class="row grid_view"  >


                <div v-for="item in items" class="col-xs-3 col-sm-2  p-1">
                    <div @click="library(item);" class="card image-over-card  border-0">
                        <div class="card-image">
<!--                            <a href="assets/img/gallery/full/full-11.jpg" data-caption="<div class='text-center'>Place your caption here.<br><em class='text-muted'><i class='zmdi zmdi-favorite'></i> Material Wrap</em></div>" data-width="1600" data-height="1068" itemprop="contentUrl">
                                <img src="http://materialwrap-html.authenticgoods.co/assets/img/gallery/full/full-11.jpg" itemprop="thumbnail" alt="Image description">
                            </a>-->
                            <img v-if="item.icon=='fa-picture-o'" class="card-img-top img-fluid img-responsive" :src="afm_base_url+up_dir+browsing_dir+item.name" :alt="item.name">
                            <i  v-else-if="item.type=='folder'" :class="'fa '+item.icon"></i>
                            <i  v-else="item.icon!='fa-picture-o'" :class="'iconic fa '+item.icon"></i>

                        </div>
                        <div class="card-body p-1">
                            <p class="afm_file_name text-center">{{item.label}}</p>
                            <!--<p class="text-center">{{item.type}}</p>-->
                        </div>
                    </div>
                </div>


            </div>
            <table v-if="view_type=='List'" class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col" style="width: 100px!important;">Size</th>
                        <th scope="col" style="width: 135px!important;">Modified</th>
                        <th scope="col" class="text-right" style="width: 135px!important;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items">
                        <td> ..</td>
                        <td>   

                            <a @click="library(item);" href="javascript:void(0);">
                                <img v-if="item.icon=='fa-picture-o'" class="card-img-top img-fluid img-responsive" :src="afm_base_url+up_dir+browsing_dir+item.name" :alt="item.name" style="width:30px;">
                                <i v-else  :class="'fa '+item.icon"></i>

                                {{item.name}} 
                            </a> 
                        </td>
                        <td>  {{item.size}} </td>
                        <td> {{item.modified}}  </td>
                        <td class="actions text-right w-auto"  >
                            <a v-if="item.type=='file'" title="Preview" href="javaScript:void(0)" @click="previewItem(item)"><i class="fa fa-eye"></i></a>
                            <a title="Delete" href="javaScript:void(0);" @click="deleteItem(item)"><i class="fa fa-trash-o"></i></a>
                            <a title="Rename" href="javaScript:void(0)" @click="renameItem(item)" ><i class="fa fa-pencil-square-o"></i></a>
                            <a title="Copy to..." href="javaScript:void(0)"  @click="copyItem(item)"><i class="fa fa-files-o"></i></a>
                            <a v-if="item.type=='file'" title="Direct link" :href="afm_base_url+up_dir+browsing_dir+item.name" target="_blank"><i class="fa fa-link"></i></a>
                            <a v-if="item.type=='file'" title="Download" href="javaScript:void(0)" @click="downloadItem(item)"><i class="fa fa-download"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="items.length===0" class="alert alert-warning"> 
                There is no file or folder. 
                Please <button type="button" @click="openUpload"  class="btn btn-sm btn-warning">Upload</button> file or 
                <button type="button" @click="openNewItem"  class="btn btn-sm btn-info"> create folder</button>. 

            </div>
        </div>





        <div v-if="is_preview"  class="col-4  p-2 "  >
            <div class="card preview make-me-sticky p-0">
                <div class="card-body p-0 text-center"> 
                    <div class=" py-2">
                        <a class="btn btn-success btn-sm  text-right" title="Select" href="javaScript:void(0)" @click="selectItem(item)"><i class="fa fa-tick"></i> Select</a>
                    </div>
                    <img v-if="item.icon=='fa-picture-o'" class="card-img-top img-fluid img-thumbnail" :src="afm_base_url+up_dir+browsing_dir+item.name" :alt="item.name">
                    <i  v-if="item.icon!='fa-picture-o'" :class="'iconic fa '+item.icon"></i>
                </div>
                <div class="card-body  p-1">
                    <h5 class="card-title p-0 m-0">{{item.label}}</h5>
                    <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                </div>
                <ul class="list-group list-group-flush p-1">
                    <li class="list-group-item  p-0">{{item.name}}</li>
                    <li class="list-group-item  p-0"><strong>Size:</strong> {{item.size}}</li>
                    <li class="list-group-item  p-0"><strong>Modified:</strong>  {{item.modified}}</li>
                    <li class="list-group-item  p-0"><strong>Type:</strong>  {{item.type}}</li>
                    <li class="list-group-item  p-0"> 
                        <a class="card-link" v-if="item.type=='file'" title="Download" href="javaScript:void(0)" @click="downloadItem(item)"><i class="fa fa-download"></i> Download</a>
                        <a class="card-link" title="Rename" href="javaScript:void(0)" @click="renameItem(item)" ><i class="fa fa-pencil-square-o"></i> Edit</a>
                        <a class="card-link" title="Copy to..." href="javaScript:void(0)"  @click="copyItem(item)"><i class="fa fa-files-o"></i> Copy</a>
                        <a class="card-link" title="Delete" href="javaScript:void(0);" @click="deleteItem(item)"><i class="fa fa-trash-o"></i> Delete</a>

                    </li>

                    <li class="list-group-item  p-0"> 
                        <strong><i class="fa fa-link"></i> Direct link:</strong>     <a class="card-link" v-if="item.type=='file'" title="Direct link" :href="afm_base_url+up_dir+browsing_dir+item.name" target="_blank">{{afm_base_url+up_dir+browsing_dir+item.name}} </a>
                    </li>

                </ul>

            </div>


        </div>



        <div v-if="current_action=='upload'"  class="col-12  " >


            <div class="path">

                <div class="card mb-2 fm-upload-wrapper">

                    <div class="card-body">
                        <div class="row">
                            <div class="card-text col">
                                <strong>  Path:</strong>   {{up_dir}}{{browsing_dir}}
                            </div>
                            <div class="card-text col">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"> <strong>  Image Format:</strong>   </span>
                                    </div>
                                    <select class="form-control">
                                        <option value="0">As Original</option>
                                        <option value="webp">WebP</option>
                                    </select>
                                </div>


<!--                                 <strong>  Image Format:</strong>   
<select class="form-control">
    <option value="0">As Original</option>
    <option value="webp">WebP</option>
</select>-->
                            </div>
                            <div class="card-text col">
                                 <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"> <strong>  Image resize(Max width):</strong>   </span>
                                    </div>
                                     <input type="text" class="form-control" value="1200" />
                                </div>
                                
                              
                            </div>

                        </div>
                        <vue-dropzone :options="dropzoneOptions" :useCustomSlot=true id="sa_dropzone">
                            <div class="dropzone-custom-content">
                            </div>
                        </vue-dropzone>

                        <div v-if="uploadedTotalFiles" class="afm_breadcrumb px-2 py-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center  border-bottom">
                            <div class="">
                                <button id="remove_all_dropzone_files" class="btn btn-danger pull-left ua-remove-all"  >Remove  All</button>
                            </div>
                            <div class="">
                                Total File:  {{uploadedTotalFiles}}
                            </div>

                            <div   class="">
                                <button class="btn btn-success pull-right ua-save-all" @click="fetchObjects">Ok, Fine</button>

                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </div>


    </div>

    <div class="afm_footer px-2 py-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center  border-bottom">
        <div class="">
            Path:   {{up_dir}}{{browsing_dir}}
        </div>
        <div   class="btn-toolbar mb-2 mb-md-0">
            Smart File Manager ...  

        </div>
    </div>
    <div v-if="afm_debug" class="col-md-12 bg-dark text-white">
        <h2>Debug: </h2>
        <p>  Path:   {{up_dir}}{{browsing_dir}}</p>
        <p>  browsing_dir:   {{browsing_dir}} </p>

        <p>Current Folder: </p>
        <pre class="text-white" v-if="current_folder" >
             {{current_folder}}
        </pre> 
        <p>Current File: </p>
        <pre class="text-white"  v-if="item">
             {{item}}
        </pre> 


    </div>
</div>





<!--<script type="text/javascript" src="src/js/afm_fm.js"></script>-->
<style>
    .grid_view .fa{
        font-size: 100px;
    }
    .grid_view img{
        max-height: 100px;
    }
    .grid_view .card{
        cursor: pointer;
    }
    .preview .iconic{
        font-size: 120px;
    }

    .dropzone{
        background:#d9edf7;
        border: 2px dotted  #00796b;
        color: #00796b;
        text-align: left;
        vertical-align: middle;
        padding: 15px;
        margin: 15px 0px;

    }

    .dropzone-custom-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .dropzone-custom-title {
        margin-top: 0;
        color: #00b782;
    }

    .subtitle {
        color: #314b5f;
    }



    form.dropzone {
        min-height: 200px;
        border: 2px dashed #007bff;
        line-height: 6rem;
    }
</style>