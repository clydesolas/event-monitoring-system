<template>
  <div>
    <apexchart
      type="bar"
      :options="chartOptions"
      :series="chartSeries"
    ></apexchart>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import axios from 'axios';
export default {
  components: {
    apexchart: VueApexCharts,
  },
  data() {
    return {
      chartOptions: {
        chart: {
          type: "bar",
          background: '#f0ece5',
        },
        plotOptions: {
          bar: {
            horizontal: true,
            distributed: true,
          },
        },
        colors: ['#b68f40'],
        xaxis: {
          categories: [],
        },
        title: {
          text: 'MONTHLY EVENTS COUNT',
          align: 'center',
          style: {
            fontSize: '15px',
            fontWeight: 'normal',
            color: '#3e2723',
          },
        },
        legend: {
          show: false, // Hide the legend
        },
        responsive: [
          {
            breakpoint: 576,
            options: {
              chart: {
                width: '100%',
              },
            },
          },
          {
            breakpoint: 768,
            options: {
              chart: {
                width: '80%',
              },
            },
          },
          {
            breakpoint: 992,
            options: {
              chart: {
                width: '60%',
              },
            },
          },
          {
            breakpoint: 1200,
            options: {
              chart: {
                width: '50%',
              },
            },
          },
        ],
      },
      chartSeries: [
        {
          data: [],
          dataLabels: {
            position: 'bottom',
            formatter: function (value) {
              return Math.round(value); // Format data labels as whole numbers
            },
          },
        },
      ],
    };
  },

  mounted() {
    // Fetch data from the API
    this.fetchEventData();
  },
  methods: {
    async fetchEventData() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/event-count-by-month-current-year',null, {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });

        const data = response.data;

        // Extracting data from the response
        const categories = data.event_counts ? data.event_counts.map((item) => item.month_year) : [];
        const seriesData = data.event_counts ? data.event_counts.map((item) => item.count) : [];


        // Convert seriesData to numbers if needed
        const seriesDataNumeric = seriesData.map(Number);

        // Update chart data reactively
        this.chartOptions = {
          ...this.chartOptions,
          xaxis: {
            categories: categories,
          },
        };

        this.chartSeries = [
          {
            data: seriesDataNumeric,
            dataLabels: {
              position: 'bottom',
              formatter: function (value) {
                return Math.round(value);
              },
            },
          },
        ];
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
  },
};
</script>
