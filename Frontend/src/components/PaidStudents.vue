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
            <v-btn @click="openArchiveDialog(item)" elevation="0" class="" icon>
              <v-icon color="brown">mdi-archive</v-icon>
              <v-tooltip activator="parent" location="bottom">Archive?</v-tooltip>
            </v-btn>
          </td>
        </tr>
      </template>
    </v-data-table>

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
    console.log('Fetching trasactions for event ID:', this.event_id);
    const response = await axios.get(`http://127.0.0.1:8000/api/show-transaction/${this.event_id}`,
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
    async archiveTransaction() {
      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/update-transaction/${this.selectedStudent.id}`, {
          status: 'ARCHIVED',
        },
        {
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