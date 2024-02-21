<template>
    <v-data-table :items="totalEventsData" :headers="headers" dense height="310" max-height="500" class="overflow-y-auto" hide-default-footer>
      <template v-slot:top>
        <v-toolbar flat>
        
            <v-toolbar-title style="font-size:15px">
                <RouterLink :class="['text-decoration-none', switchTheme.color]" to="/Events"> 
                    <v-icon>mdi-chevron-down</v-icon>TOTAL EVENTS: {{ totalEvents }}
                </RouterLink>
            </v-toolbar-title>
        </v-toolbar>
      </template>
  
      <template v-slot:item="{ item, index }">
        <tr>
          <td>{{ index + 1 }}</td>
          <td>{{ item.title }}</td>
          <td class="text-amber-accent-4">{{ item.total_paid }}</td>
          <td class="text-yellow-darken-4">{{ item.total_not_paid }}</td>
          <td :style="{ color: 'brown' }">{{ item.total_students }}</td>
        </tr>
      </template>
      <template v-slot:bottom>
      <div class="text-center pt-2">
      </div>
    </template>
    </v-data-table>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        totalEvents: 0,
        totalEventsData: [],
        headers: [
          { title: 'No.', value: 'number' },
          { title: 'Event', value: 'title' },
          { title: 'Paid', value: 'total_paid' },
          { title: 'Not Paid', value: 'total_not_paid' },
          { title: 'Total Students', value: 'total_students' },
        ],
      };
    },
    computed: {
    user() {
      return this.$store.getters.getUser;
    },
    switchTheme(){
      return this.$store.getters.getSwitchTheme;
    }
  },
    mounted() {
      // Fetch data from the API
      this.fetchTotalPaidAndNotPaid();
    },

  methods: {
    async fetchTotalPaidAndNotPaid() {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/total-paid-and-not-paid', {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });

        if (!response.ok) {
          throw new Error('Failed to fetch data');
        }

        const data = await response.json();

        this.totalEvents = data.total_events;
        this.totalEventsData = data.total_count_event.map((item, index) => ({ ...item, number: index + 1 }));
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
},
  };
  </script>
  
  <style>
  /* Add your styles here */
  .overflow-y-auto {
    overflow-y: auto;
  }
  </style>
  