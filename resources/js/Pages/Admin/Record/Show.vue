<template>
  <AppLayout title="Record">
    <template #header>
      <!-- Header -->
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Year: <span class="">{{ record.year }}</span>
        </h2>
      </div>
      <!-- Header -->
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Controls -->
        <div class="mx-auto sm:px-6 lg:px-8">
          <div class="mx-auto md:flex md:justify-between md:items-center px-5 py-3">
            <div class="pb-2 sm:pb-0"></div>

            <div class="text-right">
              <jet-button @click="openModal(true)"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                Add Record
              </jet-button>
            </div>
          </div>
        </div>
        <!-- Controls -->
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
              <div
                class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { pickBy, throttle } from "lodash";
import shared from "@/Scripts/shared";
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import JetButton from "@/Components/Button.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetLabel from "@/Components/InputLabel.vue";
import JetInput from "@/Components/Input.vue";
import JetPagination from "@/Jetstream/Pagination.vue";
import DialogModal from "@/Components/DialogModal.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
  components: {
    AppLayout,
    Welcome,
    JetButton,
    JetSecondaryButton,
    JetLabel,
    JetInput,
    JetPagination,
    DialogModal,
    Datepicker,
  },

  props: {
    record: Object,
  },

  extends: shared,

  data() {
    return {
      form: this.$inertia.form({
        branch: this.branches,
        year: this.year,
        total_graduates: this.total_graduates,
        total_employed: this.total_employed,
        total_unemployed: this.total_unemployed,
        total_untracked: this.total_untracked,
      }),

      isOpen: false,
      disabled: null,
      editMode: false,
    };
  },

  methods: {
    // Disable function
    disabledClick: function (s) {
      this.disabled = s;
    },

    // Modal function
    openModal: function (status) {
      if (status == true) {
        this.isOpen = true;
      } else if (status == false) {
        this.form = {};
        this.isOpen = false;
        this.editMode = false;
      }

      return this.isOpen;
    },

    // Sort function
    sort(field) {
      this.params.field = field;
      this.params.direction = this.params.direction === "asc" ? "desc" : "asc";
    },

    // Save function
    save: function (course) {
      this.$inertia.visit("/admin/record", {
        method: "post",
        data: course,
        onBefore: () => {
          this.disabledClick(true);
        },
        onSuccess: () => {
          this.disabledClick(false), this.openModal(false), (this.form = {});
        },
        preserveScroll: true,
        preserveState: true,
      });
    },

    // Edit mode function
    edit: function (record, status) {
      this.form = Object.assign({}, record);
      this.editMode = true;
      this.openModal(status);
    },

    // Update function
    update: function (record) {
      this.$inertia.visit("/admin/record/" + record.id, {
        method: "put",
        data: record,
        onBefore: () => {
          this.disabledClick(true);
        },
        onSuccess: () => {
          this.disabledClick(false), this.openModal(false);
        },
        onFinish: () => (this.form = {}),
        preserveScroll: true,
      });
    },

    // Delete function
    deleteRow: function (id) {
      this.$inertia.visit("/admin/record/" + id, {
        method: "delete",
        preserveScroll: true,
        onBefore: () => {
          this.disabledClick(true);
        },
        onSuccess: () => {
          this.disabledClick(false);
        },
      });
    },

    //Clear filters
    clearFilters: function () {
      this.$inertia.get(this.route("admin.record.index"), {});
    },
  },

  watch: {
    params: {
      handler: throttle(function () {
        let params = pickBy(this.params);

        this.$inertia.get(this.route("admin.record.index"), params, {
          replace: true,
          preserveState: true,
        });
      }, 150),
      deep: true,
    },
  },
};
</script>
