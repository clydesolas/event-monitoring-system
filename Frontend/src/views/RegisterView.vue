
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
      <v-snackbar
            v-model="snackbar"
            :timeout="snackbarTimeout"
            top
            vertical
        >
            {{ snackbarMessage }}
            <template v-slot:actions>
        <v-btn
          color="yellow"
          variant="text"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
        </v-snackbar>

      <v-card class="mx-auto px-6 pt-8 bg-brown-darken-1  justify-center   overflow-auto" height="100%" min-width="344">
        <h5 class="text-white text-center pb-4">Register</h5>
        <v-form
          v-model="form"
          @submit.prevent="onSubmit"
        >
        <v-row class="justify-center">
              <v-col cols="12" sm="6">
                <v-text-field
                class="mb-2 font-weight-bold text-white"
                  label="Student Number*"
                  v-model="student_number"
                  @input="fetchStudentDetails"
                  persistent-hint
                  hint="Enter your student number"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
              <v-text-field
                  label="Course"
                  persistent-hint
                  hint="Generated upon entering student number."
                  v-model="course"
                  class="mb-2 font-weight-bold text-white"
                  readOnly
                ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="4">
                <v-text-field
                class="mb-2 font-weight-bold text-white"
                  label="First name*"
                  persistent-hint
                  hint="Generated upon entering student number."
                  v-model="first_name"
                  required
                  readOnly
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  label="Middle name"
                  v-model="middle_name"
                  persistent-hint
                  hint="Generated upon entering student number."
                  class="mb-2 font-weight-bold text-white"
                  readOnly
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  label="Last name*"
                  class="mb-2 font-weight-bold text-white"
                  persistent-hint
                  hint="Generated upon entering student number."
                  readOnly
                  v-model="last_name"
                  required
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
              >
                <v-text-field
                type="date"
                  label="Birthday*"
                  persistent-hint
                  hint="Enter your birthday"
                  v-model="birth_date"
                  class="mb-2 font-weight-bold text-white"
                  required
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
              >
                <v-select
                :items="['Male', 'Female']"
                  label="Sex*"
                  v-model="sex"
                  class="mb-2 font-weight-bold text-white"
                  required
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                    :label="isOTPSent ? 'OTP sent! Check your email.' : 'Email'"
                  v-model="email"
                  class="mb-2 font-weight-bold text-white"
                  persistent-hint
                  :hint="isOTPSent ? 'OTP sent! Check your email.' : 'Enter your email'"
                  required
                >
                 <template v-slot:append>
                    <v-btn
                    @click="generateOTP"
                    color="yellow-lighten-3"
                    class="white--text ma-0 w-100 h-100"
                    :disabled="!email"
                >
                    Send OTP
                </v-btn>
                </template>
            </v-text-field>
              </v-col>
            
              <v-col cols="12"  md="6">
                <v-text-field
                  :label="isOTPValid !== null ? (isOTPValid ? 'Valid OTP' : 'Invalid OTP') : 'OTP'"
                  v-model="enteredOTP"
                  maxLength="6"
                  class="pt-0 mt-0 font-weight-bold text-white w-100"
                  persistent-hint
                    messages="fsdfds"
                  hint="Enter the OTP received from your email"
                  :color="isOTPValid ?'green' : 'red'"
                  required
                >
            </v-text-field>
              </v-col>
              <v-col cols="12" sm="6" >
                <v-text-field
                  label="Password*"
                  class="mb-2 font-weight-bold text-white"
                  type="password"
                  minLength="8"
                  v-model="password"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  label="Confirm Password*"
                  class="mb-2 font-weight-bold text-white"
                  type="password"
                  minLength="8"
                  v-model="password_confirmation"
                  required
                ></v-text-field>
              </v-col>
              
              <v-col 
                cols="12"
                md="6"
                sm="12"
              >
              <v-btn
            :disabled="!form"
            :loading="loading"
            block
            color="yellow-lighten-2"
            size="large"
            type="submit"
            variant="elevated"
          >
            Register
          </v-btn>
              </v-col>

            </v-row>
            
            
          <div class="text-center pt-5 pb-3 mb-0">
            <RouterLink to="/" class="text-white">Already have an account.</RouterLink>
          </div>
        </v-form>
      </v-card>
  
  </v-container>
  </v-app>
  </template>
  
  <script>
    export default {
      data: () => ({
        snackbar: false,
        snackbarMessage: '',
        snackbarTimeout: 5000,
        form: false,
        email: null,
        password: null,
        password_confirmation: null,
        loading: false,
        student_number: null,
        first_name: null,
        middle_name: null,
        course: null,
        last_name: null,
        birth_date: null,
        sex: null,
        otp: null,
        enteredOTP: null,
        isOTPValid: null,
        isOTPSent: false,
        switchTheme :  { color: "text-brown", bg: "amber-lighten-5" } 

      }),
      watch: {
        enteredOTP(newOTP) {
        this.isOTPValid = this.validateOTP(newOTP);
        },
     },
      methods: {
          validateOTP(enteredOTP) {
        return enteredOTP === this.otp;
        },
            generateOTP() {
            this.otp = Math.floor(100000 + Math.random() * 900000).toString();

            this.$axios.post("http://127.0.0.1:8000/api/send-otp-email", {
                email: this.email,
                otp: this.otp,
            })
            .then(response => {
                console.log("OTP sent successfully", response.data);
                this.isOTPSent = true;  
              
            })
            .catch(error => {
                console.error("Failed to send OTP", error);
               
            });
        },
            onSubmit() {
      if (this.isOTPValid) {
            console.error("Invalid OTP");
            return;
        } else {
        console.error("Invalid OTP");
      }
    },
    async onSubmit() {
    if (!this.isOTPValid) {
        console.error("Invalid OTP");
        this.snackbarMessage = 'Invalid OTP';
        this.snackbar = true;
        return;
    }

    if (!this.otp || !this.student_number) {
        console.error("Invalid or empty OTP or student_number");
        this.snackbarMessage = 'Invalid or empty OTP or student number';
        this.snackbar = true;
        return;
    }

    try {
        await this.generateOTP();

        const response = await this.$axios.post(
            "http://127.0.0.1:8000/api/register",
            {
                student_number: this.student_number,
                first_name: this.first_name,
                middle_name: this.middle_name,
                last_name: this.last_name,
                course: this.course,
                birth_date: this.birth_date,
                sex: this.sex,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }
        );

        console.log("Registration successful", response.data);
        this.snackbarMessage = 'Registration successful';
        this.snackbar = true;
        this.$store.dispatch("setToken", response.data.token);
        this.$store.dispatch("setUser", response.data.user);
        this.$store.dispatch("setSwitchTheme", this.switchTheme);

    } catch (error) {
        console.error("Registration failed", error);
        this.snackbarMessage = 'Registration failed:'+ error.response.data.message;
        this.snackbar = true;
    }
},

        async fetchStudentDetails() {
      if (this.student_number) {
        try {
            console.log("Fetching student details...", this.student_number);
          const response = await this.$axios.get(
            `http://127.0.0.1:8000/api/students/${this.student_number}`
          );

          // Update other form fields with the fetched data
          this.first_name = response.data.first_name;
          this.middle_name = response.data.middle_name;
          this.last_name = response.data.last_name;
          this.course = response.data.course;
        } catch (error) {
          console.error("Error fetching student details", error);
        }
      }
    },
      },
    }
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