<template>
  <AppLayout title="Dashboard">
    <template #header>
      <!-- Header -->
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <span>Overview</span>
        </h2>
      </div>
      <!-- Header -->
    </template>

    <div class="">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Card stats -->
          <div class="flex flex-wrap">
            <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
              <card-stats
                statSubtitle="REGISTERED USERS"
                :statTitle="noOfRecords"
                statArrow="up"
                statDescripiron="Since last month"
                statIconName="fa fa-users"
                statIconColor="bg-red-500"
              />
            </div>
            <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
              <card-stats
                statSubtitle="BRANCHES"
                :statTitle="noOfBranches"
                statArrow="down"
                statDescripiron="Since last week"
                statIconName="fa fa-landmark"
                statIconColor="bg-orange-500"
              />
            </div>
            <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
              <card-stats
                statSubtitle="RECORDS"
                :statTitle="noOfRecords"
                statArrow="down"
                statDescripiron="Since yesterday"
                statIconName="fa fa-chart-column"
                statIconColor="bg-pink-500"
              />
            </div>
          </div>
          <!-- <Welcome /> -->
        </div>
      </div>
    </div>

    <!--  -->
    <div>
      <!-- <Calendar /> -->
    </div>
  </AppLayout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import Calendar from "@/Components/Calendar.vue";
import CardStats from "@/NotusComponents/Cards/CardStats.vue";

export default {
  components: {
    AppLayout,
    Welcome,
    Calendar,
    CardStats,
  },

  props: {
    noOfUsers: Number,
    noOfBranches: Number,
    noOfRecords: Number,
  },
};
</script>
