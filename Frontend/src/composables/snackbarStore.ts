import { ref } from 'vue'

const visible = ref(false)
const message = ref('')
const type = ref<'success' | 'error' | 'info' | 'warning'>('success')
const duration = ref(3000)

const show = (_message: string, _type: 'success' | 'error' | 'info' | 'warning' = 'info', _duration = 3000) => {
  message.value = _message
  type.value = _type
  duration.value = _duration
  visible.value = false

  requestAnimationFrame(() => {
    visible.value = true
    setTimeout(() => {
      visible.value = false
    }, _duration)
  })
}

export const useSnackbarStore = () => ({
  visible,
  message,
  type,
  duration,
  show,
})
