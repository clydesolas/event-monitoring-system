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
                  label="Event Title*"
                  hint="Enter title of an event"
                  required>
                </v-text-field>
              </v-col>
              <v-col
                cols="6"
                sm="12"
                md="6">
                <v-select
                label="Academic Year*"
                :items="academicYears"
                ></v-select>
              </v-col>
              <v-col
                cols="6"
                sm="12"
                md="6">
                <v-select
                label="Semester*"
                :items="['FIRST SEMESTER', 'SECOND SEMESTER']"
                >
              </v-select>
              </v-col>
              <v-col
                cols="12"
                sm="4"
                md="4">
                <v-text-field
                  v-model="selectedDate"
                  readonly
                  hint="Date of Event">
                </v-text-field>
              </v-col>
              
              <v-col
                cols="12"
                sm="4"
                md="4">
              <v-select
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
                 class="bg-green-lighten-5"
                  label="Total Payment*"
                  :rules="[rules.required, rules.isNumber]"
                  hint="Enter total fee of a student"
                  required>
                </v-text-field>
              </v-col>
              <v-col cols="12">
                <v-textarea
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
            @click="dialog = false"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script setup >
  import { ref, computed } from 'vue';
  import { useTheme } from 'vuetify'
  import { format, subYears, getYear } from 'date-fns';
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
    
    const theme = useTheme()
    const isDarkTheme = computed(() => theme.global.current.value.dark);
    
  const todos = ref([
    {
      description: 'Take Noah to basketball practice.',
      isComplete: false,
      color: '#4C3228',
      dates: new Date(2024, 2, 15),
    },
    {
      description: 'Take Noah to basketball practice.',
      isComplete: false,
      color: '#4C3228',
      dates: new Date(2024, 2, 15),
    },
    {
      description: 'Take Noah to basketball practice.',
      isComplete: false,
      color: '#4C3228',
      dates: new Date(2024, 2, 15),
    },
  ]);

  let selectedDate = ref(null);
  const dialog1 = ref(false);

  const attributes = computed(() =>
  
    todos.value.map(todo => ({
        
      dates: todo.dates,
      dot: {
        color: todo.color,
        class: todo.isComplete ? 'opacity-75' : '',
      },
      popover: {
        label: todo.description,
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
};
  const closeDialog = () => {
    dialog1.value = false;
  };
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