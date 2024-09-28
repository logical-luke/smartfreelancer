<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

import { defineProps } from 'vue';

interface Props {
  title: string;
  icon?: string;
}

const props = defineProps<Props>();

const title = t(props.title);
const icon = props.icon ? props.icon : null;
</script>

<template>
    <div class="flex mb-6 flex-row items-center gap-2">
      <i v-if="icon" class="pi text-xl" :class="icon"></i>
      <h3 class="text-xl font-bold">{{ title }}</h3>
    </div>
</template>