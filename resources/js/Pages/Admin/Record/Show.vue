<template>
  <AppLayout title="Record">
    <template #header>
      <!-- Header -->
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <span>Records</span>
        </h2>
      </div>
      <!-- Header -->
    </template>

    <div class="">
      <div class="mx-auto">
        <div class="mx-auto grid w-full grid-cols-12 gap-4 p-1">
          <!-- Left -->
          <div class="col-span-12 lg:col-span-4 border rounded-lg">
            <!-- Table Header -->
            <div class="px-4 py-4 bg-gray-200 rounded-t-lg">
              <div class="flex items-center justify-between">
                <Link
                  :href="route('admin.record.index')"
                  class="text-sm rounded-full text-gray-500 hover:text-green-800"
                >
                  <i class="fa-solid fa-angle-left"></i>
                  Back
                </Link>
                <p
                  tabindex="0"
                  class="focus:outline-none text-base sm:text-md md:text-lg lg:text-xl font-bold leading-normal text-gray-900"
                >
                  Data Chart
                </p>
              </div>
            </div>
            <!-- End Table Header -->

            <div class="mx-auto w-auto object-none object-center">
              <div class="w-auto px-2 py-4 text-center">
                <button
                  type="submit"
                  @click="exportChart"
                  class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 font-medium rounded-l-md text-sm px-4 py-2 text-center"
                >
                  Export Chart as PNG
                </button>
                <button
                  @click="updateChart"
                  class="text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 font-medium rounded-r-md text-sm px-4 py-2 text-center"
                >
                  Update Chart
                </button>
              </div>
              <div class="pb-2 sm:pb-0 bg-white">
                <vue3-chart-js ref="chartRef" v-bind="{ ...doughnutChart }" />
              </div>
            </div>
          </div>
          <!-- End Left -->

          <!-- Right -->
          <div class="col-span-12 rounded-lg border lg:col-span-8">
            <!-- Table Header -->
            <div class="px-4 py-4 bg-gray-200 rounded-t-lg">
              <div class="flex items-center justify-between">
                <p
                  tabindex="0"
                  class="focus:outline-none text-base sm:text-md md:text-lg lg:text-xl font-bold leading-normal text-gray-900"
                >
                  Employed
                </p>
              </div>
            </div>
            <!-- End Table Header -->

            <div class="bg-white py-4 md:py-5">
              <div class="sm:flex items-center justify-between px-4">
                <div class="flex items-center">
                  <!-- Left Details -->
                  <div class="block px-3">
                    <div class="text-gray-600">
                      Branch:
                      <span class="text-emerald-800 text-xl font-bold">{{
                        branch.branch_name
                      }}</span>
                    </div>
                    <div class="text-gray-600">
                      Year:
                      <span class="text-emerald-800 text-xl font-bold">{{
                        record.year
                      }}</span>
                    </div>
                    <div class="text-gray-600">
                      Total Number of Graduates:
                      <span class="text-emerald-800 text-xl font-bold">{{
                        record.total_graduates
                      }}</span>
                    </div>
                  </div>
                  <!-- End Left Details -->
                </div>

                <!-- Add Button -->
                <button
                  @click="openModal(true)"
                  class="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 mt-2 sm:mt-0 inline-flex items-start justify-start px-4 py-2 bg-blue-700 hover:bg-blue-600 focus:outline-none rounded-full drop-shadow-sm"
                >
                  <p class="text-sm font-medium leading-none text-white">
                    <i class="fa-solid fa-circle-plus mr-2"></i> Add Record
                  </p>
                </button>
                <!-- Add Button -->
              </div>

              <div class="mt-7 overflow-x-auto">
                <!-- Main Table -->
                <table class="w-full whitespace-nowrap">
                  <thead class="bg-gray-100">
                    <tr>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <div class="flex items-center">
                          <i class="fa-solid fa-calendar-day mx-1"></i>
                          <span>Quarter</span>
                        </div>
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <div class="flex items-center">
                          <i class="fa-solid fa-briefcase mx-1"></i>
                          <span>Employed</span>
                        </div>
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <div class="flex items-center">
                          <i class="fa-solid fa-percent mx-1"></i>
                          <span>Percentage</span>
                        </div>
                      </th>
                      <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="!quarterlies.length">
                      <td class="p-4 text-center text-sm text-gray-800" colspan="7">
                        <!-- NO data -->
                        <span class="text-red-500 uppercase text-xl">No data found!</span>
                        <!-- NO data -->
                      </td>
                    </tr>
                    <tr v-for="quarterly in quarterlies" :key="quarterly.id">
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ quarterly.quarter }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ quarterly.employed }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ quarterly.percentage }}
                      </td>
                      <td
                        class="px-6 py-1 space-x-1 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <button
                          @click="deleteRow(quarterly.id)"
                          class="inline-flex items-center px-2 py-2 text-red-800 text-sm font-medium rounded-md"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- End Main Table -->
              </div>
            </div>
          </div>
          <!-- End Right -->
        </div>
      </div>
    </div>
  </AppLayout>

  <dialog-modal :show="isOpen" @close="openModal(false)">
    <template #title>
      <span v-show="!editMode"> Add New Record </span>
      <span v-show="editMode"> Update This Record </span>
    </template>

    <template #content>
      <!-- Branch/College -->
      <div class="mb-4">
        <jet-label for="quarter" value="Quarter" />
        <select
          ref="quarter"
          id="quarter"
          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
          v-model="form.quarter"
        >
          <option
            v-for="quarter in quarters"
            :key="quarter"
            :value="quarter"
            class="capitalize"
          >
            <span>{{ quarter }}</span>
          </option>
        </select>
      </div>

      <!-- Employed -->
      <div class="mb-4">
        <jet-label for="employed" value="Number of Employed" />
        <jet-input
          id="employed"
          type="number"
          class="mt-1 block w-full"
          v-show="!editMode"
          v-model="form.employed"
          @keyup.enter="save(form)"
        />
        <jet-input
          id="employed"
          type="number"
          class="mt-1 block w-full"
          v-show="editMode"
          v-model="form.employed"
          @keyup.enter="update(form)"
        />
      </div>
      <!-- Employed -->

      <!-- hidden -->
      <div class="mb-4">
        <!-- <jet-label for="record_id" value="Record ID" /> -->
        <jet-input
          id="record_id"
          type="hidden"
          class="mt-1 block w-full"
          v-model="form.record_id"
          disabled
        />
      </div>
      <div class="mb-4">
        <!-- <jet-label for="graduates" value="Graduates" /> -->
        <jet-input
          id="graduates"
          type="hidden"
          class="mt-1 block w-full"
          v-model="form.total_graduates"
          disabled
        />
      </div>
      <div class="mb-4">
        <!-- <jet-label for="year" value="Year" /> -->
        <Datepicker
          v-model="form.year"
          year-picker
          class="mt-1 w-full hidden"
          id="year"
          ref="year"
          disabled
        />
      </div>
      <!-- hidden -->
    </template>

    <template #footer>
      <jet-secondary-button @click="openModal(false)"> Cancel </jet-secondary-button>

      <jet-button
        class="ml-2"
        v-show="!editMode"
        @click="save(form)"
        :class="{ 'opacity-25': disabled }"
        :disabled="disabled"
      >
        Save
      </jet-button>

      <jet-button
        class="ml-2"
        :class="{ 'opacity-25': disabled }"
        :disabled="disabled"
        v-show="editMode"
        @click="update(form)"
      >
        Update
      </jet-button>
    </template>
  </dialog-modal>
