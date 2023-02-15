<template>
  <div class="flex flex-wrap items-center gap-4 justify-between">
    <div class="mr-3 flex items-center">
      <p class="text-xs">Show</p>
      <div class="mx-3 py-2 px-2 text-xs bg-white border rounded">
        <select @change="setLimit" class="bg-white" name="limit">
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <p class="text-xs">of {{ total }}</p>
    </div>
    <div class="w-full flex-wrap justify-center lg:w-auto flex items-center">
      <span v-if="hasPages"
            @click="setPage(page - 1)"
            class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
      >
        <chevron-left-icon size="12" />
      </span>
      <span v-if="pages > 6"
            @click="setPage(page - 3)"
            class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
      >
        <chevrons-left-icon size="12" />
      </span>
      <span
        :class="getActiveClass(1)"
        class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs rounded"
        @click="setPage(1)"
      >
        1
      </span>
      <template v-if="hasPages">
        <span v-if="pages > 3" class="inline-block mr-3">
            <dots-icon size="12" />
        </span>
        <template v-if="pages > 3">
          <span
            :class="getActiveClass(getFirstMiddlePage())"
            class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs rounded"
            @click="setPage(getFirstMiddlePage())"
          >
            {{ getFirstMiddlePage() }}
          </span>
          <span
            :class="getActiveClass(getSecondMiddlePage())"
            class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs rounded"
            @click="setPage(getSecondMiddlePage())"
          >
            {{ getSecondMiddlePage() }}
          </span>
          <span
            :class="getActiveClass(getThirdMiddlePage())"
            class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs rounded"
            @click="setPage(getThirdMiddlePage())"
          >
            {{ getThirdMiddlePage() }}
          </span>
        </template>
        <span v-else
          :class="getActiveClass(2)"
          class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs rounded"
          @click="setPage(2)"
        >
            2
        </span>
        <span v-if="pages > 3" class="inline-block mr-3">
          <dots-icon size="12" />
        </span>
        <span
          :class="getActiveClass(pages)"
          @click="setPage(pages)"
          class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs  rounded"
        >
          {{ pages }}
        </span>
        <span
          v-if="pages > 6"
          @click="setPage(page + 3)"
          class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
        >
          <chevrons-right-icon size="12" />
        </span>
        <span
          v-if="hasPages"
          @click="setPage(page + 1)"
          class="cursor-pointer inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
        >
          <chevron-right-icon size="12" />
        </span>
      </template>
    </div>
  </div>
</template>

<script>
import ChevronLeftIcon from "vue-tabler-icons/icons/ChevronLeftIcon";
import ChevronRightIcon from "vue-tabler-icons/icons/ChevronRightIcon";
import DotsIcon from "vue-tabler-icons/icons/DotsIcon";
import ChevronsLeftIcon from "vue-tabler-icons/icons/ChevronsLeftIcon";
import ChevronsRightIcon from "vue-tabler-icons/icons/ChevronsRightIcon";

export default {
  name: "PaginationControls",
  components: { ChevronsRightIcon, ChevronsLeftIcon, DotsIcon, ChevronRightIcon, ChevronLeftIcon },
  props: {
    total: {
      type: Number,
      required: true
    },
    limit: {
      type: Number,
      required: true
    },
    page: {
      type: Number,
      required: true
    }
  },
  computed: {
    pages() {
      return Math.ceil(this.total / this.limit);
    },
    hasPages() {
      return this.pages > 1;
    },
    middlePage() {
      return Math.ceil(this.pages / 2);
    },
  },
  emits: ["page-change", "limit-change"],
  methods: {
    setPage(page) {
      if (page < 1) {
        page = 1;
      }
      if (page > this.pages) {
        page = this.pages;
      }
      this.$emit("page-change", page);
    },
    setLimit(event) {
      event.target.blur();
      const limit = event.target.value;
      this.$emit("limit-change", limit);
      this.$emit("page-change", 1);
    },
    isOnFirstOrLastPage() {
      return this.page === 1 || this.page === this.pages;
    },
    isCloseToLastPage() {
      return this.page + 3 > this.pages;
    },
    getFirstMiddlePage() {
      if (this.isOnFirstOrLastPage()) {
        return this.middlePage - 1;
      }
      if (this.isCloseToLastPage()) {
        return this.pages - 3;
      }

      return this.page - 1;
    },
    getSecondMiddlePage() {
      if (this.isOnFirstOrLastPage()) {
        return this.middlePage;
      }
      if (this.isCloseToLastPage()) {
        return this.pages - 2;
      }

      return this.page;
    },
    getThirdMiddlePage() {
      if (this.isOnFirstOrLastPage()) {
        return this.middlePage + 1;
      }
      if (this.isCloseToLastPage()) {
        return this.pages - 1;
      }

      return this.page + 1;
    },
    getActiveClass(page) {
      return page !== this.page
        ? "text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 " : "text-white bg-indigo-500";
    }
  }
};
</script>

<style scoped>

</style>
