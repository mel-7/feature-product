<template>
  <v-card>
    <ValidationObserver ref="observer" v-slot="{  }">
      <v-card-title class="headline">Create a Scene</v-card-title>
      <form>
        <v-card-text>
          <ValidationProvider v-slot="{ errors }" name="Title" rules="required|max:50">
            <v-text-field
              v-model="title"
              :counter="50"
              :error-messages="errors"
              label="Title"
              required
            ></v-text-field>
          </ValidationProvider>
          <ValidationProvider v-slot="{ errors }" name="type" rules="required">
            <v-autocomplete
              v-model="sceneType"
              :items="items"
              :item-text="items.text"
              :item-value="items.value"
              :error-messages="errors"
              label="Select"
              data-vv-name="select"
              required
            ></v-autocomplete>
          </ValidationProvider>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-1" text @click="closeDialog">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="submit">Create</v-btn>
        </v-card-actions>
      </form>
    </ValidationObserver>
  </v-card>
</template>

<script>
import {
  ValidationProvider,
  ValidationObserver,
} from "vee-validate/dist/vee-validate.full";
export default {
  props: ["productId"],
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      title: "",
      sceneType: null,
      items: [
        {
          text: "360 Degree",
          value: "360-degree",
        },
        {
          text: "Panoramic",
          value: "panoramic",
        },
      ],
    };
  },
  methods: {
    closeDialog() {
      this.$emit("close", false);
    },
    submit() {
      this.$refs.observer.validate();
      let data = {
        title: this.title,
        type: this.sceneType,
        product: this.productId,
      };
      //console.log(data);
      axios
        .post("/builder/scene/store", data)
        .then((response) => {
          this.$emit("close", false);
          this.$emit("sceneCreated", false);
          //   console.log(response);
          //   this.successUI(response.data.message);
          //   this.originalSlug = this.slug;
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
  },
  mounted() {
    // console.log(typeof this.productId);
  },
};
</script>

<style>
</style>
