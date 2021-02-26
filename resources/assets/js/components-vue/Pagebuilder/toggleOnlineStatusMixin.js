export default {
    props: {
        initialShowOnlineToggle: { required: false, type: Boolean, default: false },
        initialOnlineStatus: { required: false, type: Boolean, default: true },
        toggleOnlineUrl: { required: false, type: String },
        toggleOnlineUrlBody: {
            required: false,
            type: Object,
            default: function () {
                return {};
            },
        },
    },
    data() {
        return {
            showOnlineToggle: this.initialShowOnlineToggle,
            isOnline: this.initialOnlineStatus,
        };
    },
    methods: {
        toggleOnlineStatus() {
            let newOnlineStatus = !this.isOnline;

            let data = this.toggleOnlineUrlBody;
            data['online_status'] = newOnlineStatus;

            fetch(this.toggleOnlineUrl, {
                method: 'PUT',
                headers: { 'Content-type': 'application/json; charset=UTF-8' },
                body: JSON.stringify(data),
            })
                .then((response) => response.json())
                .then((data) => {
                    this.isOnline = newOnlineStatus;
                    Eventbus.$emit(
                        'create-notification',
                        'success',
                        newOnlineStatus
                            ? `${this.title ?? 'Module'} is online gezet`
                            : `${this.title ?? 'Module'} is offline gezet`
                    );
                })
                .catch((err) => console.error(err));
        },
    },
};
