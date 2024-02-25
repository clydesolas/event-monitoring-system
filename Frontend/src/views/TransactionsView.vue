<template>
  <v-layout>
    <Sidebar :activeItem.sync="activeItem" />
    <v-main class="mx-2 my-3 ">
      <v-breadcrumbs :items="items" :class="[ 'pt-1', switchTheme.color]">
        <template v-slot:title="{ item }">
            <RouterLink :to="item.to" :class="['text-decoration-none', switchTheme.color]">
              {{ item.title.toUpperCase() }}
            </RouterLink>
          </template>
        </v-breadcrumbs>
        
        <v-row class="px-4 d-flex justify-center">
          <v-col cols="md-12">
            <TransactionsTable/>
          </v-col>
          <v-col cols="md-7">
          </v-col>
        </v-row>
    </v-main>
  </v-layout>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue"; 
import Calendar from "@/components/Calendar.vue"; 
import TransactionsTable from "@/components/TransactionsTable.vue"; 

export default {
  data: () => ({
      items: [
        {
          title: 'Dashboard',
          disabled: false,
          to: '/Dashboard',
        },
        {
          title: 'Transactions',
          disabled: true,
          to: '/Transactions',
        },
      ],
    }),
components: {
  Sidebar,
  Calendar,
  TransactionsTable,
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
