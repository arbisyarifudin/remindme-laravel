<template>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <!-- Position it: -->
        <!-- - `.toast-container` for spacing between toasts -->
        <!-- - `top-0` & `end-0` to position the toasts in the upper right corner -->
        <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
        <div class="toast-container top-0 end-0 p-3">
            <!-- Then put toasts within -->
            <div class="toast" :class="bgClass(toast.type)" role="alert" aria-live="assertive" aria-atomic="true"
                v-for="(toast) in toasts" :key="toast.id" :id="toast.id">
                <div class="toast-header" v-if="toast.title">
                    <strong class="me-auto">{{ toast.title }}</strong>
                    <!-- <small class="text-body-secondary">just now</small> -->
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"
                        @click="removeToast(toast.id)"></button>
                </div>
                <div class="d-flex">
                    <div class="toast-body" v-if="toast.message">
                        {{ toast.message }}
                    </div>
                    <button v-if="!toast.title" type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" @click="removeToast(toast.id)"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, watch, inject, onMounted, nextTick } from 'vue'
import { useToastStore } from '@/stores/toast'

const bootstrap = inject('bootstrap')

onMounted(() => {
  initToast()
})

const toastStore = useToastStore()
const toasts = computed(() => {
  return toastStore.toasts
})

const option = {
  animation: true,
  autohide: true,
  delay: 3000
}

let toastElList = null
let toastList = null

// toastElList = document.querySelectorAll('.toast')
// toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, option))

const initToast = () => {
  nextTick(() => {
    // toastElList = document.querySelectorAll('.toast')
    toastElList = document.querySelectorAll('.toast:not(.hide)')
    if (!toastElList) {
      return
    }
    toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, option))
    toastList.forEach(toast => {
      // if not shown, show it
      if (!toast._element.classList.contains('show')) {
        toast.show()
      }
    })

    // add event listener to each toast
    toastElList.forEach(toastEl => {
      toastEl.addEventListener('hidden.bs.toast', function (event) {
        // remove toast element
        toastEl.remove()

        // get id from element
        const id = event.target.id
        toastStore.removeToast(id)
      })
    })
  })
}

watch(() => toasts.value, (newVal) => {
  // console.log('toasts changed')
  initToast()
}, { deep: true })

const bgClass = (type) => {
  return `text-bg-${type}`
}

const removeToast = (id) => {
  toastStore.removeToast(id)
}
</script>
