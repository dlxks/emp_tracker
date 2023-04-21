<template>
  <div>
    <a
      class="text-gray-500 block"
      href="#pablo"
      ref="btnDropdownRef"
      v-on:click="toggleDropdown($event)"
    >
      <div class="items-center flex md:bg-gray-800 px-3 rounded-full drop-shadow-md">
        <div class="mr-3 md:ml-3 hidden md:block">
          <span class="text-white font-bold">{{ $page.props.user.name }}</span>
        </div>
        <span
          class="w-12 h-12 text-sm text-white inline-flex items-center justify-center rounded-full"
        >
          <img
            class="rounded-full h-10 w-10 object-cover"
            :src="$page.props.user.profile_photo_url"
            :alt="$page.props.user.name"
          />
        </span>
      </div>
    </a>

    <div
      ref="popoverDropdownRef"
      class="bg-white text-base z-50 float-right py-2 list-none text-left rounded shadow-lg"
      v-bind:class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
    >
      <span class="p-2 text-xs text-gray-500 text-left">Options</span>
      <div class="border-t border-gray-100"></div>

      <JetDropdownLink
        :href="route('profile.show')"
        :active="route().current('profile.show')"
        class="px-3 py-1 my-1 mx-2 bg-slate-200 text-slate-800 hover:bg-slate-100 hover:text-slate-500 rounded"
      >
        Profile
      </JetDropdownLink>

      <JetDropdownLink
        v-if="$page.props.jetstream.hasApiFeatures"
        :href="route('api-tokens.index')"
        class="px-3 py-1 my-1 mx-2 bg-slate-200 text-slate-800 hover:bg-slate-100 hover:text-slate-500 rounded"
      >
        API Tokens
      </JetDropdownLink>

      <!-- Authentication -->
      <form @submit.prevent="logout">
        <JetDropdownLink
          as="button"
          class="px-3 py-1 my-1 mx-2 bg-slate-200 text-slate-800 hover:bg-slate-100 hover:text-slate-500 rounded"
        >
          Logout
        </JetDropdownLink>
      </form>
    </div>
  </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import JetApplicationMark from "@/Components/ApplicationMark.vue";
import JetBanner from "@/Components/Banner.vue";
import JetDropdown from "@/Components/Dropdown.vue";
import JetDropdownLink from "@/Components/DropdownLink.vue";
import JetNavLink from "@/Components/NavLink.vue";
import JetResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

import image from "@/assets/img/team-1-800x800.jpg";

export default {
  components: {
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
  },

  data() {
    return {
      dropdownPopoverShow: false,
      image: image,
    };
  },

  methods: {
    toggleDropdown: function (event) {
      event.preventDefault();
      if (this.dropdownPopoverShow) {
        this.dropdownPopoverShow = false;
      } else {
        this.dropdownPopoverShow = true;
        createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
          placement: "bottom-start",
        });
      }
    },

    toggleCollapseShow: function (classes) {
      this.collapseShow = classes;
    },

    logout() {
      this.$inertia.post(route("logout"));
    },
  },
};
</script>
