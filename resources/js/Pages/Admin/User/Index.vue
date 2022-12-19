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

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Controls -->
        <div class="mx-auto sm:px-6 lg:px-8">
          <div class="mx-auto md:flex md:justify-between md:items-center px-5 py-3">
            <div class="pb-2 sm:pb-0">
              <!-- Search -->
              <div class="block">
                <span class="text-sm text-gray-500">Search: </span>
                <jet-input
                  type="text"
                  placeholder="Search..."
                  v-model="params.search"
                  class="placeholder-slate-300 text-slate-600 relative bg-white rounded text-sm border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
                />
              </div>
              <!-- Search -->

              <!-- View filter -->
              <div class="block">
                <span class="text-sm text-gray-500 mr-1">Show</span>
                <select
                  ref="perpage"
                  id="perpage"
                  class="placeholder-slate-300 text-slate-600 relative bg-white rounded text-sm border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
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
                <span class="text-sm text-gray-500 ml-1">Entries</span>
              </div>
              <!-- View filter -->

              <!-- Branch filter -->
              <div class="block">
                <span class="text-sm text-gray-500">Branch/College: </span>
                <select
                  v-model="params.branch_filter"
                  class="placeholder-slate-300 text-slate-600 relative bg-white rounded text-sm border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
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

              <!-- clear -->
              <div class="block">
                <jet-button
                  value="Clear Filter"
                  @click="clearFilters()"
                  v-if="
                    this.filters.search != null ||
                    this.filters.field != null ||
                    this.filters.direction != null ||
                    this.filters.branch_filter != null ||
                    this.filters.perpage != null
                  "
                  class="px-2 py-1 bg-white rounded text-sm border-0 mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow"
                >
                  Clear Filters
                </jet-button>
              </div>
              <!-- clear -->
            </div>

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
                Add User
              </jet-button>
            </div>
          </div>
        </div>
        <!-- Controls -->
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
              <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <span
                          class="cursor-pointer inline-flex"
                          @click="sort('branch_name')"
                        >
                          <div>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'branch_name' &&
                                params.direction === 'asc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                              />
                            </svg>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'branch_name' &&
                                params.direction === 'desc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                              />
                            </svg>
                          </div>
                          <div>Branch/College</div>
                        </span>
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <span class="cursor-pointer inline-flex" @click="sort('name')">
                          <div>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="params.field === 'name' && params.direction === 'asc'"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                              />
                            </svg>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'name' && params.direction === 'desc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                              />
                            </svg>
                          </div>
                          <div>Name</div>
                        </span>
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <span class="cursor-pointer" @click="sort('email')">
                          <div class="inline-block">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'email' && params.direction === 'asc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                              />
                            </svg>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'email' && params.direction === 'desc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                              />
                            </svg>
                          </div>
                          <div class="inline-block">Email</div></span
                        >
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <span class="cursor-pointer" @click="sort('phone')">
                          <div class="inline-block">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'phone' && params.direction === 'asc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                              />
                            </svg>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'phone' && params.direction === 'desc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                              />
                            </svg>
                          </div>
                          <div class="inline-block">Phone Number</div></span
                        >
                      </th>
                      <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        <span class="cursor-pointer" @click="sort('role')">
                          <div class="inline-block">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="params.field === 'role' && params.direction === 'asc'"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                              />
                            </svg>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-4 w-4"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                              stroke-width="2"
                              v-if="
                                params.field === 'role' && params.direction === 'desc'
                              "
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                              />
                            </svg>
                          </div>
                          <div class="inline-block">Role</div></span
                        >
                      </th>
                      <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="!users.data.length">
                      <td class="p-4 text-center text-sm text-gray-800" colspan="7">
                        <!-- NO data -->
                        <span class="text-red-500 uppercase text-xl">No data found!</span>
                        <!-- NO data -->
                      </td>
                    </tr>
                    <tr v-for="user in users.data" :key="user.id">
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ user.branch.branch_name }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ user.name }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ user.email }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ user.phone }}
                      </td>
                      <td class="px-6 py-1 whitespace-nowrap">
                        {{ user.role }}
                      </td>
                      <td
                        class="px-6 py-1 space-x-1 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <button
                          @click="edit(user, true)"
                          class="inline-flex items-center px-2 py-2 text-blue-800 text-sm font-medium rounded-md"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"
                            />
                            <path
                              d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"
                            />
                          </svg>
                        </button>

                        <button
                          @click="deleteRow(user.id)"
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
              </div>
            </div>
          </div>
        </div>
        <div class="mx-auto sm:px-6 lg:px-8">
          <jet-pagination class="m-5" :links="users.links" />
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
