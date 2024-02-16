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
        <!-- <p>Event Name {{ pageId }}</p> -->
        <v-row class="px-4 d-flex justify-center">
          <v-col cols="md-6">
            <PaidStudents />
          </v-col>
          <v-col cols="md-6">
            <PaymentForm/>
          </v-col>
        </v-row>
      </v-main>
    </v-layout>
  </template>
  
  <script>
  import Sidebar from "@/components/Sidebar.vue";
  import PaidStudents from "@/components/PaidStudents.vue";
  import PaymentForm from "@/components/PaymentForm.vue";
  
  export default {
    data: () => ({
        pageId: null,
    }),
    created() {
      this.pageId = this.$route.params.pageId;
    },
    components: {
      Sidebar,
      PaidStudents,
      PaymentForm,
    },
    computed: {
        user() {
      return this.$store.getters.getUser;
    },
    switchTheme(){
      return this.$store.getters.getSwitchTheme;
    },
      items() {
        return [
          {
            title: 'Dashboard',
            to: '/Dashboard',
          },
          {
            title: 'Events',
            to: '/Events',
          },
          {
            title: 'Details',
            to: `/EventDetails/${this.pageId}`, 
          },
        ];
      },
    },
  };
  </script>
  
  <style scoped></style>
  