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
    <v-data-table v-model:search="search" :headers="headers" :items="filteredTransactions">
      <template v-slot:item="{ item }">
        <tr>
          <td>{{ item.transaction_id }}</td>
          <td>{{ item.student_number }}</td>
          <td>{{ item.fullname }}</td>
          <td>{{ item.paid_by }}</td>
          <td>{{ item.relation }}</td>
          <td>{{ item.title }}</td>
          <td>{{ item.date }}</td>
        </tr>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      search: '',
      transactions: [],
    };
  },
  props: {
    modelValue: Object,
  },
  computed: {
    filteredTransactions() {
      const searchLower = this.search.toLowerCase();
      return this.transactions.filter(
        (transaction) =>
          transaction.transaction_id.toLowerCase().includes(searchLower) ||
          transaction.fullname.toLowerCase().includes(searchLower)
      );
    },
    headers() {
      return [
        { title: 'Transaction ID', value: 'transaction_id' },
        { title: 'Student Number', value: 'student_number' },
        { title: 'Full Name', value: 'fullname', sortable: true },
        { title: 'Paid by', value: 'paid_by', sortable: true },
        { title: 'Relation', value: 'relation', sortable: true },
        { title: 'Event', value: 'title', sortable: true },
        { title: 'Date', value: 'date', sortable: true },
      ];
    },
  },
  watch: {
    modelValue: {
      handler() {
        this.fetchTransactions();
      },
      deep: true,
    },
  },
  created() {
    // Fetch transactions initially
    this.fetchTransactions();
  },
  methods: {
    async fetchTransactions() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/transactions-display-report/${this.modelValue.selectedEvent}/${this.modelValue.selectedAcademicYear}/${this.modelValue.selectedSemester}`,
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        }
        );
        this.transactions = response.data;
      } catch (error) {
        console.error('Error fetching transactions:', error);
      }
    },
  },
};
</script>
