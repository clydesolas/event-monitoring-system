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
      <v-container>
    
        <v-card>
          <v-row class="d-flex align-center">
            <v-col>
              <v-card-title class="text-h5">
            {{ user.name }}
          </v-card-title>
            </v-col>
            <v-col class="d-flex justify-end">
              <v-btn class="ma-2" icon @click="openDialog">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            </v-col>
          </v-row>
          
          <v-divider></v-divider>
          <v-card-text>
            <v-list>
              <v-list-item-group>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>First Name</v-list-item-title>
                    <v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Middle Name</v-list-item-title>
                    <v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Last Name</v-list-item-title>
                    <v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Birth date</v-list-item-title>
                    <v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Sex</v-list-item-title>
                    <v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Email</v-list-item-title>
                    <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card-text>
        </v-card>
   

    <v-dialog persistent  v-model="dialog" max-width="600">
      <v-card>
        <v-card-title class="d-flex justify-space-between">
          Edit Profile
            <v-icon @click="closeDialog">mdi-close</v-icon>
        </v-card-title>
        <v-card-text>
            <v-text-field v-model="user.name" label="Name"></v-text-field>
            <v-text-field v-model="user.email" label="Email"></v-text-field>
            <v-text-field v-model="user.bio" label="Bio"></v-text-field>
            <v-text-field v-model="user.location" label="Location"></v-text-field>
            <v-divider></v-divider>
            <div class="d-flex justify-end">
              <v-btn type="submit" color="amber-lighten-5">Save Changes</v-btn>
            </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
    </v-main>
  </v-layout>
</template>

<script setup>
import Sidebar from "@/components/Sidebar.vue";
import PaidStudents from "@/components/PaidStudents.vue";
import PaymentForm from "@/components/PaymentForm.vue";
import { ref, onMounted } from 'vue';



const dialog = ref(false);

const openDialog = () => {
  dialog.value = true;
};
  const closeDialog = () => {
    dialog.value = false;
  };

</script>

<script>

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
