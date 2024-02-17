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
        <v-card class="mx-4">
            <v-tabs
            v-model="tab"
            bg-color="amber-lighten-5"
            color="brown"
            >
            <v-tab value="one">Students</v-tab>
            <v-tab value="two">Events</v-tab>
            </v-tabs>

            <v-card-text>
            <v-window v-model="tab">
                <v-window-item value="one">
                <ArchivedStudents/>
                </v-window-item>

                <v-window-item value="two">
                <ArchivedEvents/>
                </v-window-item>
               
            </v-window>
            </v-card-text>
        </v-card>
      </v-main>
    </v-layout>
  </template>
  
  <script>
  import Sidebar from "@/components/Sidebar.vue";
  import ArchivedStudents from "@/components/ArchivedStudents.vue";
  import ArchivedEvents from "@/components/ArchivedEvents.vue";
  
  export default {
    data: () => ({
        pageId: null,
        tab: null,
    }),
    created() {
      this.pageId = this.$route.params.pageId;
    },
    components: {
      Sidebar,
      ArchivedStudents,
      ArchivedEvents,
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
  