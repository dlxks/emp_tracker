<template>
  <!--  -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <!--  -->
    <div class="flex items-center justify-between p-6 bg-blue-500">
      <div class="text-white font-bold">
        <div class="font-semibold text-lg">{{ currentMonth }}</div>
      </div>
      <div class="flex items-center">
        <button
          class="text-white rounded-full bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray"
          @click="prevMonth"
        >
          <svg
            class="h-6 w-6 fill-current"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
          >
            <path d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <button
          class="ml-2 text-white rounded-full bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray"
          @click="nextMonth"
        >
          <svg
            class="h-6 w-6 fill-current"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
          >
            <path d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
    <!--  -->
    <div class="grid grid-cols-7 gap-2 p-6">
      <template v-for="(day, index) in days" :key="index">
        <div class="text-center font-semibold text-gray-700">
          {{ day }}
        </div>
      </template>
      <template v-for="(date, index) in dates" :key="index">
        <div
          class="text-center py-2"
          :class="{ 'bg-blue-500 text-white': isCurrentDate(date) }"
        >
          {{ date ? date.getDate() : "" }}
        </div>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentDate: new Date(),
    };
  },
  computed: {
    days() {
      return ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    },
    dates() {
      const firstDayOfMonth = new Date(
        this.currentDate.getFullYear(),
        this.currentDate.getMonth(),
        1
      );
      const lastDayOfMonth = new Date(
        this.currentDate.getFullYear(),
        this.currentDate.getMonth() + 1,
        0
      );
      const daysInMonth = lastDayOfMonth.getDate();
      const daysInLastMonth = new Date(
        this.currentDate.getFullYear(),
        this.currentDate.getMonth(),
        0
      ).getDate();
      const dayOfWeek = firstDayOfMonth.getDay();
      const dates = []; // Add dates from last month
      for (let i = dayOfWeek - 1; i >= 0; i--) {
        dates.push(
          new Date(
            this.currentDate.getFullYear(),
            this.currentDate.getMonth() - 1,
            daysInLastMonth - i
          )
        );
      }

      // Add dates from current month
      for (let i = 1; i <= daysInMonth; i++) {
        dates.push(
          new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), i)
        );
      }

      // Add dates from next month
      const lastDate = dates[dates.length - 1];
      const remainingSlots = 7 - (dates.length % 7);
      for (let i = 1; i <= remainingSlots; i++) {
        dates.push(
          new Date(lastDate.getFullYear(), lastDate.getMonth(), lastDate.getDate() + i)
        );
      }

      return dates;
    },

    currentMonth() {
      return this.currentDate.toLocaleString("default", {
        month: "long",
        year: "numeric",
      });
    },

    currentMonthIndex() {
      return this.currentDate.getMonth();
    },
  },
  methods: {
    isCurrentDate(date) {
      const today = new Date();
      return (
        date &&
        date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear()
      );
    },
    prevMonth() {
      this.currentDate = new Date(
        this.currentDate.getFullYear(),
        this.currentDate.getMonth() - 1,
        1
      );
    },
    nextMonth() {
      this.currentDate = new Date(
        this.currentDate.getFullYear(),
        this.currentDate.getMonth() + 1,
        1
      );
    },
  },
};
</script>
