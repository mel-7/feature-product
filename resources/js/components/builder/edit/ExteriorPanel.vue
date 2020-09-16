<template>
  <div>
    <input type="hidden" id="cur-frame" />
    <!-- <div id="results"></div> -->
    <!-- <div class="col-12 py-0 d-flex justify-space-between align-center"> -->
    <v-toolbar dense class="elevation-1">
      <v-toolbar-title>Product ID: {{product}}</v-toolbar-title>
      <v-spacer></v-spacer>
      <div v-show="hotspots.length != 0">
        <v-btn color="primary" small @click="applyHotspot">
          <v-icon small class="mr-1">mdi-check</v-icon>Save Hotspots
        </v-btn>
      </div>
    </v-toolbar>
    <!-- </div> -->
    <div>
      <div style="min-height:450px;background-color:#eee;">
        <upload-zone v-show="uploader == true" :add-items="true" :item-type="'360'" @uploaded="getImagesByProduct"></upload-zone>
        <div class="spritespin-wrapper" v-show="uploader == false" >
          <spritespin
            v-bind:options="options"
            v-if="show && uploader == false"
            ref="spritespin" 
            style="margin:0 auto;"
          />
          <!-- :data-hps="`${spot.hotspotObjectToEmit.id}`" -->
          <div v-show="hotspots.length != 0" style="width:100%;">
            <!-- <div class="hotspot-draggable-wrapper"> -->
            <!-- Action Buttons -->
            <div
              v-for="(spot, index) in hotspots"
              :key="index"
              :data-hps="`${spot.id}`"
              :id="`${spot.id}`"
              :class="`cd-single-point draggable-hotspot hotspot-default-position hotspot-id-${spot.id}`"
            >
              <a class="cd-img-replace" href="#0">More</a>
              <div class="cd-label d-flex align-center">
                <span
                  :title="spot.title"
                  class="px-1"
                >{{spot.title != null && spot.title.length > 10 ? spot.title.substring(0, 10)+'..' : spot.title}}</span>
                <v-btn
                  icon
                  x-small
                  :title="'Delete '+spot.title+' hotspot'"
                  class="ml-auto"
                  @click="removeHotspotSettings(spot.id)"
                >
                  <v-icon x-small color="black">mdi-close</v-icon>
                </v-btn>
              </div>
            </div>
            <!-- </div> -->
          </div>
        </div>
      </div>
      <div class="d-flex" v-if="withItems == true">
        <v-card class="mt-3" style="width:90%;">
          <v-slide-group v-model="model" center-active class="pa-1" active-class="grey" show-arrows>
            <v-slide-item
              v-for="(item, index) in items"
              :key="index"
              v-slot:default="{ active, toggle }"
            >
              <v-card class="ma-1 elevation-0">
                <v-card
                  :color="active ? undefined : 'white'"
                  class="ma-1"
                  height="auto"
                  width="100"
                  @click="toggle"
                >
                  <!-- to disabled -->
                  <v-img
                    @click="selected(index, item)"
                    :aspect-ratio="16/9"
                    :data-targetid="JSON.stringify(item)"
                    :class="'white--text align-end target-frame-'+index"
                    :src="baseUrl+'/storage/uploads/'+authUser.company_id+'/'+item.media_file.path"
                    style="border:0;opacity:.75;"
                  >
                    <v-card-text>{{index+1}}</v-card-text>
                  </v-img>
                </v-card>
                <div class="d-flex justify-center py-1">
                  <v-btn x-small icon @click="editItem(item)">
                    <v-icon x-small color="primary">mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn x-small icon @click="deleteItem(item)">
                    <v-icon x-small color="red">mdi-trash-can-outline</v-icon>
                  </v-btn>
                </div>
              </v-card>
            </v-slide-item>
          </v-slide-group>
        </v-card>
        <v-card
          dark
          :class="`mt-3 ml-2 d-flex align-center justify-center ${uploader == false ? 'primary' : 'error'}`"
          active-class="red"
          style="width:10%"
          @click="uploader = !uploader"
        >
          <v-icon>{{uploader == false ? 'mdi-image-plus' : 'mdi-image-remove'}}</v-icon>
        </v-card>
      </div>
    </div>
    <v-dialog v-model="actionDialog" max-width="300">
      <v-card :loading="dialogLoading">
        <v-card-title
          class="subtitle-1"
        >Are you sure you want to delete {{dialogItem && dialogItem.media_file.path}}?</v-card-title>
        <v-card-text>Deleting this item will delete the file and hotspot information.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn :disabled="dialogLoading" color="primary" text @click="actionDialog = false">Cancel</v-btn>
          <v-btn
            :disabled="dialogLoading"
            color="red"
            text
            @click="confirmDelete(dialogItem.id)"
          >Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <media-files :mediaOptions="mediaFilesSettings" @responded="mediaResponse"></media-files>
  </div>
