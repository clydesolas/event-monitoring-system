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
  
      <v-infinite-scroll color="brown" mode="manual" height="430" @load="load">
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
      </v-infinite-scroll>
  
    </v-card>
  </template>

  <script>
export default {
  data: () => ({
    isSearchExpanded: false,
    search: "",
    selectedFolderId: null,
    notifications: false,
    sound: true,
    widgets: false,
    dialog: false,
    folders: [
      {
        id: 1,
        subtitle: 'Jan 9, 2014',
        title: 'Fieldtrip',
      },
      {
        id:3,
        subtitle: 'Jan 17, 2014',
        title: 'Graduation Ceremony',
      },
      {
        id:4,
        subtitle: 'Jan 28, 2014',
        title: 'xzxc',
      },
      {
        id:5,
        subtitle: 'Feb 5, 2014',
        title: 'Science Fair',
      },
      {
        id: 34,
        subtitle: 'Feb 14, 2014',
        title: 'Valentine’s Day Celebration',
      },
      {
        id:23,
        subtitle: 'Mar 3, 2014',
        title: 'Sports Day',
      },
      {
        id:12,
        subtitle: 'Mar 12, 2014',
        title: 'Art Exhibition',
      },
      {
        id:121,
        subtitle: 'Apr 8, 2014',
        title: 'Parent-Teacher Meeting',
      },
      {
        id:31,
        subtitle: 'Apr 22, 2014',
        title: 'Book Fair',
      },
      {
        id:11,
        subtitle: 'May 7, 2014',
        title: 'Music Recital',
      },
    //   {
    //     subtitle: 'May 21, 2014',
    //     title: 'School Picnic',
    //   },
    //   {
    //     subtitle: 'Jun 2, 2014',
    //     title: 'End-of-Year Assembly',
    //   },
    //   {
    //     subtitle: 'Jun 15, 2014',
    //     title: 'Summer Camp Begins',
    //   },
    ],
    displayedItemCount: 5,
  }),
  computed: {
  filteredFolders() {
    return this.folders.filter((folder) =>
      folder.title.toLowerCase().includes(this.search.toLowerCase())
    );
  },
  switchTheme(){
      return this.$store.getters.getSwitchTheme;
    }
},

  methods: {
    toggleSearch() {
      this.isSearchExpanded = !this.isSearchExpanded;
    },
    loadMore() {
    if (this.displayedItemCount < this.filteredFolders.length) {
        this.displayedItemCount += 5;
    }
    },

    load({ done }) {
      setTimeout(() => {
        done('empty');
      }, 1000);
    },
    openNewPage(folderId) {
    this.selectedFolderId = folderId;
    // Assuming you have a route named 'eventDetails' with a parameter 'id'
    this.$router.push({ name: 'EventDetails', params: { pageId: folderId } });
    },
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