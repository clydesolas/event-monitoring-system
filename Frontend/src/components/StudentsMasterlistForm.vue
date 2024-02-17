<template>
    <div>
      <!-- First Section -->
      <v-card>
        <v-card-title>
          <span >Masterlist Form</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" class="py-0 my-0 ">
                <v-file-input
                    show-size
                    accept=".xsls"
                    counter
                    multiple
                    label="Excel File"
                ></v-file-input>
              </v-col>
              
              <v-col cols="12"  class="py-0 my-0 ">
                <v-select
                label="Academic Year*"
                :items="academicYears"
                ></v-select>
              </v-col>
              <v-col cols="12"  class="py-0 my-0 ">
                <v-select
                label="Semester*"
                :items="['FIRST SEMESTER', 'SECOND SEMESTER']"
                >
              </v-select>
              </v-col>
              <v-col cols="12" class="py-0 my-0 justify-center d-flex">
                <v-btn color="amber-lighten-2" class="mx-2">Submit</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </div>
  </template>
  <script setup>
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
  