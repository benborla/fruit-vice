const helpers = {
  install(app: any) {
    app.config.globalProperties = {
      formatDate: (date: string): string => (
        new Date(date).toISOString().slice(0, 19).replace("T", " ")
      ),
      translateSource: (type: string): string => (
        type === 'FRUITY_VICE_API' ? 'From Fruity Vice API' : 'From Fruity Vice App'
      ),
    }
  }
}

export default helpers;
