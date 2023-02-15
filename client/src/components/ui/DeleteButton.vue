<template>
  <transition name="fade">
    <div
      v-if="deleteModalOpen"
      class="relative z-50"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
      ></div>
      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0"
        >
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
          >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                >
                  <alert-triangle-icon />
                </div>
                <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <div>
                    <p class="text-sm text-gray-500">
                      Are you sure you want to delete<strong
                      v-if="subject !== ''"
                    >
                      "{{ subject }}"</strong
                    >?
                    </p>
                    <p class="text-sm text-gray-500">
                      This action cannot be undone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
            >
              <button
                @click="confirm"
                type="button"
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Delete
              </button>
              <button
                @click="dismiss"
                type="button"
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
  <button
    @click="deleteModalOpen = true"
    :class="iconOnly ? 'text-red-500' : 'inline-flex text-center items-center w-full md:w-auto font-medium text-sm px-6 py-3 bg-red-400 hover:bg-red-600 rounded transition duration-200'"
  >
    <trash-icon />
    <template v-if="!iconOnly">{{ title ? title : "Delete" }}</template>
  </button>
</template>

<script>
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";
import AlertTriangleIcon from "vue-tabler-icons/icons/AlertTriangleIcon";

export default {
  name: "DeleteButton",
  components: { AlertTriangleIcon, TrashIcon },
  data() {
    return {
      deleteModalOpen: false
    };
  },
  watch: {
    deleteModalOpen(val) {
      if (val) {
        document.body.classList.add("overflow-hidden");
      } else {
        document.body.classList.remove("overflow-hidden");
      }
    }
  },
  props: {
    title: String,
    subject: String,
    iconOnly: Boolean
  },
  emits: ["confirm"],
  methods: {
    confirm() {
      this.$emit("confirm");
      this.dismiss();
    },
    dismiss() {
      this.deleteModalOpen = false;
    }
  }
};
</script>

<style scoped></style>
