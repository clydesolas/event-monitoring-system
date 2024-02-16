<!-- Home.vue -->

<template>
    <v-card>
      <v-layout>
        <Sidebar :activeItem.sync="activeItem" />
        <v-main>
          <div>
            <h2>Welcome to the Profile Page</h2>
            <p>User Information:</p>
            <pre>{{ user.email }}</pre>
            <button @click="logout">Logout</button>
          </div>
        </v-main>
      </v-layout>
    </v-card>
  </template>
  
  <script>
  import Sidebar from "@/components/Sidebar.vue"; // Adjust the path based on your project structure
  
  export default {
    components: {
      Sidebar,
    },
    computed: {
      user() {
        return this.$store.getters.getUser;
      },
    },
    methods: {
      async logout() {
        // ... (same as before)
      },
    },
  };
  </script>
  
  <style scoped>
  </style>
  