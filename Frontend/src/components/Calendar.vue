<template>
    <div>
        <v-toolbar-title :class="['elevation-1 rounded-t-lg pa-5 w-100', switchTheme.bg, switchTheme.color]">Select a date to add event:</v-toolbar-title>
        
      <VCalendar vc-text-2xl vc-font-bold class="rounded-t-0 tcalendar border-0 elevation-t-0 elevation-1" style="height: 430px !important;" :is-dark="isDarkTheme"  expanded :attributes="attributes" @dayclick="openDialog" />
  
      <v-dialog v-model="dialog1" max-width="800">
        <v-card>
        <v-card-title>
          <span class="text-h5">Add Event</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="12"
                sm="12"
                md="12">
                <v-text-field
                  v-model="formData.title"
                  label="Event Title*"
                  hint="Enter title of an event"
                  required>
                </v-text-field>
              </v-col>
              <v-col
              cols="12"
                sm="4"
                md="4">
                <v-select
                  v-model="formData.academic_year"
                label="Academic Year*"
                :items="academicYears"
                ></v-select>
              </v-col>
              <v-col
              cols="12"
                sm="4"
                md="4">
                <v-select
                  v-model="formData.semester"
                label="Semester*"
                :items="['FIRST SEMESTER', 'SECOND SEMESTER','SUMMER SEMESTER']"
                >
              </v-select>
              </v-col>
              <v-col
                cols="12"
                sm="4"
                md="4">
                <v-text-field
                  v-model="formData.due_date"
                  required
                  type="date"
                  min=""
                  hint="Due Date for Payment"
                  label="Due Date for Payment"
                  >
                </v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="4"
                md="4">
                <v-text-field
                  v-model="selectedDate"
                  readonly
                  hint="Date of Event"
                  persistent-hint
                  >
                </v-text-field>
              </v-col>
              
              <v-col
                cols="12"
                sm="4"
                md="4">
              <v-select
                  v-model="formData.duration_date"
                label="Event Duration"
                :items="['1 Day', '2 Days', '3 Days', '4 Days', '5 Days', '6 Days', '1 Week']"
                >
              </v-select>
              </v-col>
              <v-col
                cols="12"
                sm="4"
                md="4">
                <v-text-field
                  v-model="formData.payment"
                 class="bg-green-lighten-5"
                  label="Total Payment*"
                  onkeypress="return event.key === 'Enter'
                    || (Number(event.key) >= 0
                    && Number(event.key) <= 9)"
                  type="number" 
                  min="50"
                  :rules="[rules.required, rules.isNumber]"
                  hint="Enter total fee of a student"
                  required>
                </v-text-field>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  v-model="formData.description"
                clearable
                clear-icon="mdi-close-circle"
                label="Description">
                </v-textarea>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue-darken-1"
            variant="text"
            @click="closeDialog"
          >
            Close
          </v-btn>
          <v-btn
          color="blue-darken-1"
          variant="text"
          @click="submitForm"
          :disabled="isLoading"
        >
          <span v-if="isLoading">
            <v-progress-circular
              indeterminate
              color="white"
              size="20"
            ></v-progress-circular>
          </span>
          <span v-else>
            Save
          </span>
        </v-btn>

        </v-card-actions>
      </v-card>
      </v-dialog>
      <v-snackbar v-model="snackbar" :timeout="timeout">
      {{ snackbarMessage }}
    </v-snackbar>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useTheme } from 'vuetify';
  import { getYear, format } from 'date-fns';
  import axios from 'axios';
import { useStore } from 'vuex';  
  

  const snackbar = ref(false);
  const snackbarMessage = ref('');
  const timeout = 6000;
  
  const formData = ref({
    title: '',
    description: '',
    date: '',
    duration_date: '',
    due_date: '',
    payment: '0',
    academic_year: '',
    semester: '',
  });
  

  const isLoading = ref(false);
  const store = useStore(); // Access the store

  const submitForm = async () => {
    try {
      isLoading.value = true; 
     

      formData.value.date = selectedDate2.value;
      const response = await axios.post('http://127.0.0.1:8000/api/store-event', formData.value,
      {
          headers: {
            Authorization: `Bearer ${store.getters.getToken}`,
          },
        }
      );
        const newEventId = Date.now().toString();

      const newEvent = {
        id: newEventId,
        date: formData.value.date,
        title: formData.value.title,
        isComplete: false, 
      };

todos.value.push(newEvent);
      // Handle successful response
      snackbarMessage.value = "Event added successfully!";
      dialog1.value = false;
      snackbar.value = true;
    } catch (error) {
      // Handle error response
      snackbarMessage.value = 'Error submitting the form.';
      snackbar.value = true;
      console.error(error);
    } finally {
    isLoading.value = false; // Disable loading state
  }
  };
  
  const currentDate = new Date();
  const currentYear = getYear(currentDate);
  
  const academicYears = computed(() => {
    const lastYear = currentYear - 1;
    const nextYear = currentYear + 1;
  
    const currentAcademicYear = `A.Y. ${lastYear}-${currentYear}`;
    const nextAcademicYear = `A.Y. ${currentYear}-${nextYear}`;
  
    return [currentAcademicYear, nextAcademicYear];
  });
  
  const rules = {
    required: (value) => !!value || 'This field is required',
    isNumber: (value) => /^\d+$/.test(value) || 'Only numeric input is allowed',
  };
  
  const theme = useTheme();
  const isDarkTheme = computed(() => theme.global.current.value.dark);
  
  const todos = ref([]);
  let selectedDate = ref(null);
  let selectedDate2 = ref(null);
  const dialog1 = ref(false);
  
  const attributes = computed(() =>
    todos.value.map((todo) => ({
      dates: todo.date,
      dot: {
        color: todo.color,
        class: todo.isComplete ? 'opacity-75' : '',
      },
      popover: {
        label: todo.title,
      },
    }))
  );
  
  const openDialog = (date) => {
    dialog1.value = true;
    console.log('Clicked date:', date.id);
    const formattedDate = new Date(date.id).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: '2-digit',
    });
    selectedDate.value = formattedDate;
    selectedDate2.value = date.id;
    console.log(selectedDate2.value)
  };
  
  const closeDialog = () => {
    dialog1.value = false;
  };
  
  onMounted(async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/events-get-all',
      {
          headers: {
            Authorization: `Bearer ${store.getters.getToken}`,
          },
        });
      todos.value = response.data;
    } catch (error) {
      console.error('Error fetching events:', error);
    }
  });
  </script>
  
  

<script>
export default {
  
  computed: {
    user() {
      return this.$store.getters.getUser;
    },
    switchTheme(){
      return this.$store.getters.getSwitchTheme;
    }
  },
 
};
</script>

  <style>

.tcalendar {
 
}


.vc-day-content {
    font-size: 1.1rem !important; 
    background-color: #ffecb350 !important;
    color: #a1887f;
    padding: 1.2rem ;
}
.vc-week{
    margin-bottom: .5rem;
}
  </style>