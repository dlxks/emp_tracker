<template>
  <AppLayout title="Records">
    <template #header>
      <!-- Header -->
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <span>Records</span>
        </h2>
      </div>
      <!-- Header -->
    </template>

    <div class="py-0 md:py-12">
      <div class="mx-auto">
        <div class="mx-auto grid w-full grid-cols-12 gap-4 p-1">
          <!-- Left -->
          <!-- <div
            class="col-span-12 rounded-lg border border-gray-400 bg-gray-200 p-16 lg:col-span-4"
          ></div> -->

          <!-- Right -->
          <div class="col-span-12 lg:col-span-12">
            <!-- Table -->
            <div
              class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded"
            >
              <!-- Table Header -->
              <div class="px-4 py-4 bg-gray-200 rounded-t-lg">
                <div class="flex items-center justify-between">
                  <p
                    tabindex="0"
                    class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-900"
                  >
                    Records
                  </p>
                  <div
                    class="flex items-center text-sm font-medium leading-none text-gray-600 hover:bg-gray-300 cursor-pointer rounded"
                  >
                    <!-- Search -->
                    <div class="block">
                      <input
                        type="text"
                        placeholder="Search..."
                        v-model="params.search"
                        class="placeholder-slate-500 text-slate-6900 bg-white rounded text-xs border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
                      />
                    </div>
                    <!-- Search -->
                  </div>
                </div>
              </div>
              <!-- End Table Header -->

              <div class="bg-white py-4 md:py-5">
                <div class="sm:flex items-center justify-between px-4">
                  <div class="flex items-center">
                    <!-- Branch filter -->
                    <div class="block px-3">
                      <span class="text-sm text-gray-500">Branch/College: </span>
                      <select
                        v-model="params.branch_filter"
                        class="placeholder-slate-300 text-slate-600 relative bg-white rounded text-xs border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
                      >
                        <option
                          v-for="branch in branches"
                          :key="branch.branch_name"
                          :value="branch.branch_name"
                        >
                          <span>{{ branch.branch_name }}</span>
                        </option>
                      </select>
                    </div>
                    <!-- Branch filter -->

                    <!-- Divider -->
                    <div class=""></div>
                    <!-- End Divider -->

                    <!-- View filter -->
                    <div class="block px-3">
                      <span class="text-sm text-gray-500 mr-1">Per page: </span>
                      <select
                        ref="perpage"
                        id="perpage"
                        class="placeholder-slate-300 text-slate-800 relative bg-white rounded text-xs border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
                        v-model="params.perpage"
                      >
                        <option
                          v-for="perpage in perpages"
                          :key="perpage"
                          :value="perpage"
                          class="capitalize"
                        >
                          <span>{{ perpage }}</span>
                        </option>
                      </select>
                    </div>
                    <!-- View filter -->

                    <!-- Clear -->
                    <div class="block">
                      <button
                        value="Clear Filter"
                        @click="clearFilters()"
                        v-if="
                          this.filters.search != null ||
                          this.filters.field != null ||
                          this.filters.direction != null ||
                          this.filters.branch_filter != null ||
                          this.filters.perpage != null
                        "
                        class="px-3 py-1 mt-1 text-xs bg-red-500 text-white rounded-full drop-shadow-md hover:bg-red-600 hover:text-gray-100"
                      >
                        <i class="fa-solid fa-xmark mr-1"></i>
                        Clear Filters
                      </button>
                    </div>
                    <!-- End Clear -->
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
                  <!-- End Add Button -->
                </div>

                <div class="mt-7 overflow-x-auto">
                  <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-100">
                      <tr>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div class="flex items-center">
                            <i class="fa-solid fa-building-columns mx-1"></i>
                            <span>Branch/College</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('year')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'year' && params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'year' && params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-calendar mx-1"></i>
                            <span>Year</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('total_graduates')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'total_graduates' &&
                                  params.direction === 'asc'
                                "
                              ></i
                              ><i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'total_graduates' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-user-graduate mx-1"></i>
                            <span>Graduates</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('total_employed')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'total_employed' &&
                                  params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'total_employed' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-briefcase mx-1"></i>
                            <span>Employed</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('total_percentage')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'total_percentage' &&
                                  params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'total_percentage' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-percent mx-1"></i>
                            <span>Percentage</span>
                          </div>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-if="!records.data.length"
                        tabindex="0"
                        class="focus:outline-none h-16 rounded"
                      >
                        <td class="p-4 text-center text-xs text-gray-800" colspan="7">
                          <!-- NO data -->
                          <span class="text-red-500 uppercase text-md"
                            >No data found!</span
                          >
                          <!-- NO data -->
                        </td>
                      </tr>
                      <tr
                        v-for="record in records.data"
                        :key="record.id"
                        tabindex="0"
                        class="text-sm focus:outline-none rounded"
                      >
                        <td class="px-6 py-1">
                          {{ record.branch.branch_name }}
                        </td>
                        <td class="px-6 py-1">
                          {{ record.year }}
                        </td>
                        <td class="px-6 py-1">
                          {{ record.total_graduates }}
                        </td>
                        <td class="px-6 py-1">
                          {{ record.total_employed }}
                        </td>
                        <td class="px-6 py-1">
                          {{ record.total_percentage }}
                        </td>
                        <td class="py-3 text-center">
                          <button
                            @click="view(record)"
                            class="text-emerald-800 bg-emerald-200 hover:text-emerald-600 hover:ring-2 ring-emerald-600/30 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 text-xs py-2 px-3 rounded-full"
                          >
                            <i
                              class="fa-solid fa-eye fa-lg mx-0.5 hidden md:inline-flex"
                            ></i>
                            <span class="mx-0.5">View</span>
                          </button>
                          <button class="rounded-full"></button>

                          <button
                            @click="edit(record, true)"
                            class="text-blue-800 bg-blue-200 hover:text-blue-600 hover:ring-2 ring-blue-600/30 focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 text-xs py-2 px-3 rounded-full"
                          >
                            <i
                              class="fa-solid fa-file-pen fa-lg mx-0.5 hidden md:inline-flex"
                            ></i>
                            <span class="mx-0.5">Edit</span>
                          </button>

                          <button
                            @click="deleteRow(record.id)"
                            class="text-rose-800 bg-rose-200 hover:text-rose-600 hover:ring-2 ring-rose-600/30 focus:ring-2 focus:ring-offset-2 focus:ring-rose-600 text-xs py-2 px-2 rounded-full"
                          >
                            <i
                              class="fa-solid fa-trash fa-lg mx-0.5 hidden md:inline-flex"
                            ></i>
                            <span class="mx-0.5">Remove</span>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- End Table Header -->

              <!-- Main Table -->
              <!-- End Main Table -->

              <!-- Pagination -->
              <div class="mx-auto sm:px-6 lg:px-8">
                <jet-pagination class="m-1" :links="records.links" />
              </div>
              <!-- End Pagination -->
            </div>

            <!-- End Table -->
          </div>
        </div>

        <!--  -->
        <div class="mx-auto grid w-full grid-cols-12 gap-4 p-1">
          <!-- Left -->
          <div class="col-span-12 lg:col-span-8 border rounded-lg">
            <!-- Table Header -->
            <div class="px-4 py-4 bg-gray-200 rounded-t-lg">
              <div class="flex items-center justify-between">
                <p
                  tabindex="0"
                  class="focus:outline-none text-base sm:text-md md:text-lg lg:text-xl font-bold leading-normal text-gray-900"
                >
                  Number of Employed Graduates
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
                <vue3-chart-js ref="chartRef" v-bind="{ ...sideChart }" />
              </div>
            </div>
          </div>
          <!-- End Left -->

          <!-- Right -->
          <div class="col-span-12 rounded-lg border lg:col-span-4">
            <!-- Table Header -->
            <div class="px-4 py-4 bg-gray-200 rounded-t-lg">
              <div class="flex items-center justify-between">
                <p
                  tabindex="0"
                  class="focus:outline-none text-base sm:text-md md:text-lg lg:text-xl font-bold leading-normal text-gray-900"
                >
                  Graduates
                </p>
              </div>

              <div class="mx-auto w-auto object-none object-center">
                <div class="w-auto px-2 text-center"></div>
                <div class="pb-2 sm:pb-0 bg-white">
                  <vue3-chart-js v-bind="{ ...rightChart }" />
                </div>
              </div>
            </div>
            <!-- End Table Header -->
          </div>
          <!-- End Right -->
        </div>
      </div>
    </div>
  </AppLayout>

  <dialog-modal :show="isOpen" @close="openModal(false)">
    <template #title>
      <span v-show="!editMode"> Add New Record </span>
      <span v-show="editMode"> Update Record </span>
    </template>

    <template #content>
      <!-- Branch/College -->
      <div class="mb-4">
        <jet-label for="branch" value="Branch/College" />
        <select
          v-model="form.branch"
          v-show="!editMode"
          @keyup.enter="save(form)"
          :required="true"
          ref="branch"
          id="branch"
          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >
          <option
            class="capitalize"
            v-for="branch in branches"
            :key="branch.id"
            v-bind:value="branch"
          >
            <span>{{ branch.branch_name }} - {{ branch.branch_desc }}</span>
          </option>
        </select>
        <select
          v-model="form.branch"
          v-show="editMode"
          @keyup.enter="update(form)"
          :required="true"
          ref="branch"
          id="branch"
          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >
          <option
            class="capitalize"
            v-for="branch in branches"
            :key="branch.id"
            v-bind:value="branch"
          >
            <span>{{ branch.branch_name }} - {{ branch.branch_desc }}</span>
          </option>
        </select>
      </div>

      <!-- Year -->
      <div class="mb-4">
        <jet-label for="year" value="Year" />
        <Datepicker
          v-model="form.year"
          year-picker
          v-show="!editMode"
          @keyup.enter="save(form)"
          class="mt-1 block w-full"
          id="year"
          ref="year"
        />
        <Datepicker
          v-model="form.year"
          year-picker
          v-show="editMode"
          @keyup.enter="update(form)"
          class="mt-1 block w-full"
          id="year"
          ref="year"
        />
      </div>

      <!-- Graduates -->
      <div class="mb-4">
        <jet-label for="graduates" value="No. of Graduates" />
        <jet-input
          id="graduates"
          type="number"
          class="mt-1 block w-full"
          v-show="!editMode"
          v-model="form.total_graduates"
          @keyup.enter="save(form)"
        />
        <jet-input
          id="graduates"
          type="number"
          class="mt-1 block w-full"
          v-show="editMode"
          v-model="form.total_graduates"
          @keyup.enter="update(form)"
        />
      </div>
      <!-- Graduates -->
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
import Welcome from "@/Components/Welcome.vue";
import JetButton from "@/Components/Button.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetLabel from "@/Components/InputLabel.vue";
import JetInput from "@/Components/Input.vue";
import JetPagination from "@/Jetstream/Pagination.vue";
import DialogModal from "@/Components/DialogModal.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Vue3ChartJs from "@j-t-mcc/vue3-chartjs";

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
    Vue3ChartJs,
  },

  props: {
    records: Object,
    filters: Object,
    branches: Object,
    chartdata: Object,
  },

  setup(data) {
    const chartRef = ref(null);

    // Main Chart
    const sideChart = {
      id: "sideChart",
      type: "line",
      data: {
        labels: data.chartdata.labels,
        datasets: [
          {
            label: "Graduates",
            data: data.chartdata.graduates,
            backgroundColor: "rgba(3, 66, 252, 0.7)",
            borderColor: "rgba(0,0,0,0.2)",
            hoverOffset: 4,
            pointStyle: "circle",
            pointRadius: 10,
            pointHoverRadius: 15,
            tension: 0.5,
            fill: false,
            tension: 0.5,
          },
          {
            label: "Employed Percentage",
            data: data.chartdata.percentage,
            backgroundColor: "rgba(252, 3, 3, 0.7)",
            borderColor: "rgba(0,0,0,0.2)",
            hoverOffset: 4,
            pointStyle: "circle",
            pointRadius: 10,
            pointHoverRadius: 15,
            tension: 0.5,
            fill: false,
            tension: 0.5,
          },
        ],
      },
      options: {
        responsive: true,

        plugins: {
          legend: {
            position: "top",
          },
        },

        layout: {
          padding: 20,
        },
      },
    };

    const updateChart = () => {
      sideChart.data.labels = data.chartdata.labels;
      sideChart.data.datasets = [
        {
          label: "Graduates",
          data: data.chartdata.graduates,
          backgroundColor: "rgba(3, 66, 252, 0.7)",
          borderColor: "rgba(0,0,0,0.2)",
          hoverOffset: 4,
          pointStyle: "circle",
          pointRadius: 10,
          pointHoverRadius: 15,
          tension: 0.5,
          fill: false,
          tension: 0.5,
        },
        {
          label: "Employed Percentage",
          data: data.chartdata.percentage,
          backgroundColor: "rgba(252, 3, 3, 0.7)",
          borderColor: "rgba(0,0,0,0.2)",
          hoverOffset: 4,
          pointStyle: "circle",
          pointRadius: 10,
          pointHoverRadius: 15,
          tension: 0.5,
          fill: false,
          tension: 0.5,
        },
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
      sideChart,
      chartRef,
      updateChart,
      exportChart,
    };
  },

  extends: shared,

  data() {
    return {
      params: {
        search: this.filters.search,
        field: this.filters.field,
        direction: this.filters.direction,
        perpage: this.filters.perpage,
        branch_filter: this.filters.branch,
      },

      form: this.$inertia.form({
        branch: this.branches,
        year: this.year,
        total_graduates: this.total_graduates,
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
    save: function (record) {
      this.$inertia.visit("/admin/record", {
        method: "post",
        data: record,
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

    // View function
    view: function (id) {
      this.$inertia.visit(route("admin.record.show", id));
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
