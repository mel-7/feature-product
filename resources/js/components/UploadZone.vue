<template>
  <div style="width:100%;">
    <div style="max-height:450px;overflow-y:scroll;background-color: #f6f6f6;">
      <vue-dropzone
        class="file-upload"
        ref="myVueDropzone"
        id="dropzone"
        :options="dropzoneOptions"
        v-on:vdropzone-sending="sendingEvent"
        v-on:vdropzone-drop="dropFunction"
        v-on:vdropzone-success-multiple="uploadSuccess"
        v-on:vdropzone-removed-file="removedFile"
        v-on:vdropzone-processing-multiple="processing"
        v-on:vdropzone-error-multiple="uploadError"
        :include-styling="false"
        :useCustomSlot="preview"
      >
        <div v-if="preview == true" class="dropzone-custom-content">
          <p>Drop your images here</p>
          <v-btn large color class="open-uploader">Upload</v-btn>
        </div>
      </vue-dropzone>
    </div>
    <div class="pa-3 d-flex">
      <v-spacer></v-spacer>
      <v-btn class="ml-3" color="grey" text @click="removeAllFiles">clear</v-btn>
      <v-btn class="ml-3" color="primary" :loading="btnLoading" @click="upload">Upload</v-btn>
    </div>
  </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
export default {
  props: {
    addItems: {
      type: Boolean,
      default: false,
    },
    itemType: {
      type: String,
      default: "360",
    },
  },
  components: {
    vueDropzone: vue2Dropzone,
  },
  data() {
    return {
      model: null,
      dropzoneOptions: {
        url: "/files/upload",
        thumbnailWidth: 40,
        thumbnailHeight: 40,
        uploadMultiple: true,
        autoProcessQueue: false,
        maxFiles: 100,
        parallelUploads: 100,
        maxFilesize: this.itemType == 'video' ? 30 : this.itemType == 'panorama' ? 3 : 1,
        timeout: 180000,
        previewTemplate: this.dropzoneTemplate(),
        clickable: ".open-uploader",
        headers: {
          "x-csrf-token": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
      },
      dropzone: null,
      preview: true,
      btnLoading: false,
    };
  },
  methods: {
    dropzoneTemplate() {
      return `<div class="dz-preview dz-file-preview d-flex pa-2 blue lighten-5">
                <img data-dz-thumbnail />
                <div class="dz-details d-flex align-center justify-start">
                  <div class="px-1 caption">
                    <div class="dz-filename" data-dz-name></div>
                     <div class="dz-size px-1 caption" data-dz-size></div>
                    <div class="error--text" data-dz-errormessage></div>
                  </div>
                  <div class="dz-progress d-flex align-center justify-center caption">
                    <span class="dz-upload" data-dz-uploadprogress></span>
                  </div>
                </div>
                <v-spacer></v-spacer>
                <button
                  data-dz-remove
                  type="button"
                  class="ml-auto v-btn v-btn--flat v-btn--icon v-btn--round theme--light v-size--x-small error--text"
                >
                  <span class="v-btn__content">
                    <i
                      aria-hidden="true"
                      class="v-icon notranslate mdi mdi-trash-can-outline theme--light"
                      style="font-size: 12px;"
                    ></i>
                  </span>
                </button>
              </div>`;
    },
    processing() {
      this.btnLoading = true;
    },
    dropFunction(e) {
      e.preventDefault();
      this.preview = false;
      // this.sendWithFile = true;
      // this.dragging = false;
    },
    sendingEvent(file, xhr, formData) {
      //   console.log(formData);
      formData.append("product", this.$route.params.id);
      formData.append("add_items", this.addItems);
      formData.append("item_type", this.itemType);
    },
    removeAllFiles() {
      this.$refs.myVueDropzone.removeAllFiles();
      this.preview = true;
    },
    removedFile(file, xhr, formData) {
      this.$refs.myVueDropzone.getQueuedFiles().length == 0
        ? (this.sendWithFile = false)
        : "";
    },
    uploadSuccess(files, response) {
      this.btnLoading = false;
      this.$refs.myVueDropzone.removeAllFiles();
      this.$emit("uploaded", response);
      //   this.sendWithFile = false;
      //   this.loading = false;
    },
    uploadError(files, message, xhr) {
      this.btnLoading = false;
      console.log(message);
    },
    upload() {
      this.$refs.myVueDropzone.processQueue();
    },
  },
  mounted() {
    //   console.log(this.addItems);
    //   console.log(this.itemType);
  },
};
</script>
<style lang="scss">
.loading-sheet {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  bottom: auto;
  right: auto;
}
.file-upload {
  min-height: 400px;
  height: 100%;
  max-height: 100%;
  width: 100%;
  padding: 4px;
  // display: flex;
  // flex-wrap: wrap;
  // justify-content: flex-start;
  .dz-message {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    min-height: 400px;
    height: 100%;
  }
  .dz-preview {
    width: calc(33.33% - 16px);
    min-width: calc(33.33% - 16px);
    border-radius: 4px;
    margin: 8px;
    min-height: 60px;
    float: left;
    .dz-details {
      .dz-filename {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
      }
      img {
        width: 30px;
        height: auto;
      }
    }
    &.dz-error.dz-complete {
      background-color: #ffebee !important;
    }
  }
}
.drop-wrapper {
  background-color: #fdfdfd;
  .drop-msg {
    position: absolute;
    top: auto;
    left: 0;
    right: 0;
    bottom: 20px;
    width: 100%;
    text-align: center;
  }
  .textfield {
    width: 100%;
    border-top: 1px solid #f1f1f1;
    background-color: #ffffff;
  }
}
</style>
