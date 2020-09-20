<template>
  <div>
    <v-row class="px-3">
      <div class="col-12 pb-3 pa-0">
        <v-btn
          large
          :color="`${activateExterior == true ? 'yellow accent-4' : 'primary' }`"
          @click="selectPanel('exterior')"
        >Exterior</v-btn>
        <v-btn
          large
          :color="`${activateInterior == true ? 'yellow accent-4' : 'primary' }`"
          @click="selectPanel('interior')"
        >Interior</v-btn>
        <v-btn
          large
          :color="`${activateVideo == true ? 'yellow accent-4' : 'primary' }`"
          @click="selectPanel('video')"
        >Video</v-btn> 
        <v-btn class="float-right">Preview</v-btn>
      </div>
    </v-row>
    <v-divider></v-divider>
    <v-row>
      <div></div>
      <div class="col-12 col-md-3" v-show="activateVideo == false">
        <Hotspots
          :item="selected_item"
          :auth-user="authUser"
          :product="this.$route.params.id"
          :current-panel="selected_panel_prop"
          @emitHotspot="hotspotToSet"
          @emitInteriorHotspot="hotspotToInterior"
        />
      </div>
      <div class="col-12 col-md-9" v-if="activateExterior == true" cols="9">
        <exterior-panel
          :auth-user="authUser"
          :product="this.$route.params.id"
          :selected-hotspot-prop="selected_hotspot_prop"
          @selectedItem="theSelectedItem"
        />
      </div>
      <div class="col-12 col-md-9" v-if="activateInterior == true" cols="9">
        <interior-panel
          :auth-user="authUser"
          :product="this.$route.params.id"
          :selected-interior-hotspot="selected_interior_hotspot_prop"
        />
      </div>
      <div class="col-12 col-md-9" v-if="activateVideo == true">
        <video-panel :auth-user="authUser" :product="this.$route.params.id" />
      </div>
    </v-row>
  </div>
</template>

<script>
import Hotspots from "./edit/Hotspots";
import ExteriorPanel from "./edit/ExteriorPanel";
import InteriorPanel from "./edit/InteriorPanel";
import VideoPanel from "./edit/VideoPanel";
export default {
  props: {
    authUser: {
      type: Object,
      default: null,
    },
  },
  components: {
    Hotspots,
    ExteriorPanel,
    InteriorPanel,
    VideoPanel,
  },
  data() {
    return {
      selected_panel_prop: "exterior",
      activateExterior: true,
      activateInterior: false,
      activateVideo: false,

      selected_item: null,
      selected_hotspot_prop: null,

      selected_interior_hotspot_prop: null,
    };
  },
  methods: {
    theSelectedItem(v) {
      this.selected_item = v;
    },
    hotspotToInterior(v) {
      // Check if hotspot added are equal
      if (
        JSON.stringify(this.selected_interior_hotspot_prop) != JSON.stringify(v)
      ) {
        this.selected_interior_hotspot_prop = v;
      }
    },
    hotspotToSet(v) {
      // Check if hotspot added are equal
      if (JSON.stringify(this.selected_hotspot_prop) != JSON.stringify(v)) {
        this.selected_hotspot_prop = v;
      }
    },
    selectPanel(panel) {
      // Exterior
      if (panel == "exterior") {
        this.activateExterior = true;
        this.activateInterior = false;
        this.activateVideo = false;
        this.selected_panel_prop = "exterior";
      } else if (panel == "interior") {
        // Interior
        this.selected_panel_prop = "interior";
        this.activateInterior = true;
        this.activateExterior = false;
        this.activateVideo = false;
      } else {
        this.activateVideo = true;
        this.activateInterior = false;
        this.activateExterior = false;
      }
    },
  },
};
</script>
