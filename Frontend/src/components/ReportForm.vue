<template>
    <div>
      <!-- First Section -->
      <v-card>
        <v-card-title>
          <span >Generate Report</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" class="py-0 my-0 ">
                <v-text-field type="date"  label="Start Date*" required></v-text-field>
              </v-col>
              <v-col cols="12"  class="py-0 my-0 ">
                <v-text-field type="date"  label="End Date*" required></v-text-field>
              </v-col>
              <v-col cols="12"  class="py-0 my-0 ">
                <v-select
                  :items="['All', 'Guardian', 'Sibling', 'Relative', 'Friend']"
                  label="Event*"
                  required
                ></v-select>
              </v-col>
              <v-col cols="12" class="py-0 my-0 justify-center d-flex">
                <v-btn color="green" class="mx-2" prepend-icon="mdi-microsoft-excel" >Excel</v-btn>
                <v-btn color="red" prepend-icon="mdi-file-pdf-box" >Pdf</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </div>
  </template>
  
  <style scoped>
  .label-value {
    display: flex;
    justify-content: space-between;
  }
  .label {
    text-align: right;
    padding-right: 10px;
  }
  </style>
  