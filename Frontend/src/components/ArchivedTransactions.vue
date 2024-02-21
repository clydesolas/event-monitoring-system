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
    
    <v-data-table v-model:search="search" :headers="headers" :items="filteredtransactions">
      <template v-slot:item="{ item }">
        <tr>
          <td>{{ item.transaction_id }}</td>
          <td>{{ item.event_id }}</td>
          <td>{{ item.student_number }}</td>
          <td>{{ item.fullname }}</td>
          <td>{{ item.title }}</td>
          <td>{{ item.academic_year }}</td>
          <td>{{ item.semester }}</td>
          <td>{{ new Date(item.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true }) }}</td>
          <!-- Add other fields as needed -->
          <td>
            <v-btn @click="openArchiveDialog(item)" elevation="0" class="" icon>
              <v-icon color="grey">mdi-archive</v-icon>
              <v-tooltip activator="parent" location="bottom">Unarchive?</v-tooltip>
            </v-btn>
          </td>
        </tr>
      </template>
    </v-data-table>
  
    <!-- Archive Dialog -->
    <v-dialog v-model="archiveDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Undo Archive</v-card-title>
        <v-card-text>
          Are you sure you want to unarchive this transaction? 
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
      transactions: [],
      archiveDialog: false,
      selectedtransaction: null,
      snackbar: {
        show: false,
        message: '',
        color: '',
      },
    };
  },
  props: {
    title: String,
    transaction_id: String,
  },
  computed: {
    filteredtransactions() {
      const searchLower = this.search.toLowerCase();
      return this.transactions.filter(
        (transaction) =>
          transaction.transaction_id.toLowerCase().includes(searchLower) ||
          transaction.title.toLowerCase().includes(searchLower) ||
          transaction.academic_year.toLowerCase().includes(searchLower) ||
          transaction.semester.toLowerCase().includes(searchLower) ||
          transaction.date.toLowerCase().includes(searchLower) ||
          transaction.created_at.toLowerCase().includes(searchLower) ||
          transaction.duration_date.toLowerCase().includes(searchLower) ||
          transaction.due_date.toLowerCase().includes(searchLower) 

          
      );
    },
    headers() {
      return [
        { title: 'Transaction ID', value: 'transaction_id' },
        { title: 'Event ID', value: 'event_id' },
        { title: 'Title', value: 'title', sortable: true },
        { title: 'Student Number', value: 'student_number', sortable: true },
        { title: 'Student Name', value: 'fullname', sortable: true },
        { title: 'Academic Year', value: 'academic_year', sortable: true },
        { title: 'Semester', value: 'semester', sortable: true },
        { title: 'Date Added', value: 'created_at', sortable: true },
        { title: 'Action', value: 'action', sortable: false },

      ];
    },
  },
  watch: {
    transaction_id: 'fetchtransactions', // Use watch to fetch transactions when transaction_id changes
  },
  created() {
    // Fetch transactions initially
    this.fetchtransactions();

    // Set up interval to fetch transactions every 5 seconds
    this.intervalId = setInterval(this.fetchtransactions, 5000);
  },
  destroyed() {
    // Clear the interval when the component is destroyed
    clearInterval(this.intervalId);
  },
     methods: {
  async fetchtransactions() {
    try {
      const response = await axios.get(
        'http://127.0.0.1:8000/api/get-archived-transactions',
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        }
      );

      // Update the transactions array by transforming the data
      this.transactions = response.data.map(transaction => {
        return {
          ...transaction,
          course: `${transaction.course}`,
        };
      });
    } catch (error) {
      console.error('Error fetching transactions:', error);
    }
  },
    openArchiveDialog(transaction) {
      this.selectedtransaction = transaction;
      this.archiveDialog = true;
    },
    closeArchiveDialog() {
      this.archiveDialog = false;
    },
    async archiveTransaction() {
      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/update-transaction/${this.selectedtransaction.id}`, {
          status: 'ACTIVE',
        }, {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });

        console.log(response.data);

        // Show success Snackbar
        this.showSnackbar('transaction unarchived successfully', 'success');
      } catch (error) {
        console.error('Error archiving transaction:', error);

        // Show error Snackbar
        this.showSnackbar('Error unarchiving transaction', 'error');
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