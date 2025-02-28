import { defineStore } from 'pinia';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alerts: [],
  }),
  actions: {
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
        console.log($page.props.flash.error);
        this.removeAlert(alert.id);
      }, 3000);
    },
    
    removeAlert(alertId) {
      this.alerts = this.alerts.filter(alert => alert.id !== alertId);
    },
  },
});