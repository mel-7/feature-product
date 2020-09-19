<template>
  <v-row>
    <div class="col-12 px-5">
      <h3 class="font-weight-light mb-5">Watermark Settings</h3>
      <v-card>
        <v-card-text>
          <div class="row">
            <div class="col-12 col-md-6">
              <ValidationObserver ref="observer">
                <form class="d-flex flex-column" style="height:100%;">
                  <div class="d-flex">
                    <v-switch
                      v-model="watermarkOn"
                      :label="`${watermarkOn == true ? 'Enabled' : 'Disabled' }`"
                      inset
                      class="mt-0"
                    ></v-switch>
                  </div>
                  <ValidationProvider v-slot="{ errors }" name="Name" rules="required">
                    <div class="d-flex">
                      <v-text-field
                        v-model="watermark"
                        outlined
                        label="Watermark Image"
                        required
                        :error-messages="errors"
                        dense
                        readonly
                      ></v-text-field>
                      <v-btn class="mt-2 ml-3" small icon @click="openMediaFiles">
                        <v-icon small>mdi-folder-image</v-icon>
                      </v-btn>
                    </div>
                  </ValidationProvider>
                  <v-select
                    v-model="position"
                    :items="positions"
                    item-text="label"
                    item-value="value"
                    label="Position"
                    return-object
                    outlined
                    dense
                    class="mb-0"
                  ></v-select>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="Width"
                    rules="required|numeric|min_value:50|max_value:1366"
                  >
                    <v-text-field
                      type="number"
                      v-model="imageWidth"
                      outlined
                      :error-messages="errors"
                      label="Width (px)"
                      required
                      dense
                      class="mt-0"
                    ></v-text-field>
                  </ValidationProvider>
                  <div class="d-flex">
                    <div class="subtitle-1" style="width:120px">
                      Offset:
                      <strong class="primary--text ml-auto">{{offsetSpace}}px</strong>
                    </div>
                    <v-slider v-model="offsetSpace" min="1" max="100" class="pt-0"></v-slider>
                  </div>
                  <div class="d-flex">
                    <div class="subtitle-1" style="width:120px">
                      Opacity:
                      <strong class="primary--text ml-auto">{{imageOpacity}}%</strong>
                    </div>
                    <v-slider v-model="imageOpacity" min="1" max="100" class="pt-0"></v-slider>
                  </div>
                  <div class="d-flex justify-end mt-auto">
                    <v-btn
                      class="mr-auto"
                      text
                      color="red"
                      title="Delete Watermark Settings"
                      @click="deleteWatermark"
                    >Delete</v-btn>
                    <v-btn class="mr-1" text color="grey" @click="resetFields">reset</v-btn>
                    <v-btn class="primary" @click="saveWatermark">Save</v-btn>
                  </div>
                </form>
              </ValidationObserver>
            </div>
            <div class="col-12 col-md-6">
              <span class="overline">Preview</span>
              <v-card-text class="grey lighten-4 pa-0">
                <div style="position:relative;width:100%;height:100%;padding-bottom:75%;">
                  <v-img
                    width="100"
                    min-height="50"
                    contain
                    :class="`${position.value ? position.value : position } grey lighten-4 rounded elevation-0`"
                    :src="watermark == '' ? baseUrl+'/images/no-image-placeholder.jpg': baseUrl+'/storage/uploads/'+authUser.company_id+'/'+ watermark"
                    :style="`position:absolute; margin:${offsetSpace}px;opacity: ${imageOpacity == 100 ? '1' : imageOpacity < 10 ? '.0'+imageOpacity : '.'+imageOpacity};`"
                  >
                    <!-- baseUrl+'/storage/uploads/'+authUser.company_id+'/'+ watermark :  -->
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
            </div>
          </div>
        </v-card-text>
      </v-card>
    </div>
    <media-files :mediaOptions="mediaFilesSettings" @responded="mediaResponse" />
  </v-row>
</template>