</template>

<script>
/**
 * To update
 * 1. Add onframe event - hotspots should be set when onframe is triggered.
 */
var temp_hotspots = [];
var allHps = [];
import MediaFiles from "../../MediaFiles";
import UploadZone from "../../UploadZone";
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
    selectedHotspotProp: {
      type: Object,
      default: null,
    },
  },
  components: {
    // SingleHotspot,
    MediaFiles,
    UploadZone,
  },
  data() {
    return {
      enableButton: [],

      settingsInCurrentScene: [],

      // Fetched Hotspot Settings
      fetchedHotspotSettings: null,

      // Saved hotspots settings
      // Loop hotspots with asigned exterior settings
      theHotspot: [],
      hotspots: [],
      toSetHotspot: {
        itemID: null,
        hotspotID: null,
        hotspotSettings: {
          top: null,
          left: null,
        },
      },
      tempItemID: null,

      // MediaFiles
      mediaFilesSettings: {
        dialog: true,
        dialogStatus: false,
        user: this.authUser,
        action: "replace",
        data: null,
      },

      dialogLoading: false,
      dialogItem: null,
      actionDialog: false,
      withItems: false,
      model: null,
      items: [],
      baseUrl: window.location.origin,

      // 360
      uploader: true,
      show: false,
      options: {
        source: [],
        animate: false,
        // responsive: true,
        // width: 1920,
        // height: 1080,
        width: 800,
        height: 450,
        frames: 0,
        framesX: 6,
        plugins: ["drag", "360"], //"zoom"
        sense: -1,
        // onFrame: function (e, data) {
        //   // console.log(data.frame);
        // },
        onFrameChanged: this.spritespinOnFrame,
      },
    };
  },
  watch: {
    selectedHotspotProp: {
      // To set hotspot
      handler(val) {
        // console.log(val)
        // console.log(this.hotspots)
        this.hotspots.push(val);
        this.draggableFunc();
      },
      deep: true,
    },
  },
  methods: {
    removeHotspotSettings(spotId) {
      let refId = spotId;
     
        $("#"+spotId).css({'display': "none"});
        $(".hp-"+spotId).show();
        
        var hpSettings = {};
        hpSettings.top = 5;
        hpSettings.left = 5;
        hpSettings.display = 'none';
         
          temp_hotspots[spotId+this.tempItemID] = {
          hotspotsID: spotId,
          itemID: this.tempItemID,
          hotspotSettings: hpSettings,
        };
        var filtered = temp_hotspots.filter(function (el) {
              return el != null;
            });

        tempHotspots = JSON.stringify(filtered);
       
        console.log(tempHotspots)
    },
    closeHotspot() {
      //console.log("close hotspot");
    },
    applyHotspot() {
      // Get the selected item_id
      // Get the hotspot hotspot_id
      let data = {
        hotspot_settings: tempHotspots,
      };
     // console.log(data);
      axios
        .post("/hotspot/apply", data)
        .then((response) => {
          tempHotspots = [];
          this.draggableFunc();
          this.getHotspotSettings();
        })
        .catch((error) => {
          console.log("Error Applying Hotspots");
          console.log(error);
        });
    },
    getHotspotSettings() {
      axios
        // .get("/hotspot/settings/" + this.product)
        .get("/hotspot/product/" + this.product)
        .then((response) => {
          this.hotspots = response.data.settings;
          this.draggableFunc();
        })
        .catch((error) => {
          console.log("Error Fetching Hotspots");
          console.log(error);
        });
    },
    mediaResponse(res) {
      // console.log(res.status);
      if (res.status == "error") {
        return;
      }
      this.mediaFilesSettings.dialogStatus = false;
      this.getImagesByProduct();
    },
    editItem(item) {
      // Toggle Dialog
      this.mediaFilesSettings.dialogStatus = !this.mediaFilesSettings
        .dialogStatus;
      this.mediaFilesSettings.data = item;
    },
    deleteItem(item) {
      this.actionDialog = true;
      this.dialogItem = Object.assign({}, item);
    },
    confirmDelete(item) {
     // console.log(hotspotsID)
      this.dialogLoading = true;
      axios
        .post("/item/delete/" + item)
        .then((response) => {
          this.dialogLoading = false;
          this.actionDialog = false;
          
          this.getImagesByProduct();
        })
        .catch((error) => {
          this.dialogLoading = false;
          this.actionDialog = false;
          console.log("Error Deleting Items");
          console.log(error);
        });
    },

    spritespinOnFrame() {
      if (this.$refs.spritespin) {
        // console.log("item id: "+$("#cur-frame").val())
        let targetFrame = this.$refs.spritespin.data.frame;
        let targetItem = $('.target-frame-'+targetFrame).data('targetid');
        // targetItem = JSON.parse(targetItem);
        // console.log("frame: "+ targetFrame);
        // console.log("item: "+ targetItem);
        this.selected(targetFrame, targetItem);
      }
    },
    selected(index, id = null) {
      
      allHps = this.hotspots;
       $(".draggable-hotspot").css({
          left:  "5%",
          top: "5%",
          display: "none",
        });

      $(".default-hp").show();

      //   $(".cd-single-point").hide();
      let dItemId = id.id; // Current Item ID
      this.settingsInCurrentScene = []; // Settings Variable
      let tempSettings = [];
    
      this.hotspots.map(function (k, i) { 
        if(k){
        k.hotspot_settings.map(function (inner, index) {
          if (inner.item_id == dItemId) {
            // Insert all the settings on the selected Item ID
            tempSettings.push(inner);
          
          } 
        }); 
        }
      });

      tempSettings.map(function (s, index) {
        if(s){
          let parseData = JSON.parse(s.hotspot_settings);   
         
          // Apply hotspot style from the settings variable
          $(".draggable-hotspot.hotspot-id-" + s.hotspot_id).css({
            left: parseData.left ? parseData.left+"%" : "5%",
            top: parseData.top ? parseData.top+"%" : "5%",
            display: parseData.display,
          });

          if($(".hotspot-id-"+s.hotspot_id).is(":hidden")){
            $(".hp-"+s.hotspot_id).show();
          }else{
            $(".hp-"+s.hotspot_id).hide();
          }
        }
      });
      this.settingsInCurrentScene = tempSettings;
      //   console.log(this.settingsInCurrentScene);

      var hpItems = [];
      if (tempHotspots.length > 0) {
        hpItems = JSON.parse(tempHotspots);
      }
      if (hpItems.length > 0) {
        $.each(hpItems, function (i, o) {
          if (o.itemID == id.id) {   

            $("#" + o.hotspotsID).css({
              left: o.hotspotSettings.left + "%",
              top: o.hotspotSettings.top + "%",
              display: o.hotspotSettings.display,
            });

            if($(".hotspot-id-"+o.hotspotsID).is(":hidden")){
              $(".hp-"+o.hotspotsID).show();
            }else{
              $(".hp-"+o.hotspotsID).hide(); 
            }
          }
        });
      }
      $("#cur-frame").val(id.id);
      if(this.$refs.spritespin){
      this.$refs.spritespin.api.updateFrame(index);
      }
      this.$emit("selectedItem", id.id);
      this.tempItemID = id.id;
    },
    getImagesByProduct() {
      this.show = false;
      axios
        .get("/items/by-product/" + this.product)
        .then((response) => {   
          // console.log(response.data.items);
          // If no items found
          if (response.data.items.length == 0) {
            this.withItems = false;
            this.uploader = true;
            return;
          }

          this.withItems = true;
          this.uploader = false;

          // Set Items
          this.items = response.data.items;
          this.isItemsLoaded = true;

          // Setup 360
          this.options.frames = response.data.items.length;
          this.options.source = response.data.items.map(
            (item) =>
              window.location.origin +
              "/storage/uploads/" +
              this.authUser.company_id +
              "/" +
              item.media_file.path
          );

          setTimeout(() => {
            this.show = true;
          }, 1);


          if (this.items[0].length !== 0) {
            setTimeout(() => {
              this.selected(0, this.items[0]);
            }, 3000);
          }
        })
        .catch((error) => {
          console.log("Error fetching items");
          console.log(error);
        });
    },
    draggableFunc() {
      // console.log(i + " : ss");
      var hotspotObject = this.toSetHotspot;
      var topPercentage;
      var leftPercentage;
      
      // this.$nextTick(function () {
      $(function () {
        $(".draggable-hotspot").draggable({
          containment: ".spritespin-wrapper",
          drag: function () {
            // coordinates(".draggable-hotspot");
            var widthWrapper = $(".spritespin-wrapper").width();
            var heightWrapper = $(".spritespin-wrapper").height();
            var left = $(this).position().left;
            var top = $(this).position().top;
            leftPercentage = (left / widthWrapper) * 100;
            topPercentage = (top / heightWrapper) * 100;
            // console.log(topPercentage.toFixed(2));
            // console.log(selectedItemId);
          },
          stop: function () {
            var hpSettings = {
              top: null,
              left: null,
              display: 'none'
            };
            hpSettings.top = topPercentage.toFixed(2);
            hpSettings.left = leftPercentage.toFixed(2);
            hpSettings.display = 'block';
            var ieID = $("#cur-frame").val();
            var hpsId = $(this).attr("data-hps");
            temp_hotspots[ieID + hpsId] = {
              hotspotsID: hpsId,
              itemID: ieID,
              hotspotSettings: hpSettings,
            };
            
            console.log(allHps)

            var filtered = temp_hotspots.filter(function (el) {
              return el != null;
            });

            tempHotspots = JSON.stringify(filtered);

            console.log(tempHotspots);
          },
        });
        // this.toSetHotspot = hotspotObject;
      });
    },
  },
  created() {
    this.getImagesByProduct();
    this.getHotspotSettings();
   
  },
  mounted() {},
};
</script>

