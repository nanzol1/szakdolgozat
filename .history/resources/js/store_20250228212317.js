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
          this.showAlert(newFlash.error,'error');
        }
        if(newFlash.success){
          this.showAlert(newFlash.success,'success');
        }
      },{deep:true});
    },
    showAlert(newMessage, newType) {
      const alert = {
        id:Date.now(),
        message:newMessage,
        type:newType,
      };
      const page = usePage();
      if(this.alerts.length < 3){
        this.alerts.push(alert);
      }

      setTimeout(() => {
        this.removeAlert(alert.id);
      }, 3000);
    },
    
    removeAlert(alertId) {
      if(page.props.flash.error){
        page.props.flash.error = null
      }
      if(page.props.flash.success){
        page.props.flash.success = null
      }
      this.alerts = this.alerts.filter(alert => alert.id !== alertId);
    },
  },
});