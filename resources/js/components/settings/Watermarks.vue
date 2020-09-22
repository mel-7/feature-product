<template>
  <v-row>
    <div class="col-12 col-md-6 px-5">
      <h3 class="font-weight-light mb-5">Watermarks</h3>
      <v-card>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Title</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in watermarks" :key="item.id">
                <td style="cursor:pointer;" @click="editWatermark(item.id)">{{ item.title }}</td>
                <td class="text-right">
                  <v-btn title="Edit" icon small @click="editWatermark(item.id)" color="blue">
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn
                    title="Delete"
                    icon
                    small
                    @click="actionFunction('delete', item)"
                    color="red"
                  >
                    <v-icon small>mdi-trash-can-outline</v-icon>
                  </v-btn>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
    </div>
    <v-dialog v-model="userFormDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <h4 class="pb-2">User Form</h4>
        </v-card-title>
        <v-card-text>
          <form>
            <v-text-field v-model="name" outlined label="Name" required class="py-0" dense></v-text-field>
            <v-text-field v-model="email" outlined label="Email" required class="py-0" dense></v-text-field>
            <v-text-field v-model="phone" outlined label="Phone" required class="py-0" dense></v-text-field>
            <!-- :hint="`${role.role}, ${role.value}`" -->
            <v-select
              v-model="role"
              :items="roleItems"
              item-text="role"
              item-value="value"
              label="Role"
              persistent-hint
              return-object
              outlined
              single-line
              required
              class="py-0"
              dense
            ></v-select>
            <div class="d-flex justify-end">
              <v-btn class="mr-1" text color="grey" @click="userFormDialog = false">cancel</v-btn>
              <v-btn class="primary" @click="saveUser('update')">Update</v-btn>
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
          <strong>{{dialogData.name}}</strong>'s account?
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
  props: {
    authUser: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      dialogData: [],
      userFormDialog: false,
      deleteDialog: false,
      watermarks: [],

      roleItems: [
        { role: "Team Admin", value: 3 },
        { role: "Team Editor", value: 4 },
      ],

      name: "",
      email: "",
      phone: "",
      role: {},
    };
  },
  methods: {
    editWatermark(id) {
      this.$router.push("/settings/watermark/edit/" + id);
    },
    actionFunction(action, value) {
      this.dialogData = [];
      this.dialogData = value;
      if (action == "edit") {
        this.userFormDialog = true;
        this.name = this.dialogData.name;
        this.email = this.dialogData.email;
        this.phone = this.dialogData.phone ? this.dialogData.phone : "";
        this.role = {
          role: this.dialogData.role == 3 ? "Admin" : "Editor",
          value: this.dialogData.role,
        };
      } else {
        this.deleteDialog = true;
      }
    },
    confirmDelete() {
    //   axios
    //     .post("/settings/team/delete/" + this.dialogData.id)
    //     .then((response) => {
    //       this.deleteDialog = false;
    //       this.dialogData = [];
    //       this.fetchWatermarks();
    //     })
    //     .catch((error) => {
    //       console.log("Error Deleting User");
    //       console.log(error);
    //     });
    },
    saveUser(action) {
      let route = "save";
      if (action == "update") {
        route = "update/" + this.dialogData.id;
      }
      let data = {
        name: this.name,
        phone: this.phone,
        role: this.role.value,
      };
      if (this.email != this.dialogData.email) {
        data.email = this.email;
      }
      // console.log(data);
      axios
        .post("/settings/team/" + route, data)
        .then((response) => {
          if (action == "update") {
            this.userFormDialog = false;
          }
          this.dialogData = [];
          this.fetchWatermarks();
        })
        .catch((error) => {
          console.log("Error Saving User");
          console.log(error);
        });
    },
    fetchWatermarks() {
      axios
        .get("/settings/watermarks/fetch")
        .then((response) => {
          this.watermarks = response.data;
        })
        .catch((error) => {
          console.log("Error Fetching Org Users");
          console.log("Error: " + error);
        });
    },
  },
  created() {
    this.fetchWatermarks();
  },
};
</script>

<style>
</style>
