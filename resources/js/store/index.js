import Vue from "vue";
import Vuex from "vuex";
import watermarks from "./modules/watermarks"
Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        watermarks
    }
})