<template>
  <div class="row">
    <div class="col-12">
      <v-btn @click="videoDialog = true" class="primary mb-3">New Video</v-btn>
      <v-data-table :headers="headers" :items="videos" hide-default-footer class="elevation-1">
        <!-- <template v-slot:[`item.actions`]="{ item }"> -->
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon
            small
            title="Edit Hotspot"
            color="primary"
            class="mr-2"
            @click="editVideo(item)"
          >mdi-pencil</v-icon>
          <v-icon small title="Delete Hotspot" @click="deleteVideo(item)">mdi-trash-can</v-icon>
        </template>
      </v-data-table>
    </div>
    <v-dialog v-model="videoDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <h4 class="pb-2">Video</h4>
        </v-card-title>
        <v-card-text>
          <form>
            <v-text-field v-model="title" outlined label="Title" required class="py-0" dense></v-text-field>
            <div class="d-flex">
              <v-text-field
                v-model="videoPath"
                outlined
                dense
                label="Video URL"
                required
                class="py-0 mr-1"
              ></v-text-field>
              <v-btn class="mt-2" small icon @click="openMediaFiles">
                <v-icon small>mdi-folder-image</v-icon>
              </v-btn>
            </div>
            <div class="d-flex justify-end">
              <v-btn class="mr-1" text color="grey" @click="videoDialog = false">cancel</v-btn>
              <v-btn class="primary" @click="saveVideo">Save</v-btn>
            </div>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteDialog" max-width="300px">
      <v-card>
        <v-card-title class="subtitle-1">Confirm delete</v-card-title>
        <v-card-text>
          Are you sure you want to delete
          <strong
            class="black--text"
          >{{title ? title : 'this video'}}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" text @click="deleteDialog = false">cancel</v-btn>
          <v-btn color="primary" text @click="confirmDelete">Confirm</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <media-files :mediaOptions="mediaFilesSettings" @responded="mediaResponse" />
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: String,
      default: "",
    },
    authUser: {
      type: Object,
      default: null,
    },
  },
  watch: {
    deleteDialog: function (val) {
      if (val == false) {
        this.id = null;
      }
    },
    videoDialog: function (val) {
      if (val == false) {
        this.title = "";
        this.videoPath = "";
        this.dialogAction = "save";
      }
    },
  },
  data() {
    return {
      deleteDialog: false,
      dialogAction: "save",
      videoDialog: false,
      id: null,
      title: "",
      videoPath: "",

      // Media Files
      mediaFilesSettings: {
        dialog: true,
        dialogStatus: false,
        user: this.authUser,
        action: "save",
        data: null,
        product: this.product,
        itemType: "video",
        returnUrl: true,
      },

      headers: [
        {
          text: "Videos",
          align: "start",
          value: "title",
          sortable: false,
        },
        { text: "Actions", align: "end", value: "actions", sortable: false },
      ],
      videos: [],
    };
  },
  methods: {
    mediaResponse(v) {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
      this.videoPath = v != false ? v : this.videoPath;
    },
    openMediaFiles() {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
    },
    deleteVideo(v){
      this.id = v.id;
      this.deleteDialog = true;
    },
    confirmDelete(){
      axios
        .post("/video/delete/" + this.id)
        .then((response) => {
          this.deleteDialog = false;
          this.id = null;
          console.log("delete success");
          this.fetchAllVideos();
        })
        .catch((error) => {
          console.log("Error Deleting Video");
          console.log(error);
        });
    },
    editVideo(v) {
      this.dialogAction = "update";
      this.id = v.id;
      this.title = v.title;
      this.videoPath = v.video_path;
      this.videoDialog = true;
    },
    saveVideo() {
      let route = "save";
      if (this.dialogAction == "update") {
        route = "update/" + this.id;
      }
      let data = {
        title: this.title,
        video_path: this.videoPath,
        product_id: this.product,
      };
      axios
        .post("/video/" + route, data)
        .then((response) => {
          this.videoDialog = false;
          this.title = "";
          this.videoPath = "";
          this.id = null;
          this.fetchAllVideos();
          console.log("success");
        })
        .catch((error) => {
          console.log("Error Saving Video");
          console.log(error);
        });
    },
    fetchAllVideos() {
      axios
        .get("/video/all/" + this.product)
        .then((response) => {
          this.videos = response.data.videos;
        })
        .catch((error) => {
          console.log("Error Fetching Videos");
          console.log(error);
        });
    },
  },
  created() {
    this.fetchAllVideos();
  },
};
</script>
