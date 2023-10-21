<template>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="name">Name:</label>
    <input
      class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
      type="text"
      id="name"
      name="name"
      v-model="client.name"
      placeholder="Breathtaking Application"
    />
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="description">Description:</label>
    <textarea
      class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
      id="description"
      name="description"
      v-model="client.description"
      rows="5"
      placeholder="Write something..."
    ></textarea>
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="email">Email:</label>
    <span class="p-input-icon-left ">
      <mail-icon class="pb-2" />
      <input-text
          v-model="client.email"
          name="email"
          id="email"
          placeholder="name@domain.com" />
    </span>
  </div>
</template>

<script>
import { mapState } from "vuex";
import InputText from "primevue/inputtext";
import MailIcon from "vue-tabler-icons/icons/MailIcon";

export default {
  name: "ClientForm",
  components: {MailIcon, InputText },
  computed: mapState({
    client: (state) => state.client.current,
  }),
};
</script>

<style scoped></style>
