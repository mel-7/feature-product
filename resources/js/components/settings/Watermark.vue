<template>
  <v-row>
    <div class="col-12 col-md-4 px-5">
      <h3 class="font-weight-light mb-5">Watermark Settings</h3>
      <v-card>
        <v-card-text>
          <span class="overline">Preview</span>
          <!-- :src="baseUrl+'/storage/uploads/'+companyId+'/'+file.path" -->
          <v-responsive :aspect-ratio="16/9" class="grey lighten-4 rounded">
            <v-card-text class="pa-0">
              <div class="d-flex pa-5">
                <v-img
                  width="100"
                  min-height="50"
                  contain
                  :class="`${position != '' ? position.value : ''} grey lighten-4 rounded elevation-0`"
                  :src="watermark"
                  style="position:absolute;"
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
              </div>
            </v-card-text>
          </v-responsive>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <form>
            <div class="d-flex">
              <v-text-field
                v-model="watermark"
                outlined
                label="Watermark Image"
                required
                class="py-0"
                dense
              ></v-text-field>
              <v-btn class="mt-2 ml-3" small icon @click="openMediaFiles">
                <v-icon small>mdi-folder-image</v-icon>
              </v-btn>
            </div>
            <v-select
              v-model="position"
              :items="positions"
              item-text="label"
              item-value="value"
              label="Positions"
              return-object
              outlined
              dense
            ></v-select>
            <v-text-field
              v-model="offsetSpace"
              outlined
              label="Offset space in px"
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
      fetchedposition: "",
      fetchedoffsetSpace: "",

      watermark: "",
      position: "",
      offsetSpace: "",

      positions: [
        { label: "Top Left (default)", value: "top-left" },
        { label: "Top", value: "top" },
        { label: "Top Right", value: "top-right" },
        { label: "Left", value: "left" },
        { label: "Center", value: "center" },
        { label: "Right", value: "right" },
        { label: "Bottom Left", value: "bottom-left" },
        { label: "Bottom", value: "bottom" },
        { label: "Bottom Right", value: "bottom-right" },
      ],
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
      this.position = this.fetchedposition;
      this.offsetSpace = this.fetchedoffsetSpace;
    },
    updateOrg() {
      let data = {
        watermark: this.watermark,
        position: this.position,
        offset_space: this.offsetSpace,
      };
      console.log(data);
      //   axios
      //     .post("/settings/organization/update", data)
      //     .then((response) => {
      //       //   this.fetchOrg();
      //       console.log(response.data);
      //     })
      //     .catch((error) => {
      //       console.log("Error Fetching Organization");
      //       console.log(error);
      //     });
    },
    fetchOrg() {
      console.log("fetch watermark settings");
      //   axios
      //     .get("/settings/organization/fetch")
      //     .then((response) => {
      //       this.fetchedwatermark = response.data.watermark ? response.data.watermark : "";
      //       this.fetchedposition = response.data.position;
      //       this.fetchedoffsetSpace = response.data.offsetSpace
      //         ? response.data.offsetSpace
      //         : "";
      //       this.watermark = response.data.watermark ? response.data.watermark : "";
      //       this.position = response.data.position;
      //       this.offsetSpace = response.data.offsetSpace
      //         ? response.data.offsetSpace
      //         : "";
      //     })
      //     .catch((error) => {
      //       console.log("Error Fetching Organization");
      //       console.log(error);
      //     });
    },
  },
  created() {
    this.fetchOrg();
  },
};
</script>

<style lang="css">
.top-left {
  top: 0;
  left: 0;
  right: auto;
  bottom: auto;
}
.top {
  margin: 0 auto;
  top: 0;
  left: 0;
  right: 0;
  bottom: auto;
}
.top-right {
  top: 0;
  right: 0;
  left: auto;
  bottom: auto;
}
.left {
  left: 0;
  top: auto;
  right: auto;
  bottom: auto;
}
.center {
  left: auto;
  top: auto;
  right: auto;
  bottom: auto;
}
.right {
  right: 0;
  left: auto;
  top: auto;
  bottom: auto;
}
.bottom-left {
  left: 0;
  top: auto;
  right: auto;
  bottom: 0;
}
.bottom {
  left: 0;
  top: auto;
  right: 0;
  bottom: 0;
  margin: 0 auto;
}
.bottom-right {
  left: auto;
  top: auto;
  right: 0;
  bottom: 0;
}
</style>
