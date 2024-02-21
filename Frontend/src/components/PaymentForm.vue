<template>
  <div>
    <!-- First Section -->
    <v-card>
      <v-card-title>
        <span>Payment Form</span>
      </v-card-title>
      <v-card-text>
        <small>*indicates required field</small>
        <v-container>
          <v-row>
            <v-col cols="12" sm="6" md="6" class="py-0 my-0">
              <v-text-field v-model="studentNumber" label="Student Number*" @input="fetchStudentInfo" required></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="6" class="py-0 my-0">
              <v-text-field v-model="paidBy" label="Paid By*" required></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="6" class="py-0 my-0">
              <v-select v-model="relation" :items="['Self', 'Parent', 'Guardian', 'Sibling', 'Relative', 'Friend']" label="Relationship with Student*" required></v-select>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>

    <!-- Second Section -->
    <v-card>
      <v-card-title class="d-flex justify-center">
        <span class=" text-center" style="font-size: 15px !important;">Transaction Details</span>
      </v-card-title>
      <v-card-text>
        <v-container class="py-0 my-0 ml-0 pl-0 mt-1">
          <v-row  >
            <v-col cols="12" sm="12" class="py-0 my-0">
              <v-row class="label-value">
                <v-col class="label" cols="6"><span>Student Full Name:</span></v-col>
                <v-col class="value  text-no-wrap" cols="6"><span :class="studentFullName !== 'Student does not exist' ? 'text-green-darken-3' : 'text-red-lighten-1'">{{  studentFullName  }}</span></v-col>
              </v-row>
            </v-col>
            <v-col cols="12" sm="12" class="py-0 my-0">
              <v-row class="label-value">
                <v-col class="label" cols="6"><span>Academic Year:</span></v-col>
                <v-col class="value" cols="6">{{ academic_year }}</v-col>
              </v-row>
            </v-col>
            <v-col cols="12" sm="12" class="py-0 my-0">
              <v-row class="label-value">
                <v-col class="label" cols="6"><span>Semester:</span></v-col>
                <v-col class="value" cols="6"> {{ semester }}</v-col>
              </v-row>
            </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Event Title:</span></v-col>
                  <v-col class="value text-truncate" cols="6"><span >{{ event_title }}</span></v-col>
                </v-row>
                <v-tooltip activator="parent" location="bottom"> {{ event_title }}</v-tooltip>
              </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Event Date:</span></v-col>
                  <v-col class="value" cols="6"><span>{{ event_date }}</span></v-col>
                </v-row>
              </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Payment Price:</span></v-col>
                  <v-col class="value" cols="6"><span>PHP {{ payment }}</span></v-col>
                </v-row>
                </v-col>
            <v-col cols="12" sm="12" class="py-0 my-0">
              <v-row class="label-value">
                <v-col class="label" cols="6"><span>Status:</span></v-col>
                <v-col class="value" cols="6"><span :class="isPaid ? 'text-green-darken-1' : 'text-blue-darken-5'">{{ isPaid ? 'Already Paid' : 'Pending' }}</span></v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn  variant="text" @click="clearForm">Clear</v-btn>
        <v-btn color="green-darken-1" variant="text" :disabled="isPaid" @click="savePayment">Submit</v-btn>
      </v-card-actions>
    </v-card>
      <!-- Snackbar -->
 <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line>
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar.show = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>

</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      studentNumber: '',
      relation: '',
      paidBy: '',
      studentFullName: '',
      isPaid: false,
      snackbar: {
        show: false,
        message: '',
        color: '',
      },
    };
  },
  props: {
    event_title: String,
    event_id: String,
    payment: String,
    event_date: String,
    academic_year: String,
    semester: String,
    duration_date: String,
  },
  methods: {
    
    async fetchStudentInfo() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/students/${this.studentNumber}`,
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
        const studentData = response.data;
        studentData != '' ? this.studentFullName = `${studentData.first_name} ${studentData.middle_name} ${studentData.last_name}` : this.studentFullName = 'Student does not exist';
        // Check if student is paid
        await this.checkPaymentStatus();
      } catch (error) {
        console.error('Error fetching student information:', error);
      }
    },
    async checkPaymentStatus() {
      try {
        const payload = {
          student_number: this.studentNumber,
          event_id: this.event_id,
        };

        const response = await axios.post('http://127.0.0.1:8000/api/check-transaction', payload,
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
        response.data.status == 'paid' ? this.isPaid = true : this.isPaid = false;
        console.log(this.isPaid);
      } catch (error) {
        console.error('Error checking payment status:', error);
      }
    },
    async savePayment() {
      try {
        // Prepare data for submission
        const transactionData = {
          title: this.event_title,
          event_id: this.event_id,
          payment: this.payment,
          date: this.event_date,
          duration_date: this.duration_date,  
          academic_year: this.academic_year,
          semester: this.semester,
          student_number: this.studentNumber,
          fullname: this.studentFullName,
          paid_by: this.paidBy,
          relation: this.relation,  
        };

        const response = await axios.post('http://127.0.0.1:8000/api/store-transaction', transactionData,
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
        console.log("success")
        // Display a success message using Snackbar
        this.showSnackbar('Transaction saved successfully', 'success');

        this.clearForm();
      } catch (error) {
        console.log("error")

        console.error('Error saving payment:', error);
        this.showSnackbar('Error saving payment. Please try again.', 'error');
      }
    },
    clearForm() {
      this.studentNumber = '';
      this.paidBy = '';
      this.studentFullName = '';
      this.isPaid = false;
    },
    showSnackbar(message, color) {
      this.snackbar.message = message;
      this.snackbar.color = color;
      this.snackbar.show = true;
    },
  },
};
</script>

<style scoped>
.label-value {
  display: flex;
  justify-content: space-between;
}
.label {
  text-align: right;
  padding-right: 10px;
}
</style>

<style scoped>
.label-value {
  display: flex;
  justify-content: space-between;
}
.label {
  text-align: right;
  padding-right: 10px;
}
</style>
