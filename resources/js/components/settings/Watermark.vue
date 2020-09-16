<template>
  <v-row>
    <div class="col-12 col-md-4 px-5">
      <h3 class="font-weight-light mb-5">Organization Settings</h3>
      <v-card>
        <v-card-text>
          <span class="overline">Preview</span>
          <!-- :src="baseUrl+'/storage/uploads/'+companyId+'/'+file.path" -->
          <v-responsive :aspect-ratio="16/9" class="grey lighten-4 rounded">
            <v-card-text>
              <v-img
                width="100"
                min-height="50"
                contain
                class="grey lighten-4 rounded elevation-0"
                :src="watermark"
              >
                <template v-slot:placeholder>
                  <v-img
                    :src="baseUrl+'/images/no-image-placeholder.jpg'"
                    width="100"
                    height="50"
                    cover
                    class="grey lighten-4"
                  ></v-img>
                </template>
              </v-img>
            </v-card-text>
          </v-responsive>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <form>
            <div class="d-flex">
              <v-text-field v-model="watermark" outlined label="Watermark" required class="py-0" dense></v-text-field>
              <v-btn class="mt-2 ml-3" small icon @click="openMediaFiles">
                <v-icon small>mdi-folder-image</v-icon>
              </v-btn>
            </div>
            <v-text-field v-model="title" outlined label="Title" required class="py-0" dense></v-text-field>
            <v-text-field
              v-model="description"
              outlined
              label="Description"
              required
              class="py-0"
              dense
            ></v-text-field>
            <div class="d-flex justify-end">
              <v-btn class="mr-1" text color="grey" @click="resetFields">reset</v-btn>
              <v-btn class="primary" @click="updateOrg">Update</v-btn>
            </div>
          </form>
        </v-card-text>
      </v-card>
    </div>
    <media-files :mediaOptions="mediaFilesSettings" @responded="mediaResponse" />
  </v-row>
</template>

<script>
export default {
  props: {
    authUser: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      fetchedwatermark: "",
      fetchedTitle: "",
      fetchedDescription: "",

      watermark: "",
      title: "",
      description: "",

      baseUrl: window.location.origin,

      // Media Files
      mediaFilesSettings: {
        dialog: true,
        dialogStatus: false,
        user: this.authUser,
        action: "save",
        data: null,
        product: null,
        itemType: "image",
        returnUrl: true,
      },
    };
  },
  methods: {
    mediaResponse(v) {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
      this.watermark = v != false ? v : this.watermark;
    },
    openMediaFiles() {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
    },
    resetFields() {
      this.watermark = this.fetchedwatermark;
      this.title = this.fetchedTitle;
      this.description = this.fetchedDescription;
    },
    updateOrg() {
      let data = {
        watermark: this.watermark,
        title: this.title,
        description: this.description,
      };
      axios
        .post("/settings/organization/update", data)
        .then((response) => {
          //   this.fetchOrg();
          console.log(response.data);
        })
        .catch((error) => {
          console.log("Error Fetching Organization");
          console.log(error);
        });
    },
    fetchOrg() {
      axios
        .get("/settings/organization/fetch")
        .then((response) => {
          this.fetchedwatermark = response.data.watermark ? response.data.watermark : "";
          this.fetchedTitle = response.data.title;
          this.fetchedDescription = response.data.description
            ? response.data.description
            : "";
          this.watermark = response.data.watermark ? response.data.watermark : "";
          this.title = response.data.title;
          this.description = response.data.description
            ? response.data.description
            : "";
        })
        .catch((error) => {
          console.log("Error Fetching Organization");
          console.log(error);
        });
    },
  },
  created() {
    this.fetchOrg();
  },
};
</script>

<style>
</style>
