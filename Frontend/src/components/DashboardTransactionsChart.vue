<template>
    <v-card elevation="0">
      <v-card-title flat>
        <v-toolbar-title class="d-flex justify-end">
          <v-btn @click="updateChart"><v-icon>mdi-reload</v-icon>&nbsp; Reload</v-btn>
        </v-toolbar-title>
      </v-card-title>
      <div>
        <apexchart width="500" type="bar" :options="chartOptions" :series="series"></apexchart>
      </div>
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
          colors: ['#E5D763', '#EEBA94', '#4C3228'],
        },
        series: [
          {
            name: 'Total Paid',
            data: [],
          },
          {
            name: 'Total Not Paid',
            data: [],
          },
          {
            name: 'Total Students',
            data: [],
          },
        ],
      };
    },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/total-paid-and-not-paid', {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });

        if (response.status !== 200) {
          throw new Error('Failed to fetch data');
        }

        const { total_count_event } = response.data;

        this.chartOptions.xaxis.labels.categories = total_count_event.map(item => item.title);
        this.series = [
          {
            name: 'Total Paid',
            data: total_count_event.map(item => item.total_paid),
          },
          {
            name: 'Total Not Paid',
            data: total_count_event.map(item => item.total_not_paid),
          },
          {
            name: 'Total Students',
            data: total_count_event.map(item => item.total_students),
          },
        ];
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
  