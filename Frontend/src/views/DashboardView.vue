<!-- Home.vue -->

<template>
    <v-layout>
      <Sidebar :activeItem.sync="activeItem" />
      <v-main class="mx-6 my-3">
        <v-sheet class="orig-secondary-background pa-4 rounded-lg">
          <h5 :class="['m-3', switchTheme.color]">Welcome to the Event Monitoring System!</h5>
          <pre>{{ switchTheme.color }}</pre>

        </v-sheet>
      </v-main>
    </v-layout>
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
    switchTheme(){
      return this.$store.getters.getSwitchTheme;
    }
  },
 
};
</script>

<style scoped>

</style>