<style lang="scss" scoped>
/** Hotspot Action */
.hotspot-action {
  position: absolute;
  top: 5px;
  right: 5px;
  left: auto;
  bottom: auto;
}
.cd-label {
  cursor: move;
  position: absolute;
  top: 0;
  left: 100%;
  padding: 0 5px;
  background-color: #fff;
  border-radius: 10px;
  margin-left: 5px;
  bottom: auto;
  right: auto;
  max-width: 120px;
  width: 120px;
  &::after {
    content: "";
    width: 0px;
    height: 0px;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid #fff;
    position: absolute;
    right: 98%;
    top: 0;
    bottom: auto;
    left: auto;
    // z-index: 1;
    margin-top: 4px;
  }
}

/** Draggrable */
.hotspot-draggable-wrapper {
  background-color: rgba(0, 0, 0, 0.25);
  max-width: 800px;
  width: 800px;
  height: 450px;
  max-height: 450px;
  margin: 0 auto;
  overflow: hidden;
  position: absolute;
  top: 0;
  left: 50%;
  bottom: 0;
  right: 0;
  transform: translateX(-50%);
}

/**.hotspot */
.spritespin-wrapper {
  width: 800px;
    height: 450px;
    margin: 0 auto;
  position: relative;
}
.hotspot-wrapper {
  background-color: rgba(0, 0, 0, 0.25);
  height: 100%;
  width: 100%;
  z-index: 9999999;
  position: absolute;
  top: 0;
  left: 0;
  right: auto;
  bottom: auto;
}
.action-wrapper {
  position: absolute;
  left: auto;
  right: auto;
  top: auto;
  bottom: 34px;
  width: 100%;
}

