const hiveAssistant = (config) => ({
    endpoint: '/admin/hive/suggest',
    payload: config.payload || {},
    wireModel: config.wireModel,
    dialogId: config.dialogId,
    loading: false,
    text: null,
    suggestions: [],

    async prompt(promptClass = null) {
        this.loading = true;

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    prompt: promptClass,
                    text: this.text,
                    payload: this.payload,
                }),
            });

            const data = await response.json();

            if (data.suggestions) {
                this.suggestions = data.suggestions;

                this.$dispatch('open-dialog', { id: this.dialogId });
            }
        } catch (e) {
            console.error('Hive suggestie mislukt', e);
        } finally {
            this.loading = false;
        }
    },
    applySuggestion(suggestion) {
        this.$wire.set(this.wireModel, suggestion);
        this.suggestions = [];
        this.$dispatch('close-dialog', { id: this.dialogId });
    },
});

export { hiveAssistant as default };
