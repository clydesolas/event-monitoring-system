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
          <td>{{ item.student_number }}</td>
          <td>{{ item.fullname }}</td>
          <td>{{ item.course }}</td>
          <td>{{ item.academic_year }}</td>
          <td>{{ item.semester }}</td>
          <td>{{ new Date(item.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true }) }}</td>
          <!-- Add other fields as needed -->
          <td>
           
          </td>
        </tr>
      </template>
    </v-data-table>
    <v-alert
    type="info"
    title="Note:"
    text="The system will automatically archived student records after 5 months and it cannot be unarchived. "
    variant="tonal"
  ></v-alert>
    <!-- Archive Dialog -->
    <v-dialog v-model="archiveDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirm Archive</v-card-title>
        <v-card-text>
          Are you sure you want to archive this transaction?
        </v-card-text>
        <v-card-actions>
          <v-btn @click="archiveTransaction" color="primary" text>
            Confirm
          </v-btn>
          <v-btn @click="closeArchiveDialog" color="red" text>
            Cancel
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

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
          student.fullname.toLowerCase().includes(searchLower)||
          student.course.toLowerCase().includes(searchLower)||
          student.academic_year.toLowerCase().includes(searchLower)||
          student.semester.toLowerCase().includes(searchLower)
      );
    },
    headers() {
      return [
        { title: 'Student Number', value: 'student_number' },
        { title: 'Full Name', value: 'fname', sortable: true },
        { title: 'Course', value: 'course', sortable: true },
        { title: 'Academic Year', value: 'academic_year', sortable: true },
        { title: 'Semester', value: 'semester', sortable: true },
        { title: 'Date Added', value: 'created_at', sortable: true },

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
    this.intervalId = setInterval(this.fetchStudents, 5000);
  },
  destroyed() {
    // Clear the interval when the component is destroyed
    clearInterval(this.intervalId);
  },
     methods: {
  async fetchStudents() {
    try {
      const response = await axios.get(
        'http://127.0.0.1:8000/api/get-archived-students',
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        }
      );

      // Update the students array by transforming the data
      this.students = response.data.map(student => {
        return {
          ...student,
          fullname: `${student.first_name} ${student.last_name}`,
          course: `${student.course}`,
        };
      });
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
    async archiveTransaction() {
      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/update-transaction/${this.selectedStudent.id}`, {
          status: 'ARCHIVED',
        headers: {
          Authorization: `Bearer ${this.$store.getters.getToken}`,
        },
        });
        console.log(response.data);

        // Show success Snackbar
        this.showSnackbar('Transaction archived successfully', 'success');
      } catch (error) {
        console.error('Error archiving transaction:', error);

        // Show error Snackbar
        this.showSnackbar('Error archiving transaction', 'error');
      } finally {
        this.closeArchiveDialog();
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