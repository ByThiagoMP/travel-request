<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  variant?: 'primary' | 'secondary' | 'danger'
  size?: 'sm' | 'md' | 'lg'
  disabled?: boolean
}>()

const emit = defineEmits(['click'])

const baseClasses = 'inline-flex items-center font-medium rounded focus:outline-none transition-colors'

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'secondary':
      return 'bg-gray-200 hover:bg-gray-300 text-gray-800'
    case 'danger':
      return 'bg-red-500 hover:bg-red-600 text-white'
    default:
      return 'bg-blue-500 hover:bg-blue-600 text-white' // primary padrão
  }
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'text-sm px-3 py-1.5'
    case 'lg':
      return 'text-lg px-5 py-3'
    default:
      return 'text-base px-4 py-2' // md padrão
  }
})
</script>

<template>
  <button
    :class="[baseClasses, variantClasses, sizeClasses]"
    :disabled="disabled"
    @click="$emit('click')"
  >
    <slot />
  </button>
</template>
