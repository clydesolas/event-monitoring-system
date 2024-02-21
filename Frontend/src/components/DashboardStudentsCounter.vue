<template>
    <v-card elevation="0">
    <v-toolbar-title class="text-center" style="font-size:15px">
    <RouterLink :class="['text-decoration-none', switchTheme.color]" to="/StudentRecord"> 
        <v-icon>mdi-chevron-down</v-icon>TOTAL ENROLLED STUDENTS
    </RouterLink>
    </v-toolbar-title>
      <v-card-text :class="[switchTheme.color, 'text-h4 text-center d-flex justify-center align-center pt-0']" style="height:160px">
         {{ totalEnrolledStudents }}
      </v-card-text>
    </v-card>
  </template>
  
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        totalEnrolledStudents: 0,
      };
    },
    mounted() {
      this.fetchEnrolledStudentsCount();
    },
    methods: {
    async fetchEnrolledStudentsCount() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/enrolled-students-count",
        { headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },});
        this.totalEnrolledStudents = response.data.total_enrolled_students;
      } catch (error) {
        console.error("Error fetching enrolled students count:", error);
      }
    },
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
    // Call the method to fetch enrolled students count when the component is mounted
    this.fetchEnrolledStudentsCount();
  },
};
</script>
  
  <style scoped>
  /* Add any additional styling if needed */
  </style>
  