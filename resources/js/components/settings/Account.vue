<template>
  <v-row>
    <div class="col-12 col-md-6 px-5">
      <h3 class="font-weight-light mb-5">Account Settings</h3>
      <v-card>
        <v-card-text>
          <ValidationObserver ref="observer" >
          <form  method="post">
            <ValidationProvider v-slot="{ errors }" name="Name" rules="required|min:5">
            <v-text-field v-model="name" outlined label="Name"  :error-messages="errors" required class="py-0" dense></v-text-field>
            </ValidationProvider>
             <ValidationProvider v-slot="{ errors }" name="Phone" rules="required|min:10">
            <v-text-field
              v-model="phone"
              outlined
              label="Phone"
               :error-messages="errors" 
              required
              class="py-0" 
              dense
            ></v-text-field>
            </ValidationProvider>
            <div class="d-flex justify-end">
              <v-btn class="mr-1" text color="grey" @click="resetFields">reset</v-btn>
              <v-btn class="primary" @click="updateAccount">Update</v-btn>
            </div>
          </form>
          </ValidationObserver>
        </v-card-text>
      </v-card>
    </div>

    <div class="col-12 col-md-4 px-5">
      <h3 class="font-weight-light mb-5">Change Password</h3>
      <v-card>
        <v-card-text>
           <ValidationObserver ref="updatepass" >
          <v-form  v-model="valid" >
             <ValidationProvider v-slot="{ errors }" name="Password" rules="required|min:8">
                <v-text-field
                v-model="password"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :error-messages="errors"
                :type="show1 ? 'text' : 'password'" 
                outlined label="New Password" required class="py-0" dense 
                @click:append="show1 = !show1"
              ></v-text-field>
          
             </ValidationProvider>
              <ValidationProvider v-slot="{ errors }" name="Confirm Password" rules="required|min:8">
                 <v-text-field
                v-model="confirmpass"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :error-messages="passwordError=='' ? errors : passwordError"
                :type="show1 ? 'text' : 'password'" 
                outlined label="Confirm Password" required class="py-0" dense 
                @click:append="show1 = !show1"
              ></v-text-field> 
             </ValidationProvider> 

            <div class="d-flex justify-end"> 
              <v-btn class="primary" :disabled="!valid"   @click="updatePassword">Change Password</v-btn>
            </div>
          </v-form>
           </ValidationObserver>
        </v-card-text>
      </v-card>
    </div>
    
   <v-snackbar
      v-model="snackbar" 
      :color="color" 
      :right="x === 'right'"
      :timeout="timeout"
      :top="y === 'top'"
      :vertical="mode === 'vertical'"
    >
      {{ text }}  
    </v-snackbar>

  </v-row>
</template>

<script>
 import { required, name } from 'vee-validate/dist/rules';
import {  ValidationObserver, ValidationProvider } from  'vee-validate/dist/vee-validate.full';

export default {
  components: {
      ValidationProvider,
      ValidationObserver,
    },
  props: {
    authUser: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      valid: false,
      show1: false,
      passwordError: "",
      fetchedname: "",
      fetchedphone: "",  
      name: "",
      phone: "",
      password: "",
      confirmpass: "",
      baseUrl: window.location.origin,
      color: '',
      mode: 'vertical',
      snackbar: false,
      text: '',
      timeout: 5000,
      x: '',
      y: '',
    };
  },
   watch: {
    confirmpass: function (val) {
      if(this.password !== val){ 
        this.passwordError = "Password did not match!";
        this.valid = false;
      }else{ 
        this.valid = true;
         this.passwordError = "";
      }
    } 
  },
  methods: { 
    resetFields() {
     
      this.name = this.fetchedname;
      this.phone = this.fetchedphone;
    },

    postFunction(data, controller){ 
      axios
        .post(controller, data)
        .then((response) => {
         
            this.passwordError = ""; 
            this.snackbar = true;
            this.color = "success";
            this.x = "right";
            this.y = "top";
            this.text = "Success";
        })
        .catch((error) => {
          console.log("Error Fetching account");
          console.log(error);
        });
    },

    updatePassword(){
      this.$refs.updatepass.validate(); 
   
      let data = { 
        password: this.password      
      };
      if(this.password == null || this.password == "" || this.confirmpass == "" || this.confirmpass == null) { return false; }
 
      this.postFunction(data, "/settings/account/update_password");
     

    },
    updateAccount() {
     
       this.$refs.observer.validate()

      let data = { 
        name: this.name,
        phone: this.phone,
      };
      console.log(data);
      this.postFunction(data, "/settings/account/update");
       
    },
    fetchAccount() {
      axios
        .get("/settings/account/fetch")
        .then((response) => {
         
          this.fetchedname = response.data.name;
          this.fetchedphone = response.data.phone
            ? response.data.phone
            : "";
         
          this.name = response.data.name;
          this.phone = response.data.phone
            ? response.data.phone
            : "";
        })
        .catch((error) => {
          console.log("Error Fetching account");
          console.log(error);
        });
    },
  },
  created() {
    this.fetchAccount(); 
  },
};
</script>

<style>
</style>