</template>

<script>
import { pickBy, throttle } from "lodash";
import { ref } from "vue";
import shared from "@/Scripts/shared";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Welcome from "@/Components/Welcome.vue";
import JetButton from "@/Components/Button.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetLabel from "@/Components/InputLabel.vue";
import JetInput from "@/Components/Input.vue";
import JetPagination from "@/Jetstream/Pagination.vue";
import NavLink from "@/Components/NavLink.vue";
import DialogModal from "@/Components/DialogModal.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Vue3ChartJs from "@j-t-mcc/vue3-chartjs";
import { objectToString } from "@vue/shared";

export default {
  name: "App",
  components: {
    AppLayout,
    Link,
    Welcome,
    JetButton,
    JetSecondaryButton,
    JetLabel,
    JetInput,
    JetPagination,
    NavLink,
    DialogModal,
    Datepicker,
    Vue3ChartJs,
  },

  props: {
    record: Object,
    branch: Object,
    quarterlies: Object,
    data: Object,
  },

  setup(quarterlies) {
    const chartRef = ref(null);

    const doughnutChart = {
      id: "doughnutChart",
      type: "pie",
      data: {
        labels: quarterlies.data.labels,
        datasets: [
          {
            label: "Percentage",
            data: quarterlies.data.percentage,
            // backgroundColor: "rgba(3, 66, 252, 0.7)",
            backgroundColor: quarterlies.data.colors,
            offset: 4,
          },
          // {
          //   label: "Number of employed",
          //   data: quarterlies.data.employed,
          //   backgroundColor: "rgba(252, 3, 3, 0.7)",
          //   offset: 4,
          // },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Percentage of employed graduates per quarter.",
          },
        },
      },
    };

    const updateChart = () => {
      doughnutChart.options.plugins.title = {
        text: "Percentage employed graduates per quarter.",
        display: true,
      };
      doughnutChart.data.labels = quarterlies.data.labels;
      doughnutChart.data.datasets = [
        {
          label: "Percentage",
          data: quarterlies.data.percentage,
          backgroundColor: quarterlies.data.colors,
          offset: 4,
        },
        // {
        //   label: "Number of employed",
        //   data: quarterlies.data.employed,
        //   backgroundColor: "rgba(252, 3, 3, 0.7)",
        //   offset: 4,
        // },
      ];

      chartRef.value.update(250);
    };

    const exportChart = () => {
      let a = document.createElement("a");
      a.href = chartRef.value.chartJSState.chart.toBase64Image();
      a.download = "chart-export.png";
      a.click();
      a = null;
    };

    return {
      doughnutChart,
      updateChart,
      exportChart,
      chartRef,
    };
  },

  extends: shared,

  data() {
    return {
      form: this.$inertia.form({
        record_id: this.record.id,
        total_graduates: this.record.total_graduates,
        year: this.record.year,
        quarter: this.quarter,
        employed: this.employed,
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
    save: function (quarterly) {
      this.$inertia.visit("/admin/quarterly", {
        method: "post",
        data: quarterly,
        onSuccess: () => {
          this.disabledClick(false), this.openModal(false);
        },
        preserveScroll: true,
        preserveState: true,
      });
    },

    // Edit mode function
    edit: function (quarterly, status) {
      this.form = Object.assign({}, quarterly);
      this.editMode = true;
      this.openModal(status);
    },

    // Update function
    update: function (quarterly) {
      this.$inertia.visit("/admin/quarterly/" + quarterly.id, {
        method: "put",
        data: quarterly,
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
      this.$inertia.visit("/admin/quarterly/" + id, {
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
