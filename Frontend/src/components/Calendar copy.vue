<template>
  <v-container class="py-0 my-3">
    <v-row justify="space-around">
      <v-sheet class="bg-brown rounded-t-lg pa-5 w-100">Select a date</v-sheet>
      <v-date-picker
        color="brown"
        hide-header
        class="rounded-t-0 w-100"
        title="Set an Event"
        v-model="selectedDate"
        @click="openDialog"
      >
        
      </v-date-picker>
    </v-row>
    <v-dialog v-model="dialog1" max-width="400">
      <v-card>
        <v-card-title>Date Selected</v-card-title>
        <v-card-text>{{ selectedDate }}</v-card-text>
        <v-card-actions>
          <v-btn @click="closeDialog">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script>


export default {
 data() {
   return {
     selectedDate: null,
     dialog1: false,
   };
 },

 methods: {
  
   openDialog() {
     this.dialog1 = true;
   },
   closeDialog() {
     this.dialog1 = false;
   },
 },
};
</script>