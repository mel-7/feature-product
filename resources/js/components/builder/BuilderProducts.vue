<template>
  <v-row>
    <v-col cols="9" class="px-5">
      <div class="d-flex align-center mb-5">
        <!-- to="/builder/product/new" -->
        <v-btn
          @click="newProductDialog = true"
          class="mr-3 text--primary"
          elevation="2"
          fab
          dark
          x-small
          color="white"
        >
          <v-icon dark>mdi-plus</v-icon>
        </v-btn>
        <h3 class="font-weight-light">Products</h3>
      </div>
      <v-card>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Product Name</th>
                <th class="text-left">Status</th>
                <th class="text-center">Embed</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in products" :key="item.name">
                <td>{{ item.title }}</td>
                <td
                  :class="`${item.status == 1 ? 'green--text' : 'blue--text'} text-left`"
                >{{ item.status == 1 ? 'active' : 'inactive' }}</td>
                <td class="text-center">
                  <v-btn
                    title="Embed"
                    text
                    small
                    color="blue-grey darken-2"
                    @click="openCode(item.slug, item.title)"
                  >get code</v-btn>
                </td>
                <td class="text-right">
                  <v-btn
                    title="Preview"
                    icon
                    small
                    :to="`/product/${item.slug}`"
                    target="_blank"
                    color="green"
                  >
                    <v-icon small>mdi-open-in-new</v-icon>
                  </v-btn>
                  <v-btn title="Edit" icon small @click="editProduct(item.id)" color="blue">
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn title="Delete" icon small @click="actionFn(item.id)" color="red">
                    <v-icon small>mdi-trash-can-outline</v-icon>
                  </v-btn>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
      <v-pagination
        v-if="pageCount > 1"
        class="mt-3"
        v-model="page"
        :length="pageCount"
        @input="onPageChange"
      ></v-pagination>
    </v-col>
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-card-title class="headline">Embed code</v-card-title>
        <v-card-text>
          <v-textarea ref="code" v-model="code" @click="selectCode"></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" text @click="dialog = false">Close</v-btn>
          <v-btn color="primary" text @click="selectCode">Copy Code</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="newProductDialog" width="500">
      <new-product @close="newProductDialog = false"></new-product>
    </v-dialog>
  </v-row>
</template>

<script>
import NewProduct from "./NewProduct";
export default {
  components: {
    NewProduct,
  },
  name: "Products",
  data() {
    return {
      newProductDialog: false,
      dialog: false,
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      products: [],
      code: "",
    };
  },
  methods: {
    openCode(slug, title) {
      this.dialog = true;
      this.code =
        '<iframe src="' +
        window.location.origin +
        "/product/" +
        slug +
        '" height="450px" width="100%" title="' +
        title +
        '" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
    },
    selectCode() {
      let theCode = this.$refs.code.$el.querySelector("textarea");
      theCode.select();
      document.execCommand("copy");
    },
    actionFn(i) {
      console.log(i);
    },
    editProduct(i) {
      this.$router.push("/builder/product/edit/" + i);
    },
    getProducts(p) {
      axios
        .get("/builder/products/all/?page=" + p)
        .then((response) => {
          this.products = response.data.data;
          this.page = response.data.current_page;
          this.pageCount = response.data.last_page;
        })
        .catch((error) => {
          console.log("Error: " + error);
        });
    },
    onPageChange() {
      this.getProducts(this.page);
    },
    saveProduct() {
      let data = {
        title: "auto draft",
        slug: "auto-draft",
      };
      axios
        .post("/builder/product/store", data)
        .then((response) => {
          this.valid = true;
          this.$router.push("/builder/product/edit/" + data.slug);
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
  },
  mounted() {
    this.getProducts(1);
  },
};
</script>

<style>
</style>