.spritespin-buttons-wrapper {
  /* margin-top: 30px; */
  display: flex;
  justify-content: center;
  width: 200px;
  margin: 0 auto;
}

.spritespin-slider {
  width: 100%;
}

.spritespin-buttons-wrapper .button {
  font-size: 24px;
  line-height: 24px;
  padding: 0 10px 5px;
  margin: 0 10px;
  cursor: pointer;
  color: #fff;
  background-color: #191e47;
}

.content-action {
  margin-top: 15px;
  display: flex;
  justify-content: center;
}

.open-exterior,
.open-interior {
  text-transform: uppercase;
  cursor: pointer;
  color: #fff;
  padding: 13px 20px;
  background-color: #191e47;
  font-size: 24px;
  line-height: 24px;
}

.active {
  background-color: #fbad18;
}

/* HotSpot */
.cd-img-replace {
  /* replace text with background images */
  display: inline-block;
  overflow: hidden;
  text-indent: 100%;
  white-space: nowrap;
}

ul {
  list-style: none;
}

.hotspot {
  position: absolute;
  display: block;
}

.hotspot--title {
  display: inline-block;
  padding-right: 10px;
  color: #ff0000;
  text-transform: uppercase;
  line-height: 50px;
  font-size: 12px;
  letter-spacing: 1px;
  transition: all cubic-bezier(0.8, 0, 0.2, 1) 0.4s;
}

