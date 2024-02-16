<template>
    <div>
      <!-- First Section -->
      <v-card>
        <v-card-title>
          <span >Payment Form</span>
        </v-card-title>
        <v-card-text>
          <small>*indicates required field</small>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6" class="py-0 my-0 ">
                <v-text-field label="Student Number*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" class="py-0 my-0 ">
                <v-text-field label="Paid By*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" class="py-0 my-0 ">
                <v-select
                  :items="['Parent', 'Guardian', 'Sibling', 'Relative', 'Friend']"
                  label="Relationship with Student*"
                  required
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
  
      <!-- Second Section -->
      <v-card>
        <v-card-title>
        <span class="text-h5" style="font-size: 18px !important;">Payment Summary</span>
      </v-card-title>
        <v-card-text>
          <v-container class="py-0 my-0 mt-4">
            <v-row>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Student Full Name:</span></v-col>
                  <v-col class="value" cols="6"><span>John Doe</span></v-col>
                </v-row>
              </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Event Name:</span></v-col>
                  <v-col class="value" cols="6"><span>School Event</span></v-col>
                </v-row>
              </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Event Date:</span></v-col>
                  <v-col class="value" cols="6"><span>2024-02-16</span></v-col>
                </v-row>
              </v-col>
              <v-col cols="12" sm="12" class="py-0 my-0">
                <v-row class="label-value">
                  <v-col class="label" cols="6"><span>Payment Price:</span></v-col>
                  <v-col class="value" cols="6"><span>$100.00</span></v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text">Clear</v-btn>
          <v-btn color="blue-darken-1" variant="text">Save</v-btn>
        </v-card-actions>
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
  