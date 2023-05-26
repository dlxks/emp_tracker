<template>
  <div>
    <Head :title="title" />
    <sidebar />
    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>
    <admin-navbar />
    <Banner />
    <div class="relative md:ml-64 bg-gray-50">
      <!-- <header-stats /> -->
      <div class="px-4 md:px-10 mx-auto w-full pt-10">
        <!-- <router-view /> -->

        <!-- Page Content -->
        <main>
          <slot />
        </main>

        <footer-admin />
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import AdminNavbar from "@/NotusComponents/Navbars/AdminNavbar.vue";
import Sidebar from "@/NotusComponents/Sidebar/Sidebar.vue";
import HeaderStats from "@/NotusComponents/Headers/HeaderStats.vue";
import FooterAdmin from "@/NotusComponents/Footers/FooterAdmin.vue";
import Banner from "@/Components/Banner.vue";

export default {
  name: "admin-layout",
  components: {
    Link,
    Head,
    AdminNavbar,
    Sidebar,
    HeaderStats,
    FooterAdmin,
    Banner,
  },
};
</script>
