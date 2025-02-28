import { usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { watch } from 'vue';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alerts: [],
    flash: usePage().props.flash,
  }),
  actions: {
    setupFlashWatcher() {
      const page = usePage();

      watch(() => page.props.flash, (newFlash) => {
        if(newFlash.error){
          this.showAlert();
        }
      })
    },
    showAlert(newMessage, newType) {
      const alert = {
        id:Date.now(),
        message:newMessage,
        type:newType,
      };
      if(this.alerts.length < 3){
        this.alerts.push(alert);
      }

      setTimeout(() => {
        this.removeAlert(alert.id);
      }, 3000);
    },
    
    removeAlert(alertId) {
      this.alerts = this.alerts.filter(alert => alert.id !== alertId);
      if(usePage().props.flash.error){
        usePage().props.flash.error = null
      }
    },
  },
});