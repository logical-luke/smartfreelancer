<template>

</template>

<script>
import { mapState } from "vuex";
import InputText from "primevue/inputtext";
import MailIcon from "vue-tabler-icons/icons/MailIcon";

export default {
  name: "ClientForm",
  components: { MailIcon, InputText },
  computed: mapState({
    client: (state) => state.client.current,
  }),
};
</script>

<style scoped></style>
