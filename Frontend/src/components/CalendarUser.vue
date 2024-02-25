<template>
    <div>
      <v-toolbar-title style="font-size: 15px;" :class="['elevation-1 rounded-t-lg pa-4 pl-4 w-100', switchTheme.bg, switchTheme.color]">
           Calendar
         </v-toolbar-title>
        
      <VCalendar vc-text-2xl vc-font-bold class="rounded-t-0 tcalendar border-0 elevation-1" style="height: 350px !important;" :is-dark="isDarkTheme"  expanded :attributes="attributes"  />
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

      formData.value.duration_date = durationRange.value;

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
  

  const minDueDate = computed(() => {
  const today = new Date();
  today.setDate(today.getDate() + 3); // Minimum due date is 3 days from now
  return today.toISOString().split('T')[0];
});

const maxDueDate = computed(() => {
  if (!selectedDate2.value) return '';

  const selectedDate = new Date(selectedDate2.value);
  selectedDate.setDate(selectedDate.getDate() - 1); // Maximum due date is 1 day before the selected date
  return selectedDate.toISOString().split('T')[0];
});

  const currentDate = new Date();
  const currentYear = getYear(currentDate);
  
  const academicYears = computed(() => {
    const lastYear = currentYear - 1;
    const nextYear = currentYear + 1;
  
    const currentAcademicYear = `A.Y. ${lastYear}-${currentYear}`;
    const nextAcademicYear = `A.Y. ${currentYear}-${nextYear}`;
  
    return [currentAcademicYear, nextAcademicYear];
  });

  const durationRange = computed(() => {
  if (!selectedDate2.value || !formData.value.duration_date) {
    return '';
  }
  const startDate = new Date(selectedDate2.value);
  const endDate = new Date(startDate);

  // Adjust endDate based on the selected duration
  const duration = parseInt(formData.value.duration_date);
  endDate.setDate(endDate.getDate() + duration - 1);

  const formattedStartDate = format(startDate, 'MMM d, yyyy');
  const formattedEndDate = format(endDate, 'MMM d, yyyy');

  if(formData.value.duration_date == '1 Day'){
    return `${formattedStartDate}`;
  }
  return `${formattedStartDate} - ${formattedEndDate}`;
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
  const selectedDateValue = new Date(date.id);
  const currentDateValue = new Date();
  const oneWeekInMilliseconds = 5 * 24 * 60 * 60 * 1000; // 1 week in milliseconds

  if (selectedDateValue >= currentDateValue && selectedDateValue - currentDateValue >= oneWeekInMilliseconds) {
    dialog1.value = true;
    console.log('Clicked date:', date.id);
    const formattedDate = selectedDateValue.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: '2-digit',
    });
    selectedDate.value = formattedDate;
    selectedDate2.value = date.id;
    console.log(selectedDate2.value);
  } else {
    console.log('Selected date is not within 1 week in advance.');
    snackbarMessage.value = 'NOTE: Select an event date 1 week in advance.';
      snackbar.value = true;
  }
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