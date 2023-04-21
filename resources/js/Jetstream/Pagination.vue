<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links" :key="key">
        <div
          v-if="link.url === null"
          class="mr-1 my-2 px-2 py-2 text-xs leading-4 text-gray-400 border rounded"
          v-html="link.label"
        />
        <Link
          v-else
          class="mr-1 my-2 px-2 py-2 text-xs leading-4 border rounded focus:border-indigo-500 focus:text-indigo-500"
          :class="{
            'bg-gray-700 text-white hover:text-white hover:bg-gray-500': link.active,
          }"
          :href="link.url"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
  props: {
    links: Array,
  },
};
</script>
