<template>
    <span>
        <button type="button" v-if="modal" @mousedown.prevent="open(modalId)" class="btn --remove-padding inline-s">
            <slot name="modalBtn">
                <span class="icon icon-trash"></span>
                <span>Verwijder</span>
            </slot>
        </button>

        <button class="btn --remove-padding inline-s" v-if="!modal" @mousedown.prevent="remove()">
            <slot name="removeBtn">
                <span class="icon icon-trash"></span>
            </slot>
        </button>

        <mkiha-modal :id="modalId" :title="title">
            <slot name="message">Bent u zeker dat u dit wilt verwijderen?
            </slot>

            <div slot="footer">
                <button class="modal-delete btn btn-error inline" @click.prevent="remove()">
                    <span class="icon icon-trash inline-xs"></span>Definitief verwijderen
                </button>
                <a @click="close(modalId)" class="btn btn-link"><slot name='modal-close-btn'>Annuleer</slot></a>
            </div>
        </mkiha-modal>
    </span>
</template>

<script>
export default {
    props: {
        url: { required: true },
        modal: { required: false, default: false, type: Boolean, },
        title: ''
    },
    data(){
        return {
        }
    },
    methods:{
        open: function(id){
            Eventbus.$emit('open-modal',id);
        },
        close(id){
            Eventbus.$emit('close-modal',id);
        },
        remove() {
            axios.post(this.url, {
                _method: 'DELETE'
            }).then((response) => {
                window.location.href = this.url.substring(0, this.url.lastIndexOf('/') + 1);
            }).catch((errors) => {
                alert('error');
            })
        }
    },
    computed: {
        modalId: function() {
            var tempurl = this.url.split('/');
            var second  = tempurl.pop();
            var first   = tempurl.pop();
            return first+'-'+second;
        }
    }
};
</script>
