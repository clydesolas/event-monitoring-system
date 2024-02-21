<template>
  <v-card
      flat
      title="Students List"
    >
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
          <td>{{ item.student_number }}</td>
          <td>{{ item.first_name }}</td>
          <td>{{ item.middle_name }}</td>
          <td>{{ item.last_name }}</td>
          <td>{{ item.course }}</td>
          
        </tr>
      </template>
    </v-data-table>
    <v-alert
    type="info"
    title="Note:"
    text="The students in this list may be duplicated, but with academic year and semester information. The system will automatically archive student records after 5 months, and they cannot be unarchived."
    variant="tonal"
  ></v-alert>
   

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
  
  computed: {
    filteredStudents() {
      const searchLower = this.search.toLowerCase();
      return this.students.filter(
        (student) =>
        student.student_number.toLowerCase().includes(searchLower) ||
          student.fullname.toLowerCase().includes(searchLower)||
          student.course.toLowerCase().includes(searchLower)||
          student.academic_year.toLowerCase().includes(searchLower)||
          student.semester.toLowerCase().includes(searchLower)
      );
    },
    headers() {
      return [
        { title: 'Student Number', value: 'student_number' },
        { title: 'First Name', value: 'first_name', sortable: true },
        { title: 'Middle Name', value: 'middle_name', sortable: true },
        { title: 'Last Name', value: 'last_name', sortable: true },
        { title: 'Course', value: 'course', sortable: true },
      ];
    },
  },
  watch: {
    event_id: 'fetchStudents', // Use watch to fetch students when event_id changes
  },
  created() {
    // Fetch students initially
    this.fetchStudents();

    // Set up interval to fetch students every 5 seconds
    this.intervalId = setInterval(this.fetchStudents, 15000);
  },
   beforeDestroy() {
    // Clear the interval when the component is destroyed
    clearInterval(this.intervalId);
  },
  methods: {
    async fetchStudents() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/students/`,
      {
        headers: {
          Authorization: `Bearer ${this.$store.getters.getToken}`,
        },
      }
      );
        this.students = response.data;
      } catch (error) {
        console.error('Error fetching students:', error);
      }
    },
    openArchiveDialog(student) {
      this.selectedStudent = student;
      this.archiveDialog = true;
    },
    closeArchiveDialog() {
      this.archiveDialog = false;
    },
   

    showSnackbar(message, color) {
      this.snackbar.message = message;
      this.snackbar.color = color;
      this.snackbar.show = true;
    },
  },
};
</script>