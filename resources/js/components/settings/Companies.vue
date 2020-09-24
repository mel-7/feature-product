<template>
  <v-row>
    <div class="col-12 col-md-6 px-5">
      <div class="d-flex align-center mb-5">
        <v-btn
          @click="companykDialog = true"
          class="mr-3 text--primary"
          elevation="2"
          fab
          dark
          x-small
          color="white"
        >
          <v-icon dark>mdi-plus</v-icon>
        </v-btn>
        <h3 class="font-weight-light">Companies</h3>
      </div>
      <v-card :loading="loading">
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Title</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in companies" :key="item.id">
                <td style="cursor:pointer;" @click="editCompany(item.id)">{{ item.title }}</td>
                <td class="text-right">
                  <v-btn title="Edit" icon small @click="editCompany(item.id)" color="blue">
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn title="Delete" icon small @click="deleteCompany(item)" color="red">
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
    </div>
    <v-dialog v-model="companykDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <h4 class="pb-2">Add Company</h4>
        </v-card-title>
        <v-card-text>
          <form>
            <v-text-field v-model="title" outlined label="Name" required dense></v-text-field>
            <v-textarea v-model="description" outlined label="Description" required dense></v-textarea>
            <div class="d-flex justify-end">
              <v-btn class="mr-1" text color="grey" @click="companykDialog = false">cancel</v-btn>
              <v-btn class="primary" @click="addWatermark">Update</v-btn>
            </div>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteDialog" max-width="300px">
      <v-card>
        <v-card-title class="subtitle-1">Confirm delete</v-card-title>
        <v-card-text>
          Are you sure you want to delete
          <strong>{{dialogData.title}}</strong> company?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" text @click="deleteDialog = false">cancel</v-btn>
          <v-btn color="primary" text @click="confirmDelete">Confirm</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      authUser: this.$authUser,
      dialogData: [],
      companykDialog: false,
      deleteDialog: false,
      companies: [],
      title: "",
      description: "",

      loading: false,
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
    };
  },
  watch: {
    $route(to, from) {
      this.fetchCompanies(this.$route.params.page);
    },
  },
  methods: {
    addWatermark() {
      let data = {
        title: this.title,
      };
      axios
        .post("/settings/watermark/add", data)
        .then((response) => {
          this.$router.push("/settings/watermark/edit/" + response.data.id);
        })
        .catch((error) => {
          console.log("Error adding Watermark");
          console.log(error);
        });
    },
    editCompany(id) {
      this.$router.push("/settings/watermark/edit/" + id);
    },
    deleteCompany(w) {
      this.dialogData = [];
      this.dialogData = w;
      this.deleteDialog = true;
    },
    confirmDelete() {
      axios
        .post("/settings/watermark/delete/" + this.dialogData.id)
        .then((response) => {
          this.deleteDialog = false;
          this.fetchCompanies(this.page);
        })
        .catch((error) => {
          this.deleteDialog = false;
          console.log("Error deleting Company");
          console.log(error);
        });
    },
    onPageChange() {
      this.$router
        .push("/settings/companies/page/" + this.page)
        .catch((err) => {});
    },
    fetchCompanies(p) {
      this.loading = true;
      axios
        .get("/settings/companies/fetch/?page=" + p)
        .then((response) => {
          this.loading = false;
          this.page = response.data.current_page;
          this.pageCount = response.data.last_page;
          this.companies = response.data.data;
          console.log(this.companies);
        })
        .catch((error) => {
          this.loading = false;
          console.log("Error Fetching Companies");
          console.log("Error: " + error);
        });
    },
  },
  created() {
    if (this.$route.params.page) {
      this.fetchCompanies(this.$route.params.page);
    } else {
      this.fetchCompanies(1);
    }
  },
};
</script>

<style>
</style>