.hotspot--title__right {
  float: right;
  padding-right: 0;
  padding-left: 10px;
}

.hotspot--cta {
  position: relative;
  // float: right;
  display: inline-block;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #ff0000;
  transition: all cubic-bezier(0.8, 0, 0.2, 1) 0.4s;
}

.hotspot--cta::after {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  right: 0;
  margin: auto;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #fff;
  z-index: 2;
  transition: opacity 0.6s;
  animation: pulse 1.5s cubic-bezier(0.8, 0, 0.2, 1) 0s infinite;
}

.hotspot:hover .hotspot--cta {
  transform: scale(0.6);
}

.hotspot:hover .hotspot--cta:after {
  opacity: 0;
}

@keyframes pulse {
  0% {
    transform: scale(0.4);
  }

  33% {
    transform: scale(1);
  }

  66% {
    transform: scale(0.4);
  }

  100% {
    transform: scale(0.4);
  }
}

.hotspot--iphone {
  top: 62%;
  right: 68%;
}

.hotspot--macbook {
  top: 22%;
  right: 48%;
}

.hotspot--watch {
  top: 72%;
  left: 45%;
}

@media screen and (max-width: 640px) {
  .hotspot--title {
    line-height: 40px;
    font-size: 10px;
  }

  .hotspot--cta {
    width: 40px;
    height: 40px;
  }
}

@media screen and (max-width: 420px) {
  .hotspot--title {
    line-height: 30px;
    font-size: 9px;
  }

  .hotspot--cta {
    width: 30px;
    height: 30px;
  }
}

@media screen and (max-width: 320px) {
  .hotspot--title {
    display: none;
  }

  .hotspot--cta {
    width: 20px;
    height: 20px;
  }

  .hotspot--cta::after {
    width: 5px;
    height: 5px;
  }
}

.cd-product {
  text-align: center;
}

.cd-product-wrapper {
  display: inline-block;
  position: relative;
  margin: 0 auto;
  width: 90%;
  max-width: 800px;
}

.cd-product-wrapper > img {
  display: block;
}

.cd-single-point {
  position: absolute;
  border-radius: 50%;
  width: 25px;
  height: 25px;
}

.cd-single-point > a {
  cursor: move;
  position: relative;
  z-index: 2;
  display: block;
  width: 25px;
  height: 25px;
  border-radius: inherit;
  background: #d95353;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  transition: background-color 0.2s;
}

.cd-single-point > a::after,
.cd-single-point > a:before {
  /* rotating plus icon */
  content: "";
  position: absolute;
  left: 50%;
  top: 50%;
  bottom: auto;
  right: auto;
  -webkit-transform: translateX(-50%) translateY(-50%);
  -moz-transform: translateX(-50%) translateY(-50%);
  -ms-transform: translateX(-50%) translateY(-50%);
  -o-transform: translateX(-50%) translateY(-50%);
  transform: translateX(-50%) translateY(-50%);
  background-color: #ffffff;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.2s;
  -moz-transition-duration: 0.2s;
  transition-duration: 0.2s;
}

.cd-single-point > a::after {
  height: 2px;
  width: 12px;
}

.cd-single-point > a::before {
  height: 12px;
  width: 2px;
}

// .cd-single-point::after {
//   /* this is used to create the pulse animation */
//   content: "";
//   position: absolute;
//   z-index: 1;
//   width: 100%;
//   height: 100%;
//   top: 0;
//   left: 0;
//   border-radius: inherit;
//   background-color: transparent;
//   -webkit-animation: cd-pulse 2s infinite;
//   -moz-animation: cd-pulse 2s infinite;
//   animation: cd-pulse 2s infinite;
// }
.hotspot-default-position {
  // left: 50%;
  // bottom: 50%;
  left: 5%;
  top: 5%; 
}
// .cd-single-point.hotspot-1 {
//   bottom: 54%;
//   right: 23%;
// }

