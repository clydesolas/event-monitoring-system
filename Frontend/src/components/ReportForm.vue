<template>
  <div>
    <!-- First Section -->
    <v-card>
      <v-card-title>
        <span>Generate Report</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <!-- Academic Year -->
            <v-col cols="12" class="py-0 my-0">
              <v-select
                v-model="selectedAcademicYear"
                :items="academicYearOptions"
                label="Academic Year*"
                required
              ></v-select>
            </v-col>

            <!-- Semester -->
            <v-col cols="12" class="py-0 my-0">
              <v-select
                v-model="selectedSemester"
                :items="semesterOptions"
                label="Semester*"
                required
              ></v-select>
            </v-col>

            <!-- Event -->
            <v-col cols="12" class="py-0 my-0">
              <v-select
                v-model="selectedEvent"
                :items="eventOptions"
                label="Event*"
                item-text="title"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" class="py-0 my-0 justify-center d-flex">
              <v-btn color="green" class="mx-2" @click="generateReport">Excel</v-btn>
              <v-btn color="red" @click="generatePdf">Pdf</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  
  props: {
    modelValue: Object, // The prop to bind the v-model
  },
  emits: ['update:modelValue'],
  data() {
    return {
      selectedAcademicYear: 'all',
      selectedSemester: 'all',
      selectedEvent: 'all',
      academicYearOptions: [], // Initialize as an empty array
      semesterOptions: [], // Initialize as an empty array
      eventOptions: [], // Initialize as an empty array
    };
  },
  mounted() {
    // Fetch data for Academic Year and Semester from the APIs
    this.fetchAcademicYearData();
    this.fetchSemesterData();

    // Fetch event data from the API
    this.fetchEventData();
  },
  watch: {
    selectedAcademicYear(newValue) {
      console.log(newValue)
      this.emitInput('selectedAcademicYear', newValue);
    },
    selectedSemester(newValue) {
      console.log(newValue)
      this.emitInput('selectedSemester', newValue);
    },
    selectedEvent(newValue) {
      console.log(newValue)
      this.emitInput('selectedEvent', newValue);
    },
  },
  methods: {
  async fetchAcademicYearData() {
    try {
      const response = await this.$axios.get('http://127.0.0.1:8000/api/academic-year/{academic_year}',
      {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
      this.academicYearOptions = ['all', ...response.data];
    } catch (error) {
      console.error('Error fetching academic year data:', error);
    }
  },
  async fetchSemesterData() {
    try {
      const response = await this.$axios.get('http://127.0.0.1:8000/api/semester/{semester}',
      {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
      this.semesterOptions = ['all', ...response.data];
    } catch (error) {
      console.error('Error fetching semester data:', error);
    }
  },
  async fetchEventData() {
    try {
      const response = await this.$axios.get('http://127.0.0.1:8000/api/events-get-all',
      {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        });
      this.eventOptions = [{ id: 'all', title: 'All Events' }, ...response.data.map(event => ({ id: event.event_id, title: event.title }))];
    } catch (error) {
      console.error('Error fetching event data:', error);
    }
  },
  emitInput(propName, value) {
  // Update the corresponding data property
  this[propName] = value;

  // Emit the updated data
  this.$emit('update:modelValue', {
    selectedAcademicYear: this.selectedAcademicYear,
    selectedSemester: this.selectedSemester,
    selectedEvent: this.selectedEvent,
  });
},

downloadFile(response, filename) {
      const blob = new Blob([response.data], { type: response.headers['content-type'] });
      const link = document.createElement('a');
      link.href = window.URL.createObjectURL(blob);
      link.download = filename;
      link.click();
    },

    async generateReport() {
      try {
        const response = await this.$axios.post('http://127.0.0.1:8000/api/transactions-export-excel', 
        null,{
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        },
        {
          academic_year: this.selectedAcademicYear,
          semester: this.selectedSemester,
          event_id: this.selectedEvent,
        }, { responseType: 'arraybuffer' });

        this.downloadFile(response, 'report.xlsx');
      } catch (error) {
        console.error('Error generating Excel report:', error);
      }
    },

    async generatePdf() {
      try {
        const response = await this.$axios.post('http://127.0.0.1:8000/api/transactions-export-pdf', {
          academic_year: this.selectedAcademicYear,
          semester: this.selectedSemester,
          event_id: this.selectedEvent,
        }, {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        },
        { responseType: 'arraybuffer' });

        this.downloadFile(response, 'report.pdf');
      } catch (error) {
        console.error('Error generating PDF report:', error);
      }
    },
  },
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
