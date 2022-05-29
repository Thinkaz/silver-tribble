const FETCH_INTERVAL = 3000;

document.addEventListener('alpine:init', () => {
    Alpine.data('chatbox', () => ({
        messages: [],
        message: '',
        lastFetched: null,

        async init() {
            await this.fetchMessages();
        },

        async fetchMessages() {
            const query = {};
            if (this.lastFetched) {
                query.since = Math.floor(this.lastFetched);
            }

            const res = await Axios.get('/forums/chatbox', { params: query });
            if (res.status !== 200) return;

            const existingMessages = this.messages.map(msg => msg.id);
            const newMessages = res.data.filter(msg => !existingMessages.includes(msg.id));

            this.messages.push(...newMessages);

            if (newMessages.length > 0) {
                this.$nextTick(() => {
                    this.$refs.box.scrollTop = this.$refs.box.scrollHeight;
                });
            }

            this.lastFetched = Date.now() / 1000;

            setTimeout(async () => {
                await this.fetchMessages();
            }, FETCH_INTERVAL);
        },

        sendMessage() {
          const message = this.message.trim();
          if (message.length === 0) return;

          Axios.post('/forums/chatbox', {
              message
          });

          this.message = '';
        },
    }));
});