// .cd-single-point.hotspot-2 {
//   bottom: 45%;
//   right: 38%;
// }

// .cd-single-point:nth-of-type(3) {
//   top: 47%;
//   left: 44%;
// }

// .cd-single-point:nth-of-type(4) {
//   top: 80%;
//   right: 25%;
// }

.cd-single-point.is-open > a {
  background-color: #191e47;
}

.cd-single-point.is-open > a::after,
.cd-single-point.is-open > a::before {
  -webkit-transform: translateX(-50%) translateY(-50%) rotate(135deg);
  -moz-transform: translateX(-50%) translateY(-50%) rotate(135deg);
  -ms-transform: translateX(-50%) translateY(-50%) rotate(135deg);
  -o-transform: translateX(-50%) translateY(-50%) rotate(135deg);
  transform: translateX(-50%) translateY(-50%) rotate(135deg);
}

.cd-single-point.is-open::after {
  /* remove pulse effect */
  display: none;
}

.cd-single-point.is-open .cd-more-info {
  visibility: visible;
  opacity: 1;
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  -ms-transform: scale(1);
  -o-transform: scale(1);
  transform: scale(1);
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0s,
    -webkit-transform 0.3s 0s, top 0.3s 0s, bottom 0.3s 0s, left 0.3s 0s,
    right 0.3s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0s, -moz-transform 0.3s 0s,
    top 0.3s 0s, bottom 0.3s 0s, left 0.3s 0s, right 0.3s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0s, transform 0.3s 0s, top 0.3s 0s,
    bottom 0.3s 0s, left 0.3s 0s, right 0.3s 0s;
}

/* .cd-single-point.visited > a {
  background-color: #475f74;
  background-color: #fff;
}
.cd-single-point.visited > a::after,
.cd-single-point.visited > a::before {
  background-color: #191e47;
} */
.cd-single-point.visited::after {
  /* pulse effect no more active on visited elements */
  display: none;
}

@media only screen and (min-width: 600px) {
  .cd-single-point.is-open .cd-more-info.cd-left {
    right: 140%;
  }

  .cd-single-point.is-open .cd-more-info.cd-right {
    left: 140%;
  }

  .cd-single-point.is-open .cd-more-info.cd-top {
    bottom: 140%;
  }

  .cd-single-point.is-open .cd-more-info.cd-bottom {
    top: 140%;
  }
}

