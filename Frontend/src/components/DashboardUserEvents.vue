<template>
    <v-card elevation="0">
      <v-card-title flat>
        <v-toolbar-title class="d-flex justify-end">
          <v-btn @click="updateChart">
            <v-icon>mdi-reload</v-icon>&nbsp; Events
          </v-btn>
        </v-toolbar-title>
      </v-card-title>
      <div>
        <apexchart width="500" type="bar" :options="chartOptions" :series="series"></apexchart>
      </div>
      <v-row  class="mb-4 w-50 ml-4 my-0 py-0">
        <v-col class="my-0 py-0">
          <v-avatar color="green" size="20" class="mr-2" style="background: #4CAF50; border: 1px solid #ccc;"></v-avatar>
          <span>Paid</span>
        </v-col>
        <v-col  class="my-0 py-0">
          <v-avatar color="red" size="20" class="mr-2" style="background: #FF5252; border: 1px solid #ccc;"></v-avatar>
          <span>Not Paid</span>
        </v-col>
      </v-row>
    </v-card>
  </template>
  
  <script>
  import axios from 'axios';
  import VueApexCharts from 'vue3-apexcharts';
  
  export default {
    components: {
      apexchart: VueApexCharts,
    },
    data() {
      return {
        chartOptions: {
          chart: {
            id: 'vuechart-example',
          },
          xaxis: {
            labels: {
              rotate: -45,
              hideOverflowingLabels: false,
            },
          },
        },
        series: [
          {
            name: 'Payment',
            data: [],
          },
        ],
      };
    },
    methods: {
      async fetchData() {
        try {
          const response = await axios.get('http://127.0.0.1:8000/api/check-events-get-all', {
            headers: {
              Authorization: `Bearer ${this.$store.getters.getToken}`,
            },
          });
  
          if (response.status !== 200) {
            throw new Error('Failed to fetch data');
          }
  
          const eventData = response.data;
  
          this.chartOptions.xaxis.labels.categories = eventData.map(item => item.title);
          this.series[0].data = eventData.map(item => ({
            x: item.title,
            y: item.payment,
            fillColor: item.paid_status === 'Paid' ? '#4CAF50' : '#FF5252',
          }));
        } catch (error) {
          console.error('Error fetching data:', error.message);
        }
      },
  
      updateChart() {
        // Call the fetchData method to get updated data
        this.fetchData();
      },
    },
    mounted() {
      // Fetch data when the component is mounted
      this.fetchData();
    },
  };
  </script>
  