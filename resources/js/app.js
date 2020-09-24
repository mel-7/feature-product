/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from "vue";
import vuetify from "./plugins/vuetify"; // path to vuetify export
import VueRouter from "vue-router";
import { routes } from "./plugins/routes";

// Vuex
// import store from "./store/index";

// Vue Router
Vue.use(VueRouter);
const router = new VueRouter({
    routes,
    mode: "history"
});

/**
 * Authenticated User Object
 */
let authMeta = document.querySelector("meta[name='auth_user']").getAttribute("content");
if(authMeta.length){
  Vue.prototype.$authUser = JSON.parse(authMeta);
}
// Vue.prototype.$authUser = JSON.parse(document.querySelector("meta[name='auth_user']").getAttribute('content'));
// Vue.prototype.$authUser = document.querySelector("meta[name='auth_user']").getAttribute('content');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "builder-navigation",
    require("./components/BuilderNavigation.vue").default
);
Vue.component("media-files", require("./components/MediaFiles.vue").default);

Vue.component("spritespin", {
    props: ["options"],
    template: '<div class="sp-container"></div>',
    data: function() {
        return {
            api: null,
            data: null
        };
    },
    mounted: function() {
        // create spritespin
        $(this.$el).spritespin(this.options);
        // access api object
        this.api = $(this.$el).spritespin("api");
        // access data object
        this.data = $(this.$el).spritespin("data");
        // watch changes and update spritespin
        this.$watch("options", (newVal, oldVal) => {
            $(this.$el).spritespin(newVal);
        });
    },
    updated: function() {
        // $(this.$el).spritespin(this.options)
    },
    beforeDestroy: function() {
        // destroy spritespin before Vue node is destroyed
        $(this.$el).spritespin("destroy");
    }
});

const app = new Vue({
    el: "#app",
    router,
    vuetify,
    // store,
    data() {
        return {
            //login
            loginValid: true,
            loginEmail: "",
            loginEmailrules: [
                value => !!value || "Required",
                value => /.+@.+\..+/.test(value) || "E-mail must be valid"
            ],
            loginPassword: "",
            loginPasswordrules: [
                value => !!value || "Required",
                value =>
                    (value && value.length > 8) ||
                    "Password must be atleast 8 characters"
            ]
        };
    },
    methods: {
        validate() {
            if (this.$refs.form.validate()) {
                this.snackbar = true;
            }
        },
        logout: function(event) {
            event.preventDefault();
            document.getElementById("logout-form").submit();
        }
    },
    mounted() {
      // console.log(typeof this.$authUser)
    },
});
