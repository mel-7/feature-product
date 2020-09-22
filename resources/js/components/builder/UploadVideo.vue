<template>
  <v-row>
    <v-col cols="6" class="mx-auto">
      <v-file-input v-model="video" ref="video" show-size label="Upload a video"></v-file-input>
      <v-btn :loading="loading" color="primary" @click="uploadVideo">Upload</v-btn>
    </v-col>
    <v-col cols="12">
      <v-btn @click="load360">hey</v-btn>
      <v-img :src="'/display/file/video-footage-example_1_0.jpeg'" aspect-ratio="1" width="100px"></v-img>

      <button v-on:click="toggleShow">CREATE DESTROY</button>
      <button v-on:click="next">NEXT</button>
      <button v-on:click="prev">PREV</button>
      <button v-on:click="togglePlayback">TOGGLE</button>
      <button v-on:click="toggleFullscreen">FULLSCREEN</button>
      <spritespin v-bind:options="options" v-if="show" ref="spritespin" />
    </v-col>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      video: null,
      fileName: "",

      // exterior images
      show: true,
      options: {
        source: "",
        width: 1366,
        height: 650,
        frames: 34,
        framesX: 6,
        // sense: -1,
      },
    };
  },
  methods: {
    toggleShow: function () {
      this.show = !this.show;
    },
    prev: function () {
      if (this.$refs.spritespin) {
        this.$refs.spritespin.api.stopAnimation();
        this.$refs.spritespin.api.prevFrame();
      }
    },
    next: function () {
      if (this.$refs.spritespin) {
        this.$refs.spritespin.api.stopAnimation();
        this.$refs.spritespin.api.nextFrame();
      }
    },
    togglePlayback: function () {
      if (this.$refs.spritespin) {
        this.$refs.spritespin.api.toggleAnimation();
      }
    },
    toggleFullscreen: function () {
      if (this.$refs.spritespin) {
        this.$refs.spritespin.api.requestFullscreen();
      }
    },
    load360() {
      axios
        .get("/files/fetch")
        .then((response) => {
          response.data.map(function (file) {
            this.options.source.push("http://127.0.0.1:8000/display/file/" + file.path);
          });
          //console.log(this.options);
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    uploadVideo() {
      this.loading = true;
      console.log(this.video);
      let data = new FormData();
      data.append("product", 1); // needs to be dynamic
      data.append("video", this.video);
      data.append("title", this.video.name.split(".").slice(0, -1).join("."));
      axios
        .post("/video/store", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.loading = false;
          //console.log(response);
          // this.load360();
        })
        .catch((error) => {
          this.loading = false;
          console.log(error.response);
        });
    },
  },
  mounted() {
    // this.load360();
  },
};
</script>
