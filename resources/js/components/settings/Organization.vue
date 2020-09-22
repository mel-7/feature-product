<template>
  <v-row>
    <div class="col-12 col-md-6 px-5">
      <h3 class="font-weight-light mb-5">Organization Settings</h3>
      <v-card>
        <v-card-text>
          <form>
            <!-- :src="baseUrl+'/storage/uploads/'+companyId+'/'+file.path" -->
            <v-img
              width="200"
              height="200"
              contain
              class="grey lighten-4 mb-5 rounded elevation-1"
              :src="logo"
            >
              <template v-slot:placeholder>
                <v-img
                  :src="baseUrl+'/images/no-image-placeholder.jpg'"
                  width="200"
                  height="200"
                  cover
                  class="grey lighten-4 py-3"
                ></v-img>
              </template>
            </v-img>
            <div class="d-flex">
              <v-text-field v-model="logo" outlined label="Logo" required class="py-0" dense></v-text-field>
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
      fetchedLogo: "",
      fetchedTitle: "",
      fetchedDescription: "",

      logo: "",
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
      this.logo = v != false ? v : this.logo;
    },
    openMediaFiles() {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
    },
    resetFields() {
      this.logo = this.fetchedLogo;
      this.title = this.fetchedTitle;
      this.description = this.fetchedDescription;
    },
    updateOrg() {
      let data = {
        logo: this.logo,
        title: this.title,
        description: this.description,
      };
      axios
        .post("/settings/organization/update", data)
        .then((response) => {
        //   this.fetchOrg();
         // console.log(response.data);
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
          this.fetchedLogo = response.data.logo ? response.data.logo : "";
          this.fetchedTitle = response.data.title;
          this.fetchedDescription = response.data.description
            ? response.data.description
            : "";
          this.logo = response.data.logo ? response.data.logo : "";
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
