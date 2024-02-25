<!-- Home.vue -->

<template>
    <v-layout >
      <Sidebar :activeItem.sync="activeItem" />
      <v-main class="mx-2 my-3 ">
        <v-sheet class="orig-secondary-background pa-4 rounded-lg mx-4 mb-4">
          <h5 :class="['m-3', switchTheme.color]">Welcome to the Event Monitoring System!</h5>
        </v-sheet>

        <v-row class="px-4">
          <v-col cols="12" md="6"  v-if="user.role === 'admin'">
            <DashboardTransactionsTable/>
          </v-col>
          <v-col cols="12" md="6"  v-if="user.role === 'user'">
            <DashboardUpcomingEvents/>
          </v-col>
          <v-col cols="12" md="6"  v-if="user.role === 'user'">
            <DashboardUserEvents/>
          </v-col>
          
        
          <v-col cols="12" md="6"  v-if="user.role === 'user'">
            <CalendarUser/>
          </v-col>
          <v-col cols="12" md="6"  v-if="user.role === 'admin'">
            <DashboardTransactionsChart/>
          </v-col>
          <v-col cols="12" md="4"  v-if="user.role === 'admin'">
            <DashboardStudentsCounter/>
          </v-col>
          <v-col cols="12" md="4"  v-if="user.role === 'admin'">
            <DashboardUserCounter/>
          </v-col>
          <v-col cols="12" md="4"  v-if="user.role === 'admin'">
            <DashboardMonthlyEventsChart/>
          </v-col>

        </v-row>
      </v-main>
    </v-layout>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";  
import DashboardTransactionsTable from "@/components/DashboardTransactionsTable.vue";  
import DashboardTransactionsChart from "@/components/DashboardTransactionsChart.vue";  
import DashboardMonthlyEventsChart from "@/components/DashboardMonthlyEventsChart.vue";  
import DashboardStudentsCounter from "@/components/DashboardStudentsCounter.vue";  
import DashboardUserCounter from "@/components/DashboardUserCounter.vue";  
import DashboardUpcomingEvents from "@/components/DashboardUpcomingEvents.vue";  
import CalendarUser from "@/components/CalendarUser.vue";  
import DashboardUserEvents from "@/components/DashboardUserEvents.vue";  

export default {
  
  components: {
    Sidebar,
    DashboardTransactionsTable,
    DashboardTransactionsChart,
    DashboardMonthlyEventsChart,
    DashboardStudentsCounter,
    DashboardUserCounter,
    DashboardUpcomingEvents,
    CalendarUser,
    DashboardUserEvents,
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
