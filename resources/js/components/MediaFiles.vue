<template>
  <div>
    <v-dialog
      v-if="mediaOptions.dialog == true"
      v-model="mediaDialog"
      scrollable
      persistent
      max-width="80%"
    >
      <v-card :loading="mediaLoading" style="min-height:450px;">
        <v-card-title class="overline px-3 py-0">
          Media Files
          <v-spacer></v-spacer>
          <v-icon color="red" @click="closeMediaDialog">mdi-close</v-icon>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-title class="overline px-3">
          <v-btn
            class="mr-3"
            :class="`${tabItem == 'upload' ? 'primary' : ''}`"
            depressed
            @click="uploadTab"
          >
            Upload
            <v-icon small right>mdi-cloud-upload</v-icon>
          </v-btn>
          <v-btn :class="`${tabItem == 'mediafiles' ? 'primary' : ''}`" depressed @click="mediaTab">
            Media Files
            <v-icon small right>mdi-panorama</v-icon>
          </v-btn>

          <v-btn v-if="imageClickActive == true" depressed class="ml-5 warning float-right" @click="applyWatermark">Apply Watermark</v-btn>
          <v-btn v-if="imageClickActive == true" depressed class="ml-5 red float-right" @click="removeWatermark">Remove Watermark</v-btn>
           <v-checkbox
            v-if="imageClickActive == true"
            v-model="multiple"
            label="Multiple Selection"
          ></v-checkbox>
        </v-card-title>
        <v-card-text class="blue-grey lighten-5 pt-3" v-show="tabItem == 'upload'">
          <upload-zone
            :add-items="false"
            :item-type="mediaOptions.itemType"
            :watermark-options="mediaOptions.watermarkOptions"
            @uploaded="uploadZoneResponse"
          />
        </v-card-text>
        <v-card-text class="blue-grey lighten-5 pt-3" v-show="tabItem == 'mediafiles'">
          <v-row class="px-2">
            <v-col v-for="file in files" :key="file.id" cols="2" class="pa-2">
              <v-card
                @click="selectFile(file)"
                :class="`pa-1 elevation-0 ${selected.includes(file.id) == true || selected.includes(file.path) == true ? 'primary' : 'transparent'}`"
              >
                <v-img
                  v-if="file.file_type == 'image'"
                  :src="baseUrl+'/storage/uploads/'+companyId+'/'+file.path"
                  max-height="130"
                  min-height="120"
                  contain
                  class="grey lighten-4"
                >
                  <template v-slot:placeholder>
                    <v-img
                      :src="baseUrl+'/images/no-image-placeholder.jpg'"
                      max-height="130"
                      min-height="120"
                      cover
                      class="grey lighten-4"
                    ></v-img>
                  </template>
                  <v-icon
                    v-if="selected.includes(file.id) == true || selected.includes(file.path) == true"
                    class="primary"
                    dark
                    small
                  >mdi-check</v-icon>
                </v-img>
                <v-img
                  v-else
                  :src="baseUrl+'/images/video-placeholder.jpg'"
                  max-height="130"
                  min-height="120"
                  cover
                  class="grey lighten-4"
                >
                  <v-icon
                    v-if="selected.includes(file.id) == true"
                    class="primary"
                    dark
                    small
                  >mdi-check</v-icon>
                </v-img>
              </v-card>
              <div
                class="caption"
              >{{file.title.substring(0, 20)}}{{file.title.length > 20 ? '...': ''}}</div>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions v-if="tabItem == 'mediafiles'">
          <v-spacer></v-spacer>
          <v-btn color="grey" text @click="closeMediaDialog">Cancel</v-btn>
          <v-btn color="primary" text @click="submitSelected">Select</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
      <v-snackbar
      v-model="snackbar" 
      :color="color" 
      :right="x === 'right'"
      :timeout="timeout"
      :top="y === 'top'"
      :vertical="mode === 'vertical'"
    >
      {{ text }}  
    </v-snackbar>
  </div>
  
</template>

