<template>
  <div>
    <!-- First Section -->
    <v-card>
      <v-card-title>
        <span>Masterlist Form</span>
      </v-card-title>
      <v-card-text>
        <v-container>
         
          <v-row>
            <v-col cols="12" class="py-0 my-0 my-0 mb-3">
              <v-dialog width="700">
            <template  v-slot:activator="{ props }">
            <span  v-bind="props" class="d-flex justify-end cursor-pointer text-grey-darken-1 text-decoration-underline text-lg-caption text-no-wrap" ><v-icon>mdi-information-variant</v-icon> Follow the layout for excel before upload. </span>
            </template>

        <template v-slot:default="{ isActive }">
          <v-card title="Excel Layout">
            <v-card-text>
              <v-img src="public/excel_format.png"></v-img>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>

              <v-btn
                text="Close Dialog"
                @click="isActive.value = false"
              ></v-btn>
            </v-card-actions>
          </v-card>
        </template>
      </v-dialog>
              <v-file-input
              style="margin-bottom: -10px !important;"
              class="my-0 py-0"
              show-size
                accept=".xlsx, .xls"
                @change="handleFileChange"
                label="Excel File"
              >
           </v-file-input>
        
            </v-col>
            <v-col cols="12" class="py-0 my-0">
              <v-select v-model="selectedAcademicYear" label="Academic Year*" :items="academicYears"></v-select>
            </v-col>

            <v-col cols="12" class="py-0 my-0">
              <v-select v-model="selectedSemester" label="Semester*" :items="['FIRST SEMESTER', 'SECOND SEMESTER']"></v-select>
            </v-col>

            <v-col cols="12" class="py-0 my-0 justify-center d-flex">
              <v-btn @click="submitForm" color="amber-lighten-2" class="mx-2">Submit</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </div>
     <!-- Snackbar -->
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
import { ref, computed } from 'vue';
import { format, subYears, getYear } from 'date-fns';
import axios from 'axios';
import { useStore } from 'vuex';  

const store = useStore();

const dialog = ref(false);
const selectedFile = ref(null);
const selectedAcademicYear = ref('');
const selectedSemester = ref('');
const snackbar = ref({
  show: false,
  message: '',
  color: '',
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

const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0];
};

const submitForm = () => {
  if (!selectedFile.value || !selectedAcademicYear.value || !selectedSemester.value) {
    alert('Please fill in all required fields.');
    return;
  }

  const formData = new FormData();
  formData.append('excel_file', selectedFile.value);
  formData.append('academic_year', selectedAcademicYear.value);
  formData.append('semester', selectedSemester.value);

  axios.post('http://127.0.0.1:8000/api/upload-excel', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${store.getters.getToken}`,
      },
    })
    .then(response => {
      console.log(response.data);
      showSnackbar('Transaction saved successfully', 'success');
    })
    .catch(error => {
      console.error(error);
      showSnackbar('Error saving payment. Please try again.', 'error');
    })
    .finally(() => {
      selectedFile.value = null;
      selectedAcademicYear.value = '';
      selectedSemester.value = '';
    });
};

const showSnackbar = (message, color) => {
  snackbar.value.message = message;
  snackbar.value.color = color;
  snackbar.value.show = true;
};
</script>

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
