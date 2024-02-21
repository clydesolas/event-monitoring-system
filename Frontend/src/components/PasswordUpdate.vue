<template>
      <v-card>
        <v-card-title>Change Password</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-text-field
            v-model="currentPassword"
            label="Current Password"
            type="password"
            required
          ></v-text-field>
          <v-text-field
            v-model="newPassword"
            label="New Password"
            type="password"
            required
            min="8"
          ></v-text-field>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
          <v-btn @click="changePassword" color="brown">Change Password</v-btn>
        </v-card-actions>
      </v-card>
  
      <v-snackbar v-model="snackbar.show" :color="snackbar.color">
        {{ snackbar.message }}
        <template v-slot:action="{ attrs }">
          <v-btn text v-bind="attrs" @click="snackbar.show = false">Close</v-btn>
        </template>
      </v-snackbar>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useStore } from 'vuex';
  
  const currentPassword = ref('');
  const newPassword = ref('');
  const snackbar = ref({ show: false, message: '', color: '' });
  const store = useStore();
  
  const changePassword = async () => {
    try {
      const response = await axios.put('http://127.0.0.1:8000/api/update-password', {
        current_password: currentPassword.value,
        new_password: newPassword.value,
      }, {
        headers: {
          Authorization: `Bearer ${store.getters.getToken}`,
        },
      });
  
      showSnackbar(response.data.message, 'success');
    } catch (error) {
      const errorMessage = error.response.data.message || 'Error changing password. Please try again.';
      showSnackbar(errorMessage, 'error');
    }
  };
  
  const showSnackbar = (message, color) => {
    snackbar.value.message = message;
    snackbar.value.color = color;
    snackbar.value.show = true;
  };
  </script>
  