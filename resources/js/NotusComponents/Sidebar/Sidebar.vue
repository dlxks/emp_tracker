<template>
  <nav
    class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6"
  >
    <div
      class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto"
    >
      <!-- Toggler -->
      <button
        class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
        type="button"
        v-on:click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
      >
        <i class="fa-solid fa-bars fa-lg mr-3"></i>
      </button>
      <!-- Brand -->
      <nav-link
        class="md:block hidden text-left md:pb-2 text-gray-600 mr-0 whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
        to="/"
      >
        Cavite State University
      </nav-link>
      <!-- User -->
      <ul class="md:hidden items-center flex flex-wrap list-none">
        <!-- <li class="inline-block relative">
          <notification-dropdown />
        </li> -->
        <li class="inline-block relative">
          <user-dropdown />
        </li>
      </ul>
      <!-- Collapse -->
      <div
        class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded"
        v-bind:class="collapseShow"
      >
        <!-- Collapse header -->
        <div
          class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-gray-200"
        >
          <div class="flex flex-wrap">
            <div class="w-6/12">
              <nav-link
                class="md:block text-left md:pb-2 text-gray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                to="/"
              >
                Cavite State University
              </nav-link>
            </div>
            <div class="w-6/12 flex justify-end">
              <button
                type="button"
                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                v-on:click="toggleCollapseShow('hidden')"
              >
                <i class="fa-solid fa-close fa-lg mr-3"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-4 md:min-w-full" />
        <!-- Heading -->
        <h6
          class="md:min-w-full text-gray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
        >
          Dashboard
        </h6>
        <!-- Navigation -->

        <ul class="md:flex-col md:min-w-full flex flex-col list-none">
          <li class="items-center">
            <nav-link
              :href="route('admin.dashboard.index')"
              class="text-xs uppercase py-2 font-bold"
              :class="
                route().current('admin.dashboard.index')
                  ? 'text-green-500 hover:text-green-600'
                  : 'text-gray-700 hover:text-gray-500'
              "
            >
              <i class="fa-solid fa-tv fa-lg mr-3"></i>
              <span>Dashboard</span>
            </nav-link>
          </li>
        </ul>

        <!-- Divider -->
        <hr class="my-4 md:min-w-full" />
        <!-- Heading -->
        <h6
          class="md:min-w-full text-gray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
        >
          Data Management
        </h6>
        <!-- Navigation -->
        <ul class="md:flex-col md:min-w-full flex flex-col list-none">
          <li class="items-center">
            <nav-link
              :href="route('admin.record.index')"
              class="text-xs uppercase py-2 font-bold"
              :class="
                route().current('admin.record.index') ||
                route().current('admin.record.show')
                  ? 'text-green-500 hover:text-green-600 hover:no-underline'
                  : 'text-gray-700 hover:text-gray-500'
              "
            >
              <i class="fa-solid fa-chart-column fa-lg mr-3"></i>
              <span>Records</span>
            </nav-link>
          </li>

          <li class="items-center">
            <nav-link
              :href="route('admin.branch.index')"
              class="text-xs uppercase py-2 font-bold"
              :class="
                route().current('admin.branch.index')
                  ? 'text-green-500 hover:text-green-600 hover:no-underline'
                  : 'text-gray-700 hover:text-gray-500'
              "
            >
              <i class="fa-solid fa-landmark fa-lg mr-3"></i>
              <span>Branches</span>
            </nav-link>
          </li>
        </ul>

        <!-- Divider -->
        <hr class="my-4 md:min-w-full" />
        <!-- Heading -->
        <h6
          class="md:min-w-full text-gray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
        >
          User Management
        </h6>
        <!-- Navigation -->

        <ul class="md:flex-col md:min-w-full flex flex-col list-none">
          <li class="items-center">
            <nav-link
              :href="route('admin.user.index')"
              class="text-xs uppercase py-2 font-bold"
              :class="
                route().current('admin.user.index')
                  ? 'text-green-500 hover:text-green-600 hover:no-underline'
                  : 'text-gray-700 hover:text-gray-500'
              "
            >
              <i class="fa-solid fa-users fa-lg mr-3"></i>
              <span>Users</span>
            </nav-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
// import NotificationDropdown from "@/NotusComponents/Dropdowns/NotificationDropdown.vue";
import UserDropdown from "@/NotusComponents/Dropdowns/UserDropdown.vue";
import NavLink from "@/Components/NavLink.vue";

export default {
  components: {
    // NotificationDropdown,
    UserDropdown,
    NavLink,
  },

  data() {
    return {
      collapseShow: "hidden",
    };
  },

  methods: {
    toggleCollapseShow: function (classes) {
      this.collapseShow = classes;
    },
  },
};
</script>
