<template>
  <v-row>
    <v-col cols="12" class="px-5">
      <h3 class="font-weight-light mb-5">Team Settings</h3>
      <v-card>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Email</th>
                <!-- <th class="text-left">Status</th> -->
                <th class="text-left">Phone</th>
                <th class="text-left">Role</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in orgUsers" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.phone ? item.phone : "not set" }}</td>
                <td :class="`${item.role < 4 ? 'primary--text': ''}`">{{ printRole(item.role) }}</td>
                <!-- <td
                  :class="`${item.status == 1 ? 'green--text' : 'blue--text'} text-left`"
                >{{ item.status == 1 ? 'active' : 'inactive' }}</td>-->
                <td class="text-right">
                  <v-btn title="Edit" icon small @click="actionFunction('edit', item)" color="blue">
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
    </v-col>
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
      orgUsers: [], // Company Users

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
    printRole(role) {
      console.log(role);
      let roleLabel = "";
      if (role == 1) {
        roleLabel = "Super Admin";
      } else if (role == 2) {
        roleLabel = "App Admin";
      } else if (role == 3) {
        roleLabel = "Admin";
      } else {
        roleLabel = "Editor";
      }
      return roleLabel;
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
      axios
        .post("/settings/team/delete/" + this.dialogData.id)
        .then((response) => {
          this.deleteDialog = false;
          this.dialogData = [];
          this.getOrgUsers();
        })
        .catch((error) => {
          console.log("Error Deleting User");
          console.log(error);
        });
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
      if(this.email != this.dialogData.email){
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
          this.getOrgUsers();
        })
        .catch((error) => {
          console.log("Error Saving User");
          console.log(error);
        });
    },
    getOrgUsers() {
      axios
        .get("/settings/get-org-users/" + this.authUser.company_id)
        .then((response) => {
          this.orgUsers = response.data;
          // console.log(response);
        })
        .catch((error) => {
          console.log("Error Fetching Org Users");
          console.log("Error: " + error);
        });
    },
  },
  created() {
    this.getOrgUsers();
  },
};
</script>

<style>
</style>
