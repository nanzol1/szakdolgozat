import { defineStore } from 'pinia';

export const useAlertStore = defineStore('alert', {
  state: () => ({
    visible: false, 
    message: '',
    type: '',  
  }),
  actions: {
    showAlert(newMessage, newType) {
      this.message = newMessage;
      this.type = newType;
      this.visible = true;

      setTimeout(() => {
        this.visible = false;
      }, 50000);
    },
    
    closeAlert() {
      this.visible = false;
    },
  },
});