<script>
import { required, name } from "vee-validate/dist/rules";
import {
  ValidationObserver,
  ValidationProvider,
} from "vee-validate/dist/vee-validate.full";

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
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
      fetchedImageWidth: "",
      fetchedImageOpacity: "",

      selectedImage: [],

      watermarkId: null,
      watermarkOn: false,
      watermark: "",
      imageWidth: "300",
      imageOpacity: 50,
      position: "center",
      offsetSpace: "15",

      positions: [
        { label: "Top Left", value: "top-left" },
        { label: "Top", value: "top" },
        { label: "Top Right", value: "top-right" },
        { label: "Left", value: "left" },
        { label: "Center (default)", value: "center" },
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
        returnUrl: false,
        returnPath: false,
        returnObject: true,
      },
    };
  },
  methods: {
    mediaResponse(v) {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
      this.selectedImage = v;
      this.watermark = v ? v.path : this.watermark;
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
    deleteWatermark() {
      axios
        .post("/settings/watermark/delete/" + this.watermarkId)
        .then((response) => {
          this.watermarkOn = false;
          this.watermark = "";
          this.position = this.positions[4];
          this.offsetSpace = "15";
          this.imageOpacity = 50;
          this.imageWidth = "300";
        })
        .catch((error) => {
          console.log("Error deleting Watermark settings");
          console.log(error);
        });
    },
    saveWatermark() {
      let data = {
        media_file_id: this.selectedImage.id,
        watermark: this.watermark.replace("watermark/", ""),
        image_width: this.imageWidth,
        image_opacity: this.imageOpacity,
        position: this.position.value ? this.position.value : this.position,
        offset_space: this.offsetSpace,
        status: this.watermarkOn,
      };
      axios
        .post("/settings/watermark/save", data)
        .then((response) => {
          //   this.fetchOrg();
          console.log(response.data);
        })
        .catch((error) => {
          console.log("Error saving Watermark");
          console.log(error);
        });
    },
    fetchedCompanyWatermark() {
      axios
        .get("/settings/watermark/fetch")
        .then((response) => {
          let w = response.data;
          console.log(w);
          if (Object.keys(w).length != 0) {
            this.fetchedwatermark = w.media_file.path;
            this.fetchedposition = w.position;
            this.fetchedoffsetSpace = w.offset_space;
            this.fetchedImageWidth = w.image_width;
            this.fetchedImageOpacity = w.image_opacity;
            this.fetchedWatermarkOn = w.status;

            this.selectedImage = w.media_file;
            this.watermarkId = w.id;
            this.watermarkOn = w.status;
            this.watermark = w.media_file.path;
            this.imageWidth = w.image_width;
            this.imageOpacity = w.image_opacity;
            this.position = w.position;
            this.offsetSpace = w.offset_space;
          } else {
            console.log("Watermark is not set");
          }
        })
        .catch((error) => {
          console.log("Error fetching Watermark");
          console.log(error);
        });
    },
  },
  created() {
    this.fetchedCompanyWatermark();
  },
  mounted() {
    this.position = this.positions[4];
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
  margin-left: 0 !important;
  margin-right: 0 !important;
  top: 0;
  left: 50%;
  right: auto;
  bottom: auto;
  transform: translateX(-50%);
}
.top-right {
  top: 0;
  right: 0;
  left: auto;
  bottom: auto;
}
.left {
  margin-top: 0 !important;
  margin-bottom: 0 !important;
  left: 0;
  top: 50%;
  right: auto;
  bottom: auto;
  transform: translateY(-50%);
}
.center {
  margin: 0 !important;
  left: 50%;
  top: 50%;
  right: auto;
  bottom: auto;
  transform: translate(-50%, -50%);
}
.right {
  margin-top: 0 !important;
  margin-bottom: 0 !important;
  left: auto;
  top: 50%;
  right: 0;
  bottom: auto;
  transform: translateY(-50%);
}
.bottom-left {
  left: 0;
  top: auto;
  right: auto;
  bottom: 0;
}
.bottom {
  margin-left: 0 !important;
  margin-right: 0 !important;
  top: auto;
  left: 50%;
  right: auto;
  bottom: 0;
  transform: translateX(-50%);
}
.bottom-right {
  left: auto;
  top: auto;
  right: 0;
  bottom: 0;
}
</style>