@-webkit-keyframes cd-pulse {
  0% {
    -webkit-transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  100% {
    -webkit-transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
}

@-moz-keyframes cd-pulse {
  0% {
    -moz-transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  100% {
    -moz-transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
}

@keyframes cd-pulse {
  0% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }

  100% {
    -webkit-transform: scale(1.6);
    -moz-transform: scale(1.6);
    -ms-transform: scale(1.6);
    -o-transform: scale(1.6);
    transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
}

.cd-single-point .cd-more-info {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 3;
  width: 100%;
  height: 100%;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  text-align: left;
  line-height: 1.5;
  background-color: rgba(255, 255, 255, 0.95);
  padding: 2em 1em 1em;
  visibility: hidden;
  opacity: 0;
  -webkit-transform: scale(0.8);
  -moz-transform: scale(0.8);
  -ms-transform: scale(0.8);
  -o-transform: scale(0.8);
  transform: scale(0.8);
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s,
    -webkit-transform 0.3s 0s, top 0.3s 0s, bottom 0.3s 0s, left 0.3s 0s,
    right 0.3s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s, -moz-transform 0.3s 0s,
    top 0.3s 0s, bottom 0.3s 0s, left 0.3s 0s, right 0.3s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0.3s, transform 0.3s 0s,
    top 0.3s 0s, bottom 0.3s 0s, left 0.3s 0s, right 0.3s 0s;
}

.cd-single-point .cd-more-info::before {
  /* triangle next to the interest point description - hidden on mobile */
  content: "";
  position: absolute;
  height: 0;
  width: 0;
  display: none;
  border: 8px solid transparent;
}

.cd-single-point .cd-more-info h2 {
  font-size: 22px;
  font-size: 1.375rem;
  margin-bottom: 0.6em;
}

.cd-single-point .cd-more-info p {
  color: #758eb1;
}

@media only screen and (min-width: 600px) {
  .cd-single-point .cd-more-info {
    position: absolute;
    width: 430px;
    height: 260px;
    padding: 1em;
    overflow-y: visible;
    line-height: 1.4;
    border-radius: 0.25em;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
  }

  .cd-single-point .cd-more-info::before {
    display: block;
  }

  .cd-single-point .cd-more-info.cd-left,
  .cd-single-point .cd-more-info.cd-right {
    top: 50%;
    bottom: auto;
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    transform: translateY(-50%);
  }

  .cd-single-point .cd-more-info.cd-left::before,
  .cd-single-point .cd-more-info.cd-right::before {
    top: 50%;
    bottom: auto;
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    transform: translateY(-50%);
  }

  .cd-single-point .cd-more-info.cd-left {
    right: 160%;
    left: auto;
  }

  .cd-single-point .cd-more-info.cd-left::before {
    border-left-color: rgba(255, 255, 255, 0.95);
    left: 100%;
  }

  .cd-single-point .cd-more-info.cd-right {
    left: 160%;
  }

  .cd-single-point .cd-more-info.cd-right::before {
    border-right-color: rgba(255, 255, 255, 0.95);
    right: 100%;
  }

  .cd-single-point .cd-more-info.cd-top,
  .cd-single-point .cd-more-info.cd-bottom {
    left: 50%;
    right: auto;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%);
    transform: translateX(-50%);
  }

  .cd-single-point .cd-more-info.cd-top::before,
  .cd-single-point .cd-more-info.cd-bottom::before {
    left: 50%;
    right: auto;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%);
    transform: translateX(-50%);
  }

  .cd-single-point .cd-more-info.cd-top {
    bottom: 160%;
    top: auto;
  }

  .cd-single-point .cd-more-info.cd-top::before {
    border-top-color: rgba(255, 255, 255, 0.95);
    top: 100%;
  }

  .cd-single-point .cd-more-info.cd-bottom {
    top: 160%;
  }

  .cd-single-point .cd-more-info.cd-bottom::before {
    border-bottom-color: rgba(255, 255, 255, 0.95);
    bottom: 100%;
  }

  .cd-single-point .cd-more-info h2 {
    font-size: 20px;
    font-size: 1.25rem;
    margin-bottom: 0;
  }

  .cd-single-point .cd-more-info p {
    font-size: 14px;
    font-size: 0.875rem;
  }
}

/* close the interest point description - only on mobile */
.cd-close-info {
  position: fixed;
  top: 0;
  right: 0;
  height: 44px;
  width: 44px;
}

.cd-close-info::after,
.cd-close-info:before {
  content: "";
  position: absolute;
  left: 50%;
  top: 50%;
  bottom: auto;
  right: auto;
  -webkit-transform: translateX(-50%) translateY(-50%) rotate(45deg);
  -moz-transform: translateX(-50%) translateY(-50%) rotate(45deg);
  -ms-transform: translateX(-50%) translateY(-50%) rotate(45deg);
  -o-transform: translateX(-50%) translateY(-50%) rotate(45deg);
  transform: translateX(-50%) translateY(-50%) rotate(45deg);
  background-color: #475f74;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.2s;
  -moz-transition-duration: 0.2s;
  transition-duration: 0.2s;
}

.cd-close-info::after {
  height: 2px;
  width: 16px;
}

.cd-close-info::before {
  height: 16px;
  width: 2px;
}

@media only screen and (min-width: 600px) {
  .cd-close-info {
    display: none;
  }
}

#panorama {
  width: 100%;
  height: 400px;
}

.custom-hotspot {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #fbad18;
}

div.custom-tooltip span {
  visibility: hidden;
  position: absolute;
  border-radius: 3px;
  background-color: #fff;
  color: #000;
  text-align: center;
  max-width: 300px;
  min-width: 200px;
  padding: 5px 10px;
  margin-left: -220px;
  cursor: default;
  bottom: -50px;
  border: 1px solid #eee;
}

div.custom-tooltip:hover span {
  visibility: visible;
}
</style>