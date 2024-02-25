<template>
  <v-data-table :items="totalEventsData" :headers="headers" dense height="350" max-height="500" class="overflow-y-auto" hide-default-footer>
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title style="font-size:15px">
          <RouterLink :class="['text-decoration-none', switchTheme.color]" to="/Events">
            <v-icon>mdi-chevron-down</v-icon>Transactions
          </RouterLink>
        </v-toolbar-title>
      </v-toolbar>
    </template>

    <template v-slot:item="{ item, index }">
      <tr @click="openDialog(item)" class="hoverable-row">
        <td>{{ index + 1 }}</td>
        <td>{{ item.title }}</td>
        <td>{{ item.duration_date }}</td>
        <td>{{ item.due_date }}</td>
        <td>{{ item.paid_status }}</td>
      </tr>
    </template>
    <template v-slot:bottom>
      <div class="text-center pt-2"></div>
    </template>
  </v-data-table>

  <v-dialog v-model="dialogVisible" max-width="600">
    <v-card class="event-dialog">
      <v-card-title class="headline">Event Details</v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-row>
          <v-col>
            <v-subheader class="font-weight-bold"></v-subheader>
            <p class="pb-2"><strong class="pr-1">Event Name: </strong>{{ selectedEvent.title }}</p>
            <p class="pb-2"><strong class="pr-1">Description: </strong>{{ selectedEvent.description }}</p>
            <p class="pb-2"><strong class="pr-16">Date:  </strong> {{ selectedEvent.date }}</p>
            <p class="pb-2"><strong class="pr-7">Duration:  </strong> {{ selectedEvent.duration_date }}</p>
            <p class="pb-2"><strong class="pr-16">Fee:  &nbsp;</strong> PHP {{ selectedEvent.payment }}</p>
            <p class="pb-2"><strong  class="pr-6">Due Date: </strong> {{ selectedEvent.due_date }}</p>
            <p class="pb-2"><strong class="pr-1">Academic Year: </strong>{{ selectedEvent.academic_year }}</p>
            <p class="pb-2"><strong class="pr-1">Semester: </strong>{{ selectedEvent.semester }}</p>
            <p class="pb-2"><strong class="pr-1">Status: </strong>{{ selectedEvent.paid_status }}</p>
          </v-col>
        </v-row>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn @click="closeDialog" color="primary">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';
import { format } from 'date-fns';

export default {
  data() {
    return {
      dialogVisible: false,
      selectedEvent: {},
      totalEvents: 0,
      totalEventsData: [],
      headers: [
        { title: 'No.', value: 'number' },
        { title: 'Event', value: 'title' },
        { title: 'Event Date', value: 'duration_date' },
        { title: 'Payment Due', value: 'due_date' },
        { title: 'Status', value: 'paid_status' },
      ],
    };
  },
  computed: {
    user() {
      return this.$store.getters.getUser;
    },
    switchTheme() {
      return this.$store.getters.getSwitchTheme;
    },
  },
  mounted() {
    // Fetch data from the API
    this.fetchTotalPaidAndNotPaid();
  },

  methods: {
    openDialog(event) {
      this.selectedEvent = event;
      this.dialogVisible = true;
    },
    closeDialog() {
      this.dialogVisible = false;
    },
    async fetchTotalPaidAndNotPaid() {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/upcoming-events-get-all', {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });

        if (!response.ok) {
          throw new Error('Failed to fetch data');
        }

        const data = await response.json();

        this.totalEventsData = data.map((item, index) => ({
          ...item,
          number: index + 1,
          due_date: format(new Date(item.due_date), "MMM. d, yyyy"),
        }));
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
  },
};
</script>

<style scoped>
.event-dialog {
  border-radius: 8px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.headline {
  font-size: 1.5rem;
}

.overflow-y-auto {
  overflow-y: auto;
}
.hoverable-row:hover {
  background-color: #f0f0f088;  
  cursor: pointer;
}
</style>
