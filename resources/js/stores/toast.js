import { defineStore } from 'pinia'

export const useToastStore = defineStore('toast', {
  state: () => {
    return { toasts: [] }
  },
  actions: {
    addToast (toast) {
      this.toasts.push({
        ...toast,
        id: new Date().getTime() + Math.random(),
      })
    },
    removeToast (id) {
        const index = this.toasts.findIndex((toast) => toast.id === id)
        if (index !== -1) {
            this.toasts.splice(index, 1)
        }
    }
  },
})
