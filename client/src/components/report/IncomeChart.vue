<template>
  <section class="py-8">
    <div class="container px-4 mx-auto">
      <div class="flex flex-wrap -m-4">
        <div class="w-full lg:w-1/2 p-4">
          <div class="p-6 mb-8 bg-white shadow rounded">
            <div class="flex mb-3 items-center justify-between">
              <h3 class="text-gray-500">Total Income</h3>
              <button class="focus:outline-none">
                <svg
                  class="h-4 w-4 text-gray-200"
                  viewBox="0 0 16 4"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                ></svg>
              </button>
            </div>
            <div class="flex items-center mb-3">
              <span class="text-4xl font-bold">$0</span>
              <span
                class="inline-block ml-2 py-1 px-2 bg-green-500 text-white text-xs rounded-full"
                >+0%</span
              >
            </div>
            <div class="relative w-full h-1 mb-2 bg-gray-50 rounded">
              <div
                class="absolute top-0 left-0 h-full bg-purple-500 rounded"
              ></div>
            </div>
            <p class="text-xs text-black">Yearly Goal</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "IncomeChart",
};
</script>

<style scoped></style>
