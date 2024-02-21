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
    
        <v-row>
          <v-col cols="12" md="6"  >
            <v-card>
              <v-row class="d-flex align-center">
                <v-col>
                  <v-card-title >
                Personal Information
              </v-card-title>
                </v-col>
                <v-col class="d-flex justify-end">
                <ProfileUpdate v-if="user.role === 'admin'"/>
                </v-col>
              </v-row>
              
              <v-divider></v-divider>
              <v-card-text>
                <v-list>
                  <v-list-item-group>
                    <v-list-item v-if="user.role === 'user'">
                      <v-list-item-content>
                        <v-list-item-title>Student Number</v-list-item-title>
                        <v-list-item-subtitle>{{ user.student_number }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item >
                      <v-list-item-content>
                        <v-list-item-title>First Name</v-list-item-title>
                        <v-list-item-subtitle>{{ user.first_name }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Middle Name</v-list-item-title>
                        <v-list-item-subtitle>{{ user.middle_name }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Last Name</v-list-item-title>
                        <v-list-item-subtitle>{{ user.last_name }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item v-if="user.role === 'user'">
                      <v-list-item-content>
                        <v-list-item-title>Birth date</v-list-item-title>
                        <v-list-item-subtitle>{{ user.birth_date }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item v-if="user.role === 'user'">
                      <v-list-item-content>
                        <v-list-item-title>Sex</v-list-item-title>
                        <v-list-item-subtitle>{{ user.sex }}</v-list-item-subtitle>
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
          </v-col>
        <v-col cols="12" md="6" >
          <PasswordUpdate/>
        </v-col>
      </v-row>

  </v-container>
    </v-main>
  </v-layout>
</template>

<script setup>
import Sidebar from "@/components/Sidebar.vue";
import ProfileUpdate from "@/components/ProfileUpdate.vue";
import PasswordUpdate from "@/components/PasswordUpdate.vue";
import { ref, onMounted } from 'vue';

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
    ProfileUpdate,
    PasswordUpdate
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
          title: 'Profile',
          to: '/Profile',
        },
      ];
    },
  },
};
</script>

<style scoped></style>
