<template>
  <v-layout>
    <Sidebar :activeItem.sync="activeItem" />
    <v-main class="mx-2 my-3">
      <div class="d-flex bg-none justify-space-between align-center mx-4 pr-4"> 
        <v-breadcrumbs class="d-flex" :items="items" :class="[ 'pt-1', switchTheme.color]">
        <template v-slot:title="{ item }">
          <RouterLink :to="item.to" :class="['text-decoration-none', switchTheme.color]">
            {{ item.title.toUpperCase() }}
          </RouterLink>
        </template>
      </v-breadcrumbs>
      <routerLink to="/Events"  style="margin-top:-15px !important;"><v-icon color="brown">mdi-close</v-icon></routerLink>
      </div>
      
      <v-row class="px-4 d-flex justify-end">
        <v-col cols="12">
          
        </v-col>
        <v-col cols="md-6">
          <PaidStudents  :title="pageTitle" :event_id="pageEventId" :event_date = "pageEventDate" :payment = "pagePayment" />
        </v-col>
        <v-col cols="md-6">
          <PaymentForm :title="pageTitle" :academic_year="pageAy" :semester="pageSem" :duration_date="pageDuration" :event_title="pageEventTitle" :event_id="pageEventId" :event_date = "pageEventDate" :payment = "pagePayment"/>
        </v-col>
      </v-row>
      <v-card class="ma-4">
          <v-card-title>
            Event Information:
          </v-card-title>
          <v-card-body>
            <v-container class="mb-4 w-100 ml-auto  text-sm-subtitle-1">
              <v-row class="label-value   ">
                <v-col class="label py-0 my-0" cols="6"><span>Event ID:</span></v-col>
                <v-col class="value  text-no-wrap py-0 my-0" cols="6">{{ pageEventId }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Event Title:</span></v-col>
                <v-col class="value  text-wrap py-0 my-0" cols="6">{{ pageEventTitle }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Event Description:</span></v-col>
                <v-col class="value  text-wrap py-0 my-0" cols="6">{{ pageDesc }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Event Date:</span></v-col>
                <v-col class="value py-0 my-0 text-no-wrap" cols="6">{{ pageEventDate }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Event Duration:</span></v-col>
                <v-col class="value py-0 my-0 text-no-wrap" cols="6">{{ pageDuration }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Pay due:</span></v-col>
                <v-col class="value py-0 my-0 text-no-wrap" cols="6">{{ pageDueDate }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Fee:</span></v-col>
                <v-col class="value  py-0 my-0 text-no-wrap" cols="6">PHP {{ pagePayment }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Academic Year:</span></v-col>
                <v-col class="value  py-0 my-0 text-no-wrap" cols="6">{{ pageAy }}</v-col>
              </v-row>
              <v-row class="label-value  ">
                <v-col class="label py-0 my-0" cols="6"><span>Semester:</span></v-col>
                <v-col class="value  py-0 my-0 text-no-wrap" cols="6">{{ pageSem }}</v-col>
              </v-row>
              <v-row  class="label-value  ">
                <v-col class="d-flex justify-center py-0">
                  <v-btn @click="openArchiveDialog(item)" elevation="0" class="" icon>
                <v-icon color="brown">mdi-archive</v-icon>
                <v-tooltip activator="parent" location="bottom">Archive?</v-tooltip>
              </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-card-body>
        </v-card>

          <!-- Archive Dialog -->
    <v-dialog v-model="archiveDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirm Archive</v-card-title>
        <v-card-text>
          Are you sure you want to archive this event? This will also archive the transactions in this event.
        </v-card-text>
        <v-card-actions>
          <v-btn @click="archiveTransaction" color="primary" text>
            Confirm
          </v-btn>
          <v-btn @click="closeArchiveDialog" color="red" text>
            Cancel
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    
 <!-- Snackbar -->
 <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line>
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar.show = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>

    </v-main>
  </v-layout>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/Sidebar.vue";
import PaidStudents from "@/components/PaidStudents.vue";
import PaymentForm from "@/components/PaymentForm.vue";
import { useStore } from 'vuex';

export default {
  data: () => ({
    pageId: null,
    pageTitle: "Details", 
    pageEventTitle: "Details", 
    pageEventId:"",
    archiveDialog: false,
    pageEventDate:'',
    pagePayment:'',
    snackbar: {
        show: false,
        message: '',
        color: '',
      },
  }),
  async created() {
    this.pageId = this.$route.params.pageId;
    await this.fetchEventTitle();
  },
  methods: {
    async fetchEventTitle() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/show-event/${this.pageId}`,
        {
        headers: {
          Authorization: `Bearer ${this.$store.getters.getToken}`,
        },
      });
        this.pageTitle = response.data.event_id;  
        this.pageEventTitle = response.data.title;  
        this.pageEventId = response.data.event_id;  
        this.pageEventDate = response.data.date;  
        this.pageDueDate = response.data.due_date;  
        this.pageAy = response.data.academic_year;  
        this.pageSem = response.data.semester;  
        this.pageDuration = response.data.duration_date;  
        console.log(this.pageEventDate)
        this.pagePayment = response.data.payment;  
        this.pageDesc = response.data.description;  
      } catch (error) {
        console.error("Error fetching event title:", error);
      }
    },
    openArchiveDialog(student) {
      this.selectedStudent = student;
      this.archiveDialog = true;
    },
    closeArchiveDialog() {
      this.archiveDialog = false;
    },
    async archiveTransaction() {
      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/update-event/${this.pageId}`, {
          status: 'ARCHIVED',
        },
        {
          headers: {
            Authorization: `Bearer ${this.$store.getters.getToken}`,
          },
        }
        );
        console.log(response.data);

        // Show success Snackbar
        this.showSnackbar(' Event archived successfully', 'success');

        // Delay for 3 seconds
        setTimeout(() => {
          // Route to /Events after the delay
          this.$router.push('/Events');
        }, 3000); // 3000 milliseconds (3 seconds)
      } catch (error) {
        console.error('Error archiving transaction:', error);

        // Show error Snackbar
        this.showSnackbar('Error archiving event', 'error');
      } finally {
        this.closeArchiveDialog();
      }
    },


    showSnackbar(message, color) {
      this.snackbar.message = message;
      this.snackbar.color = color;
      this.snackbar.show = true;
    },

  },
  components: {
    Sidebar,
    PaymentForm,
    PaidStudents,
    
  },
  computed: {
    user() {
      return this.$store.getters.getUser;
    },
    switchTheme() {
      return this.$store.getters.getSwitchTheme;
    },
    items() {
      return [
        {
          title: "Dashboard",
          to: "/Dashboard",
        },
        {
          title: "Events",
          to: "/Events",
        },
        {
          title: this.pageTitle.toUpperCase(), // Use the dynamically fetched title
          to: `/EventDetails/${this.pageId}`,
        },
      ];
    },
  },
  
};
</script>

<style scoped>
.label {
  text-align: right;
  padding-right: 10px;
}
</style>
