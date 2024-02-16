<template>
    <v-card flat>
      <v-card-title class="d-flex align-center pe-2">
        <v-icon icon="mdi-currency-usd"></v-icon> &nbsp; Paid Students
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          density="compact"
          label="Search"
          single-line
          flat
          hide-details
          variant="solo-filled"
        ></v-text-field>
      </v-card-title>
  
      <v-divider></v-divider>
      <v-data-table v-model:search="search" :items="students">
        <template v-slot:item.number="{ item }">
          <div class="text-end">{{ item.studentNumber }}</div>
        </template>
  
        <template v-slot:item.name="{ item }">
          <div class="text-start">{{ item.name }}</div>
        </template>
  
        <template v-slot:item.paid="{ item }">
          <div class="text-start">
            <v-chip
              :color="item.paid ? 'green' : 'red'"
              :text="item.paid ? 'Paid' : 'Not Paid'"
              class="text-uppercase"
              label
              size="small"
            ></v-chip>
          </div>
        </template>
  
        <!-- Corrected slot name for actions -->
        <template v-slot:item.actions="{ item }">
          <v-btn @click="performAction(item)" color="brown" text>
             Action
          </v-btn>
        </template>
      </v-data-table>
    </v-card>
  </template>
  

  <script>
  export default {
    data() {
      return {
        search: '',
        students: [
          { studentNumber: 1, name: 'John Doe', paid: true },
          { studentNumber: 2, name: 'Jane Smith', paid: false },
          { studentNumber: 11, name: 'Alice Johnson', paid: true },
          { studentNumber: 12, name: 'Bob Miller', paid: true },
          { studentNumber: 13, name: 'Eva Brown', paid: false },
          { studentNumber: 14, name: 'Chris Davis', paid: true },
          { studentNumber: 15, name: 'Grace Wilson', paid: false },
          { studentNumber: 16, name: 'Samuel Jones', paid: true },
          { studentNumber: 17, name: 'Sophie White', paid: true },
          { studentNumber: 18, name: 'Leo Martinez', paid: false },
          { studentNumber: 19, name: 'Olivia Taylor', paid: true },
          { studentNumber: 20, name: 'Daniel Clark', paid: true },
        ],
      };
    },
    methods: {
      performAction(student) {
        console.log(`Performing action for ${student.name}`);
        // Implement your action logic here
        // For example, you can show a dialog, update the database, etc.
      },
    },
  };
  </script>
  