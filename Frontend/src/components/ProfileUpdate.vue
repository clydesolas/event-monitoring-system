<template>
    <v-btn class="mx-2" elevation="0" size="small" icon @click="openDialog">
      <v-icon >mdi-pencil</v-icon>
    </v-btn>
  
    <v-dialog persistent v-model="dialog" max-width="600">
      <v-card>
        <v-card-title class="d-flex justify-space-between">
          Edit Profile
          <v-icon @click="closeDialog">mdi-close</v-icon>
        </v-card-title>
        <v-card-text>
          <v-text-field v-model="user.first_name" label="First Name"></v-text-field>
          <v-text-field v-model="user.middle_name" label="Middle Name"></v-text-field>
          <v-text-field v-model="user.last_name" label="Last Name"></v-text-field>
          <v-text-field v-model="user.email" label="Email"></v-text-field>
          <v-divider></v-divider>
          <div class="d-flex justify-end">
            <v-btn @click="saveChanges" color="amber-lighten-5">Save Changes</v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line>
    {{ snackbar.message }}
    <template v-slot:action="{ attrs }">
      <v-btn text v-bind="attrs" @click="snackbar.show = false">
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';  

const dialog = ref(false);
const snackbar = ref({
  show: false,
  message: '',
  color: '',
});

const openDialog = () => {
  dialog.value = true;
};

const closeDialog = () => {
  dialog.value = false;
};

const store = useStore(); // Access the store

const user = ref(store.getters.getUser); // Corrected the closing curly brace here

const saveChanges = async () => {
  try {
    const response = await axios.put('http://127.0.0.1:8000/api/update-profile', 
       {
      first_name: user.value.first_name, // Use user.value to access the ref value
      middle_name: user.value.middle_name,
      last_name: user.value.last_name,
      email: user.value.email,
    },
    {
    headers: {
          Authorization: `Bearer ${store.getters.getToken}`,
        },
    }
    );

    // Handle the response as needed
    console.log(response.data);

    // Show success message in snackbar
    showSnackbar('Profile updated successfully', 'success');

    // Close the dialog after a successful update
    closeDialog();
  } catch (error) {
    console.error('Error updating profile', error);

    // Show an error message in snackbar
    showSnackbar('Error updating profile. Please try again.', 'error');
  }
};

const showSnackbar = (message, color) => {
  snackbar.value.message = message;
  snackbar.value.color = color;
  snackbar.value.show = true;
};
</script>