<template>
  <div>
    <sidebar />
    <div class="relative md:ml-64 bg-gray-50">
      <admin-navbar />
      <!-- <header-stats /> -->
      <div class="px-4 md:px-10 mx-auto w-full pt-10">
        <!-- <router-view /> -->

        <!-- Page Content -->
        <main>
          <slot />
        </main>

        <!-- <footer-admin /> -->
      </div>
    </div>
  </div>
</template>
<script>
import AdminNavbar from "@/NotusComponents/Navbars/AdminNavbar.vue";
import Sidebar from "@/NotusComponents/Sidebar/Sidebar.vue";
import HeaderStats from "@/NotusComponents/Headers/HeaderStats.vue";
import FooterAdmin from "@/NotusComponents/Footers/FooterAdmin.vue";
export default {
  name: "admin-layout",
  components: {
    AdminNavbar,
    Sidebar,
    HeaderStats,
    FooterAdmin,
  },
};
</script>
