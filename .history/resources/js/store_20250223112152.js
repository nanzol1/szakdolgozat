import { defineStore } from 'pinia';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    alerts: [{
      id:Date.now(),
      message:'sadsadas',
      type:'success',
    }],
  }),
  actions: {
    showAlert(newMessage, newType) {
      const alert = {
        id:Date.now(),
        message:newMessage,
        type:newType,
      };

      this.alerts.push(alert);

      setTimeout(() => {
        this.removeAlert(alert.id);
      }, 3000);
    },
    
    removeAlert(alertId) {
      this.alerts = this.alerts.filter(alert => alert.id !== alertId);
    },
  },
});