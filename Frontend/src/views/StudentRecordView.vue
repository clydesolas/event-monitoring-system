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
            <StudentsTable />
          </v-col>
          <v-col cols="md-4">
            <StudentsMasterlistForm/>
          </v-col>
        </v-row>
      </v-main>
    </v-layout>
  </template>
  
  <script>
  import Sidebar from "@/components/Sidebar.vue";
  import StudentsTable from "@/components/StudentsTable.vue";
  import StudentsMasterlistForm from "@/components/StudentsMasterlistForm.vue";
  
  export default {
    data: () => ({
        pageId: null,
    }),
    created() {
      this.pageId = this.$route.params.pageId;
    },
    components: {
      Sidebar,
      StudentsTable,
      StudentsMasterlistForm,
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
            title: 'Student Record',
            to: '/StudentRecord',
          },
        ];
      },
    },
  };
  </script>
  
  <style scoped></style>
  