<script>
import UploadZone from "./UploadZone";
export default {
  props: {
    mediaOptions: {
      type: Object,
      default: null,
    },
  },
  components: {
    UploadZone,
  },
  watch: {
    mediaOptions: {
      handler(val) {
        this.mediaDialog = val.dialogStatus;
      },
      deep: true,
    },
  },
  data() {
    return {
      imageClickActive: false,
      tabItem: "upload",
      companyId: this.mediaOptions.user.company_id,
      userId: this.mediaOptions.user.id,
      files: [],
      baseUrl: window.location.origin,
      mediaDialog: false,
      mediaLoading: false,
      tab: null, 
      color: '',
      mode: 'vertical',
      snackbar: false,
      text: '',
      timeout: 5000,
      x: '',
      y: '',

      return_url: this.mediaOptions.returnUrl
        ? this.mediaOptions.returnUrl
        : false,
      return_path: this.mediaOptions.returnPath
        ? this.mediaOptions.returnPath
        : false,

      submitAction: this.mediaOptions.action
        ? this.mediaOptions.action
        : "save",
      // Selected File
      multiple: this.mediaOptions.multiple ? this.mediaOptions.multiple : false,
      selected: [],
      selectedObject: [],
      watermarkPath: [],

    };
  },
  methods: {
    uploadZoneResponse() {
      this.selected = [];
      this.tabItem = "mediafiles";
      this.getUserFiles();
    },
    mediaTab() {
      this.tabItem = "mediafiles";
      this.getUserFiles();
    },
    uploadTab() {
      this.tabItem = "upload";
    },
    selectFile(file) {
      let i = file.id;
      let pathImg = file.path;
      let lngt = this.selected.length;
      let chk = true; 
     
      // Select single file
      if (this.multiple == false) {
            this.imageClickActive = true;
            this.watermarkPath = [];
            this.selected = [];
          if(lngt > 0 && this.selected[0] == i){ 
            this.selected.pop(0); 
            this.imageClickActive = false; 
            chk = false;
          }else if (this.selected.length > 0 ) { 
              this.selected.pop(0);  
          } 

          if(chk){
            this.watermarkPath[0] = file.path;
            
              if (this.mediaOptions.returnObject) {
                // Used in watermark
                this.selected.push(file.path);
                this.selectedObject = file;
              
              } else {
               
                // If Return URL used in hotspot images
                if (this.return_url == true || this.return_path == true) {
                  this.selected.push(file.path);
                } else {
                  this.selected.push(i);
                }
              }
          } 
        
        // Select multiple files
      } else {
        if (this.selected.includes(i)) {
          // if already exist pop out
          let index = this.selected.indexOf(i);
          if (index > -1) {
            this.selected.splice(index, 1);
          } 
          
        } else {
          // otherwise push
          this.selected.push(i);
         
        }

        if (this.watermarkPath.includes(pathImg)) { 

          let indx = this.watermarkPath.indexOf(pathImg);
          if (indx > -1) {
            this.watermarkPath.splice(indx, 1);
          }
        } else {
          // otherwise push 
          this.watermarkPath.push(file.path);
        }
      } 
     
    },
    applyWatermark(){
      let data = {
          selected: this.watermarkPath
      }
       axios
          .post("/files/apply_watermark", data)
          .then((response) => { 
            console.log(response);
              this.snackbar = true;
              this.color = "success";
              this.x = "right";
              this.y = "top";
              this.text = "Watermark has been added!";
          })
          .catch((error) => {
            this.watermarkPath = [];
           
          });
    },
     removeWatermark(){
      let data = {
          selected: this.watermarkPath
      }
       axios
          .post("/files/remove_watermark", data)
          .then((response) => { 
              this.snackbar = true;
              this.color = "success";
              this.x = "right";
              this.y = "top";
              this.text = "Watermark has been removed!";
          })
          .catch((error) => {
            this.watermarkPath = [];
           
          });
    },
    submitSelected() {
      this.imageClickActive = false; 
      if (this.mediaOptions.returnObject) {
        this.$emit("responded", this.selectedObject);
        this.selected = []; 
      }
      // If only needs to return the url of the selected image
      if (this.return_path == true) {
        this.$emit("responded", this.selected[0]);
        this.selected = [];
      } else if (this.return_url == true) {
        let toReturlUrl =
          this.baseUrl +
          "/storage/uploads/" +
          this.companyId +
          "/" +
          this.selected[0];
        this.$emit("responded", toReturlUrl);
        this.selected = [];
      } else {
        // Save to Item Table
        // Replace Item 360 Image
        let data = {
          selected: this.selected,
          item: this.mediaOptions.data ? this.mediaOptions.data.id : null,
          item_type: this.mediaOptions.itemType
            ? this.mediaOptions.itemType
            : null,
          action: this.submitAction,
          product: this.mediaOptions.product ? this.mediaOptions.product : null,
        };
        axios
          .post("/item/save", data)
          .then((response) => {
          
            if (response.data.status == "success") {
              this.$emit("responded", response.data);
              this.selected = [];
            }
             
          })
          .catch((error) => {
            this.selected = [];
           
          });
      }
    },
    closeMediaDialog() {
      this.mediaDialog = false;
      this.$emit("responded", false);
      this.selected = [];
       this.imageClickActive = false; 
    },
    getUserFiles() {
      if (this.selected.length == 0) {
        axios
          .get("/user/files/" + this.userId)
          .then((response) => {
            console.log("Media Files has been loaded");
            this.files = Object.assign({}, response.data.data);
            
          })
          .catch((error) => {
            console.log("Error Fetching Files in getUserFiles");
            console.log(error);
          });
      }
    },
  },
  mounted() {
    // console.log(this.mediaOptions);
  },
};
</script>
