<template>
    <transition name="fade">
      <div
        v-if="visible"
        :class="[
          'fixed bottom-4 right-4 px-4 py-2 rounded shadow-md text-white z-50',
          typeClass
        ]"
      >
        {{ message }}
      </div>
    </transition>
  </template>
  
  <script setup>
  import { onMounted, ref, watch } from 'vue'
  const props = defineProps({
    message: String,
    type: {
      type: String,
      default: 'success', // 'error', 'info', 'warning'
    },
    duration: {
      type: Number,
      default: 3000,
    }
  })
  
  const visible = ref(false)
  
  const typeClass = {
    success: 'bg-green-500',
    error: 'bg-red-500',
    info: 'bg-blue-500',
    warning: 'bg-yellow-500 text-black',
  }[props.type] || 'bg-gray-800'
  
  onMounted(() => {
    visible.value = true
    setTimeout(() => (visible.value = false), props.duration)
  })
  </script>
  
  <style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  </style>
  