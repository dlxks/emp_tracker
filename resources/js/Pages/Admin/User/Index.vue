<template>
  <AppLayout title="User Management">
    <template #header>
      <!-- Header -->
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <span>User Management</span>
        </h2>
      </div>
      <!-- Header -->
    </template>

    <div class="">
      <div class="mx-auto">
        <div class="mx-auto grid w-full grid-cols-12 gap-4 p-1">
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
                    User Accounts
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
                      <i class="fa-solid fa-circle-plus mr-2"></i>
                      Add User
                    </p>
                  </button>
                  <!-- End Add Button -->
                </div>
                <!-- End Table Header -->

                <!-- Main Table -->
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
                            @click="sort('name')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'name' && params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'name' && params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-pen-nib mx-1"></i>
                            <span>Name</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('email')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'email' &&
                                  params.direction === 'asc'
                                "
                              ></i
                              ><i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'email' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-envelope mx-1"></i>
                            <span>Email</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('phone')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'phone' &&
                                  params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'phone' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-mobile mx-1"></i>
                            <span>Phone Number</span>
                          </div>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          <div
                            class="cursor-pointer flex items-center"
                            @click="sort('role')"
                          >
                            <div>
                              <i
                                class="fa-solid fa-arrow-down-short-wide"
                                v-if="
                                  params.field === 'role' &&
                                  params.direction === 'asc'
                                "
                              ></i>
                              <i
                                class="fa-solid fa-arrow-up-wide-short"
                                v-if="
                                  params.field === 'role' &&
                                  params.direction === 'desc'
                                "
                              ></i>
                            </div>
                            <i class="fa-solid fa-users mx-1"></i>
                            <span>Role</span>
                          </div>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-if="!users.data.length"
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
                        v-for="user in users.data"
                        :key="user.id"
                        tabindex="0"
                        class="text-sm focus:outline-none rounded"
                      >
                        <td class="px-6 py-1">
                          {{ user.branch.branch_name }}
                        </td>
                        <td class="px-6 py-1">
                          {{ user.name }}
                        </td>
                        <td class="px-6 py-1">
                          {{ user.email }}
                        </td>
                        <td class="px-6 py-1">
                          {{ user.phone }}
                        </td>
                        <td class="px-6 py-1">
                          {{ user.role }}
                        </td>
                        <td class="py-3 text-center">

                          <button
                            @click="edit(user, true)"
                            class="text-blue-800 bg-blue-200 hover:text-blue-600 hover:ring-2 ring-blue-600/30 focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 text-xs py-2 px-3 rounded-full"
                          >
                            <i
                              class="fa-solid fa-file-pen fa-lg mx-0.5 hidden md:inline-flex"
                            ></i>
                            <span class="mx-0.5">Edit</span>
                          </button>

                          <button
                            @click="deleteRow(user.id)"
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

                <!-- End Main Table -->
              </div>
            </div>
            <!-- End Table -->
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <dialog-modal :show="isOpen" @close="openModal(false)">
    <template #title>
      <span v-show="!editMode"> Add New User </span>
      <span v-show="editMode"> Update User </span>
    </template>

    <template #content>
      <!-- Branch/College -->
      <div class="mb-4">
        <jet-label for="branch" value="Branch/College" />
        <select
          v-model="form.branch"
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

      <!-- Name -->
      <div class="mb-4">
        <jet-label for="name" value="Name " />
        <jet-input
          id="name"
          ref="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
        />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <jet-label for="email" value="Email " />
        <jet-input
          id="email"
          ref="email"
          type="text"
          class="mt-1 block w-full"
          v-model="form.email"
        />
      </div>

      <!-- Phone Number -->
      <div class="mb-4">
        <jet-label for="phone" value="Phone Number" />
        <jet-input
          id="phone"
          type="number"
          class="mt-1 block w-full"
          v-model="form.phone"
        />
      </div>

      <!-- Password -->
      <div class="mb-4">
        <jet-label for="password" value="Password" />
        <jet-input
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />
      </div>

      <!-- Confirm Password -->
      <div class="mb-4">
        <jet-label for="password_confirmation" value="Confirm Password" />
        <jet-input
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />
      </div>

      <!-- Role -->
      <div class="mb-4">
        <jet-label for="role" value="Role" />
        <select
          ref="role"
          id="role"
          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
          v-model="form.role"
        >
          <option v-for="role in roles" :key="role" :value="role" class="capitalize">
            <span>{{ role }}</span>
          </option>
        </select>
      </div>
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
import shared from "@/Scripts/shared";
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import JetButton from "@/Components/Button.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetLabel from "@/Components/InputLabel.vue";
import JetInput from "@/Components/Input.vue";
import JetPagination from "@/Jetstream/Pagination.vue";
import DialogModal from "@/Components/DialogModal.vue";

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
  },

  props: {
    users: Object,
    filters: Object,
    branches: Object,
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
        branch: "",
        name: "",
        email: "",
        phone: "",
        role: "",
        password: "",
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
    save: function (user) {
      this.$inertia.visit("/admin/user", {
        method: "post",
        data: user,
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
    edit: function (user, status) {
      this.form = Object.assign({}, user);
      this.editMode = true;
      this.openModal(status);
    },

    // Update function
    update: function (user) {
      this.$inertia.visit("/admin/user/" + user.id, {
        method: "put",
        data: user,
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
      this.$inertia.visit("/admin/user/" + id, {
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
      this.$inertia.get(this.route("admin.user.index"), {});
    },
  },

  watch: {
    params: {
      handler: throttle(function () {
        let params = pickBy(this.params);

        this.$inertia.get(this.route("admin.user.index"), params, {
          replace: true,
          preserveState: true,
        });
      }, 150),
      deep: true,
    },
  },
};
</script>
