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
          <v-col cols="md-5"> 
            <Calendar />
          </v-col>
          <v-col cols="md-7">
            <ListEvents/>
          </v-col>
        </v-row>
    </v-main>
  </v-layout>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue"; 
import Calendar from "@/components/Calendar.vue"; 
import ListEvents from "@/components/ListEvents.vue"; 

export default {
  data: () => ({
      items: [
        {
          title: 'Dashboard',
          disabled: false,
          to: '/Dashboard',
        },
        {
          title: 'Events',
          disabled: true,
          to: '/Events',
        },
      ],
    }),
components: {
  Sidebar,
  Calendar,
  ListEvents,
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
