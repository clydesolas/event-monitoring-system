<script setup>
import { useTheme } from 'vuetify'
import { computed, ref } from 'vue';
import { useStore } from 'vuex';

const theme = useTheme()
const store = useStore();

const switchTheme1 = ref({ color: "text-brown", bg: "amber-lighten-5" });

function toggleTheme() {
  theme.global.name.value = theme.global.current.value.dark ? 'myCustomLightTheme' : 'dark';
  switchTheme1.value.color = theme.global.current.value.dark ? "text-yellow-lighten-5" : "text-brown";
  switchTheme1.value.bg = theme.global.current.value.dark ?  "bg-blue-grey-darken-4" : "bg-amber-lighten-5";
  store.dispatch("setSwitchTheme", switchTheme1.value);
}
</script>
<template>
    <v-app-bar :elevation="0" class="border-bottom-brown">
      <template v-slot:prepend>
        <v-app-bar-nav-icon color="brown" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
      <v-avatar>
        <v-img src="/public/logo_white.jpg"></v-img>
      </v-avatar>

      <v-app-bar-title :class="switchTheme.color">
            <div class="pt-3" style="line-height: .5;">
              <span><b>Event Monitoring System </b></span>
              <br>
              <span class="text-subtitle-1">Colegio de Porta Vaga</span>
            </div>
        </v-app-bar-title>

      <template v-slot:append>
        <v-row justify="center">
    <v-dialog
      v-model="dialog"
      persistent
      width="500"
    >
      <template v-slot:activator="{ props }">
        <v-btn icon="mdi-location-exit" color="brown" v-bind="props"></v-btn>
                </template>
                <v-card >
                    <v-card-title >
                    </v-card-title>
                    <v-card-text class="text-h6 justify-center d-flex align-center"> Are you sure you want to log out?</v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color=" "
                        variant="text"
                        @click="dialog = false"
                    >
                        No
                    </v-btn>
                    <v-btn
                        color="brown"
                        variant="text"
                        @click="logout"
                    >
                        Log out
                    </v-btn>
                    </v-card-actions>
                </v-card>
                </v-dialog>
            </v-row>
        <!-- <v-btn icon="mdi-location-exit" color="brown"  @click="logout"></v-btn> -->
      </template>
    </v-app-bar>
  
    <v-navigation-drawer app class="ma-2 rounded-lg" v-model="drawer">
      <v-list>
        <v-list-item :title="user.name" :subtitle="user.email"></v-list-item>
      </v-list>
      <v-divider></v-divider>
  
      <v-list>
    <RouterLink to="/Dashboard" :class="[switchTheme.color, 'router-link']" >
      <v-list-item
        @click="setActiveItem('DashboardView')"
        :class="{ 'v-list-item--active': isRouteActive('/Dashboard') }"
        prepend-icon="mdi-view-dashboard-outline"
        title="Dashboard"
        value="myfiles"
      ></v-list-item>
    </RouterLink>
        <RouterLink to="/Events" :class="[switchTheme.color, 'router-link']">
          <v-list-item
            @click="setActiveItem('EventsView')"
            :class="{ 'v-list-item--active': isRouteActive('/Events') }"
            prepend-icon="mdi-calendar-outline"
            title="Events"
            value="eventsView"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/StudentRecord" :class="[switchTheme.color, 'router-link']">
          <v-list-item
            @click="setActiveItem('StudentsView')"
            :class="{ 'v-list-item--active': isRouteActive('/Student') }"
            prepend-icon="mdi-account-school-outline"
            title="Student Record"
            value="studentRecordView"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/Report" :class="[switchTheme.color, 'router-link']">
          <v-list-item
            @click="setActiveItem('ReportView')"
            :class="{ 'v-list-item--active': isRouteActive('/Report') }"
            prepend-icon="mdi-chart-bar"
            title="Report"
            value="reportView"
          ></v-list-item>
        </RouterLink>

        <RouterLink to="/Archived" :class="[switchTheme.color, 'router-link']">
          <v-list-item
            @click="setActiveItem('ArchivedFilesView')"
            :class="{ 'v-list-item--active': isRouteActive('/Archived') }"
            prepend-icon="mdi-archive-outline"
            title="Archived Files"
            value="archivedFilesView"
          ></v-list-item>
        </RouterLink>
        

        <RouterLink to="/Profile" :class="[switchTheme.color, 'router-link']">
          <v-list-item
            @click="setActiveItem('ProfileView')"
            :class="{ 'v-list-item--active': isRouteActive('/Profile') }"
            prepend-icon="mdi-cog-outline"
            title="Profile"
            value="myfiles"
          ></v-list-item>
        </RouterLink>
        
      </v-list>
      <v-list-item class="d-flex justify-end align-content-end align-">
            <v-switch  prepend-icon="mdi-weather-night" v-model="darkMode" @change="toggleTheme">toggle theme</v-switch>
        </v-list-item>
    </v-navigation-drawer>
  </template>
  
  <script>


  export default {
    
    data() {
      return {
        drawer: null,
        dialog: false
      }
    },
    props: {
      activeItem: String,
    },
    computed: {
      user() {
        return this.$store.getters.getUser;
      },
      switchTheme(){
      return this.$store.getters.getSwitchTheme;
    }
    },
  
    methods: {
      setActiveItem(item) {
        this.$emit('update:activeItem', item);
      },
      isRouteActive(route) {
        return this.$route.path === route;
      },
      async logout() {
      try {
        const response = await this.$axios.post(
          "http://127.0.0.1:8000/api/logout",
          null,
          {
            headers: {
              Authorization: `Bearer ${this.$store.getters.getToken}`,
            },
          }
        );

        // Remove the token from localStorage or Vuex store
        this.$store.dispatch("clearToken");

        // Redirect to login page
        this.$router.push({ name: "Login" });
      } catch (error) {
        console.error("Logout failed", error);
      }
    },
    },
  };
  </script>
  
  <style scoped>

  .v-list-item :deep(.v-icon){
    color: var(--v-list-item-icon-color) !important;
    padding: 0 !important;
  }
  
  </style>
  