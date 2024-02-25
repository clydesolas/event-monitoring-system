<template>
    <v-card flat>
      <v-card-title class="d-flex align-center text-center pe-2 px-2 mx-0">
        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          density="compact"
          label="Search"
          single-line
          flat
          hide-details
          variant="solo-filled"
        ></v-text-field>
      </v-card-title>
  
      <v-divider></v-divider>
      <v-data-table v-model:search="search" :headers="headers" :items="filteredStudents">
        <template v-slot:item="{ item }">
          <tr>
            <td>{{ item.transaction_id }}</td>
            <td>{{ item.student_number }}</td>
            <td>{{ item.fullname }}</td>
            <td>{{ item.paid_by }}</td>
            <td>{{ item.relation }}</td>
            <!-- Add other fields as needed -->
            <td>
                <v-btn elevation="0" class="" icon @click="exportAsPdf(item.transaction_id)">
                <v-icon color="red">mdi-file-pdf-box</v-icon>
                <v-tooltip activator="parent" location="bottom">Export as PDF?</v-tooltip>
                </v-btn>
            </td>
          </tr>
        </template>
      </v-data-table>
  
     
  
   <!-- Snackbar -->
   <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line>
        {{ snackbar.message }}
        <template v-slot:action="{ attrs }">
          <v-btn text v-bind="attrs" @click="snackbar.show = false">
            Close
          </v-btn>
        </template>
      </v-snackbar>
    </v-card>
  </template>
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        search: '',
        students: [],
        archiveDialog: false,
        selectedStudent: null,
        snackbar: {
          show: false,
          message: '',
          color: '',
        },
      };
    },
    props: {
      title: String,
      event_id: String,
    },
    computed: {
      filteredStudents() {
        const searchLower = this.search.toLowerCase();
        return this.students.filter(
          (student) =>
            student.student_number.toLowerCase().includes(searchLower) ||
            student.fullname.toLowerCase().includes(searchLower)
        );
      },
      headers() {
        return [
          { title: 'Transaction ID', value: 'transaction_id' },
          { title: 'Student Number', value: 'student_number' },
          { title: 'Full Name', value: 'fullname', sortable: true },
          { title: 'Paid by', value: 'paid_by', sortable: true },
          { title: 'Relation', value: 'relation', sortable: true },
          { title: 'Action', value: 'action', sortable: false },
        ];
      },
      user() {
        return this.$store.getters.getUser;
      },
      switchTheme(){
      return this.$store.getters.getSwitchTheme;
    },
    },
    watch: {
      event_id: 'fetchStudents', // Use watch to fetch students when event_id changes
    },
    created() {
      // Fetch students initially
      this.fetchStudents();
  
      // Set up interval to fetch students every 5 seconds
      this.intervalId = setInterval(this.fetchStudents, 5000);
    },
    destroyed() {
      // Clear the interval when the component is destroyed
      clearInterval(this.intervalId);
    },
    methods: {
      async fetchStudents() {
    try {
      console.log('Student number:', this.user.student_number);
      const response = await axios.get(`http://127.0.0.1:8000/api/show-student-transaction/${this.user.student_number}`,
      {
        headers: {
          Authorization: `Bearer ${this.$store.getters.getToken}`,
        },
      });
      console.log('Response:', response.data);
      this.students = response.data;
    } catch (error) {
      console.error('Error fetching transaction table:', error);
    }
  },
  
      openArchiveDialog(student) {
        this.selectedStudent = student;
        this.archiveDialog = true;
      },
      closeArchiveDialog() {
        this.archiveDialog = false;
      },
      async exportAsPdf(transactionId) {
      try {
        const response = await axios.post(
          'http://127.0.0.1:8000/api/student-export-pdf/',
          { transaction_id: transactionId },
          {
            headers: {
              Authorization: `Bearer ${this.$store.getters.getToken}`,
            },
            responseType: 'blob', // Set responseType to 'blob' for binary data (PDF)
          }
        );

        // Create a Blob from the response data
        const blob = new Blob([response.data], { type: 'application/pdf' });

        // Create a link element and trigger a download
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = `transaction_${transactionId}.pdf`;
        link.click();
      } catch (error) {
        console.error('Error exporting as PDF:', error);
        this.showSnackbar('Error exporting as PDF', 'error');
      }
    },
      showSnackbar(message, color) {
        this.snackbar.message = message;
        this.snackbar.color = color;
        this.snackbar.show = true;
      },
    },
  };
  </script>