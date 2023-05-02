<template>
  <!-- Navbar -->
  <nav
    class="absolute top-0 left-0 w-full z-10 md:flex-row md:flex-nowrap md:justify-start flex items-center p-4"
  >
    <div
      class="w-full mx-auto items-center flex justify-between md:flex-nowrap flex-wrap md:px-10 px-4"
    >
      <!-- Brand -->
      <Head :title="title" />
      <a
        class="text-gray-900 text-sm uppercase inline-block font-semibold"
        href="javascript:void(0)"
      >
      </a>
      <!-- User -->
      <ul class="flex-col md:flex-row list-none items-center hidden md:flex">
        <user-dropdown />
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</template>

<script>
import UserDropdown from "@/NotusComponents/Dropdowns/UserDropdown.vue";
import { Head } from "@inertiajs/inertia-vue3";

export default {
  components: {
    UserDropdown,
    Head,
  },
};
</script>
