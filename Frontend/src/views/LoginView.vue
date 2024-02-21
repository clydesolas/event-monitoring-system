
  <template>
      <v-app class="app-background">
      <v-container class="login-main">
        <v-app-bar class="orig-brown" :elevation="2">
          <template v-slot:prepend>
          <v-avatar>
            <v-img src="public/logo.jpg"></v-img>
          </v-avatar>  
          </template>
            <v-app-bar-title class="text-white">
              <div class="pt-3" style="line-height: .5;">
                <span><b>Event Monitoring System</b></span>
                <br>
                <span class="text-subtitle-1">Colegio de Porta Vaga</span>
              </div>
            </v-app-bar-title>
        </v-app-bar>
    
  
        <v-card class="mx-auto my-10 px-6 pt-8  justify-center glass" min-width="344">
          <h5 class="text-white text-center pb-4">Login</h5>
          <v-form
            v-model="form"
            @submit.prevent="onSubmit"
          >
            <v-text-field
              v-model="email"
              :readonly="loading"
              :rules="[required]"
              class="mb-2 font-weight-bold text-white"
              clearable
              label="Email"
            ></v-text-field>
    
            <v-text-field
              v-model="password"
              :readonly="loading"
              type="password"
              :rules="[required]"
              clearable
              class="font-weight-bold  text-white"
              label="Password"
              placeholder="Enter your password"
            ></v-text-field>
    
            <br>
    
            <v-btn
              :disabled="!form"
              :loading="loading"
              block
              color="brown"
            
              size="large"
              type="submit"
              variant="elevated"
            >
              Sign In
            </v-btn>
            <div class="text-center pt-5 pb-3 mb-0">
              <RouterLink to="/Register" class="text-white">Create an account.</RouterLink>
            </div>
          </v-form>
        </v-card>
    
    </v-container>
    </v-app>
    </template>
    
    <script>
    export default {
      data: () => ({
        form: false,
        email: null,
        password: null,
        loading: false,
        switchTheme: { color: "text-brown", bg: "amber-lighten-5" }
      }),
    
      methods: {
        // Your existing methods
    
        async archiveStudents() {
          try {
            // Call the API to archive students
            const response = await this.$axios.post(
              "http://127.0.0.1:8000/api/archive-students"
            );
    
            console.log(response.data);  
          } catch (error) {
            console.error("Error archiving students:", error);
          }
        },
    
        async onSubmit() {
          // Your existing code for form submission
          this.loading = true;
    
          try {
            const response = await this.$axios.post(
              "http://127.0.0.1:8000/api/login",
              {
                email: this.email,
                password: this.password
              }
            );
            this.$store.dispatch("setToken", response.data.token);
            this.$store.dispatch("setUser", response.data.user);
            this.$store.dispatch("setSwitchTheme", this.switchTheme);
            this.$router.push({ name: "DashboardView" });
          } catch (error) {
            console.error("Login failed", error);
          }
    
          this.loading = false;
        },
    
        required(v) {
          return !!v || "Field is required";
        }
      },
    
      created() {
        // Call the method to archive students when the component is created
        this.archiveStudents();
      }
    };
    </script>
    
  

  <style scoped>
    .app-background {
    margin-top: 60px;
    height: calc(100vh - 60px);
    display: flex;
    overflow-y: hidden;
    overflow: hidden;
    justify-content: space-around;
    align-items: center;
    background-image: url('public/background.jpg');
    background-size: cover;
    background-repeat: no-repeat; 
    background-position: center;

    /* Dark overlay */
    &::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(36, 27, 11, 0.3); /* Adjust the alpha value to control darkness */
    }
  }

  </style>