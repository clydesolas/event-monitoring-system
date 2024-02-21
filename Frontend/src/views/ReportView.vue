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
          <v-col cols="md-8">
            <ReportTable :modelValue="selectedOptions" />
          </v-col>
          <v-col cols="md-4">
            <ReportForm v-model="selectedOptions" />
          </v-col>
        </v-row>
      </v-main>
    </v-layout>
  </template>
  
  <script>
  import Sidebar from "@/components/Sidebar.vue";
  import ReportTable from "@/components/ReportTable.vue";
  import ReportForm from "@/components/ReportForm.vue";
  
  export default {
    data: () => ({
        pageId: null,
        selectedOptions: {
        selectedAcademicYear: 'all',
        selectedSemester: 'all',
        selectedEvent: 'all',
      },
    }),
    created() {
      this.pageId = this.$route.params.pageId;
    },
    components: {
      Sidebar,
      ReportTable,
      ReportForm,
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
            title: 'Report',
            to: '/Report',
          },
        ];
      },
    },
  };
  </script>
  
  <style scoped></style>
  