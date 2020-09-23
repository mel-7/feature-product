<template>
  <v-row>
    <div class="col-12 px-5">
      <h3 class="font-weight-light mb-5">Edit {{fetchedwatermark.title}}</h3>
      <v-card>
        <v-card-text>
          <div class="row">
            <div class="col-12 col-md-6" style="position:relative;">
              <v-overlay absolute :value="loading" color="white" opacity=".75">
                <v-progress-circular indeterminate color="primary"></v-progress-circular>
              </v-overlay>
              <ValidationObserver ref="observer">
                <form class="d-flex flex-column" style="height:100%;">
                  <div class="d-flex">
                    <v-switch
                      v-model="watermark.status"
                      :label="`${watermark.status == 1 ? 'Enabled' : 'Disabled' }`"
                      class="mt-0"
                    ></v-switch>
                  </div>
                  <ValidationProvider v-slot="{ errors }" rules="required">
                    <div class="d-flex">
                      <v-text-field
                        v-model="watermark.title"
                        outlined
                        label="Title"
                        required
                        :error-messages="errors"
                        dense
                      ></v-text-field>
                    </div>
                  </ValidationProvider>
                  <ValidationProvider v-slot="{ errors }" rules="required">
                    <div class="d-flex">
                      <v-text-field
                        v-model="watermark.path"
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
                  <div>
                    <v-select
                      v-model="watermark.position"
                      :items="positions"
                      item-text="label"
                      item-value="value"
                      label="Position"
                      return-object
                      outlined
                      dense
                    ></v-select>
                  </div>
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="Width"
                    rules="required|numeric|min_value:50|max_value:1366"
                  >
                    <v-text-field
                      type="number"
                      v-model="watermark.image_width"
                      outlined
                      :error-messages="errors"
                      label="Width (px)"
                      required
                      dense
                    ></v-text-field>
                  </ValidationProvider>
                  <div class="d-flex">
                    <div class="subtitle-1" style="width:120px">
                      Offset:
                      <strong class="primary--text ml-auto">{{watermark.offset_space}}px</strong>
                    </div>
                    <v-slider v-model="watermark.offset_space" min="1" max="100" class="pt-0"></v-slider>
                  </div>
                  <div class="d-flex">
                    <div class="subtitle-1" style="width:120px">
                      Opacity:
                      <strong class="primary--text ml-auto">{{watermark.image_opacity}}%</strong>
                    </div>
                    <v-slider v-model="watermark.image_opacity" min="1" max="100" class="pt-0"></v-slider>
                  </div>
                  <v-spacer></v-spacer>
                  <v-divider class="mb-5"></v-divider>
                  <div class="d-flex justify-end mt-auto">
                    <v-btn class="mr-1" text color="grey" @click="resetFields">reset</v-btn>
                    <v-btn class="primary" @click="updateWatermark">Save</v-btn>
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
                    :class="`${typeof watermark.position === 'object' ? watermark.position.value : watermark.position } grey lighten-4 rounded elevation-0`"
                    :src="watermark.path == null ? baseUrl+'/images/no-image-placeholder.jpg': baseUrl+'/storage/uploads/'+authUser.company_id+'/'+ watermark.path"
                    :style="`border:1px dashed #ddd !important;position:absolute; margin:${watermark.offset_space}px;opacity: ${watermark.image_opacity == 100 ? '1' : watermark.image_opacity < 10 ? '.0'+watermark.image_opacity : '.'+watermark.image_opacity};`"
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
// import { computesRequired } from 'vee-validate/dist/types/rules/required';

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
      // ui
      loading: false,

      fetchedwatermark: [],
      selectedImage: [],

      watermark: {
        id: null,
        title: "",
        path: null,
        position: "center",
        offset_space: "",
        image_width: "",
        image_opacity: "",
        company_id: null,
        status: 0,
        media_file_id: null,
      },
      watermarkPath: "",

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
        watermarkOptions: false,
      },
    };
  },
  watch: {},
  methods: {
    mediaResponse(v) {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
      this.selectedImage = v;
      this.watermark.path = v ? v.path : this.watermark.path;
    },
    openMediaFiles() {
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
    },
    resetFields() {
      this.watermark = Object.assign({}, this.fetchedwatermark);
    },
    updateWatermark() {
      this.loading = true;
      let data = {
        title: this.watermark.title,
        media_file_id: this.selectedImage.id,
        watermark: this.watermark.path.replace("watermark/", ""),
        image_width: this.watermark.image_width,
        image_opacity: this.watermark.image_opacity,
        position:
          typeof this.watermark.position === "object"
            ? this.watermark.position.value
            : this.watermark.position,
        offset_space: this.watermark.offset_space,
        status: this.watermark.status,
      };
      axios
        .post("/settings/watermark/update/" + this.$route.params.id, data)
        .then((response) => {
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log("Error saving Watermark");
          console.log(error);
        });
    },
    fetchedCompanyWatermark() {
      this.loading = true;
      axios
        .get("/settings/watermark/get/" + this.$route.params.id)
        .then((response) => {
          this.loading = false;
          let w = response.data;
          if (Object.keys(w).length != 0) {
            this.watermark = Object.assign({}, w);
            this.fetchedwatermark = Object.assign({}, w);
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
