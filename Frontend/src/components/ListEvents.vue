<template>
    <v-card class="mx-auto rounded-t-lg main-content" max-width="600">
      <v-toolbar :class="[switchTheme.bg, switchTheme.color]">
        <v-toolbar-title>List Of Events</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-expand-x-transition>
          <div v-show="isSearchExpanded" class="w-25">
            <v-text-field class="mt-5 sm" v-model="search" label="Search" outlined></v-text-field>
          </div>
        </v-expand-x-transition>
        <v-btn @click="toggleSearch" variant="text" icon="mdi-magnify"></v-btn>
      </v-toolbar>
  
      <v-sheet class="overflow-y-auto" color="brown" mode="manual" height="430" >
        <v-list lines="two">
          <v-list-item
            class="ma-1 border-t-sm"
            v-for="folder in filteredFolders.slice(0, displayedItemCount)"
            :key="folder.id"
            :title="folder.title"
            :subtitle="folder.subtitle"
            @click="openNewPage(folder.id)"
          >
            <template v-slot:prepend>
              <v-avatar color="brown-lighten-2">
                <v-icon color="amber-lighten-4">mdi-folder</v-icon>
              </v-avatar>
            </template>
  
            <template v-slot:append>
              <v-btn color="grey" icon="mdi-information" variant="text"></v-btn>
            </template>
          </v-list-item>
        </v-list>
      </v-sheet>
  
    </v-card>
  </template>

<script>
import axios from 'axios';

export default {
  data: () => ({
    isSearchExpanded: false,
    search: "",
    selectedFolderId: null,
    folders: [],
  }),

  computed: {
    filteredFolders() {
      return this.folders.filter((folder) =>
        folder.title.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    switchTheme() {
      return this.$store.getters.getSwitchTheme;
    }
  },

  methods: {
    toggleSearch() {
      this.isSearchExpanded = !this.isSearchExpanded;
    },
    
    formatDate(dateString) {
      const options = { month: 'short', day: 'numeric', year: 'numeric' };
      const formattedDate = new Date(dateString).toLocaleDateString('en-US', options);
      return formattedDate;
    },
    
    async loadData() {
      try {
        console.log('Fetching data...');
        const response = await axios.get('http://127.0.0.1:8000/api/events-get-all',
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
        this.folders = response.data.map(item => ({
          id: item.id,
          title: item.title,
          event_id: item.event_id,
          subtitle: 'Date: '+this.formatDate(item.date)+' | ID: '+item.event_id,
        }));
        console.log('Data fetched successfully:', this.folders);
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
   
    openNewPage(folderId) {
      this.selectedFolderId = folderId;
      this.$router.push({ name: 'EventDetails', params: { pageId: folderId } });
    },
  },

  created() {
    console.log('Component is created');
    this.loadData();

    // Fetch data every 5 seconds
    this.fetchDataInterval = setInterval(() => {
      this.loadData();
    }, 7000);
  },

  beforeDestroy() {
    clearInterval(this.fetchDataInterval);
  },
};
</script>




<style scoped>
.v-list-item:hover{
cursor: pointer;
background-color: #ffffff56;
}
.dialog-bottom-transition-enter-active,
.dialog-bottom-transition-leave-active {
  transition: transform .2s ease-in-out;
}
.main-content {
    overflow-y: hidden !important;
  }
  body {
    overflow-y: hidden !important;
  }
</style>