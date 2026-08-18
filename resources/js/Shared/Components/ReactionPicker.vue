<template>
    <div class="position-relative d-inline-block" @mouseenter="showPicker" @mouseleave="scheduleHide">
        <button type="button" class="btn btn-sm btn-light d-inline-flex align-items-center gap-1" :disabled="busy" @click="toggleQuick">
            <span v-if="myReaction" style="font-size:1rem;">{{ icon(myReaction.name) }}</span>
            <i v-else class="mdi mdi-thumb-up-outline text-muted"></i>
            <span :class="myReaction ? 'fw-semibold' : 'text-muted'" class="fs-12">{{ myReaction ? myReaction.name : 'Like' }}</span>
        </button>

        <div v-if="open" class="reaction-popover shadow bg-white rounded-pill d-flex gap-1 p-1 position-absolute">
            <button
                v-for="type in reactionTypes"
                :key="type.value"
                type="button"
                class="btn btn-sm p-1 reaction-popover-btn"
                :title="type.name"
                @click="react(type)"
            >
                <span style="font-size:1.35rem;">{{ icon(type.name) }}</span>
            </button>
        </div>
    </div>
</template>
<script>
const REACTION_ICONS = {
    Like: '👍',
    Love: '❤️',
    Care: '🥰',
    Haha: '😆',
    Wow: '😮',
    Sad: '😢',
    Angry: '😡',
};

export default {
    props: {
        postId: { type: Number, required: true },
        reactionTypes: { type: Array, default: () => [] },
        myReaction: { type: Object, default: null },
    },
    emits: ['updated'],
    data(){
        return {
            open: false,
            busy: false,
            hideTimer: null,
        }
    },
    methods: {
        icon(name){
            return REACTION_ICONS[name] || '👍';
        },
        showPicker(){
            clearTimeout(this.hideTimer);
            this.open = true;
        },
        scheduleHide(){
            this.hideTimer = setTimeout(() => { this.open = false; }, 300);
        },
        toggleQuick(){
            if (this.busy) return;
            const target = this.myReaction
                ? this.reactionTypes.find(type => type.value === this.myReaction.id)
                : this.reactionTypes[0];
            if (!target) return;
            this.react(target);
        },
        react(type){
            if (this.busy) return;
            this.busy = true;
            this.open = false;
            axios.post(`/posts/${this.postId}/react`, { reaction_id: type.value })
                .then(response => {
                    this.$emit('updated', response.data);
                })
                .catch(err => console.log(err))
                .finally(() => { this.busy = false; });
        }
    }
}
</script>
<style scoped>
.reaction-popover {
    bottom: 100%;
    left: 0;
    margin-bottom: 6px;
    z-index: 10;
}
.reaction-popover-btn {
    transition: transform 0.1s ease;
    line-height: 1;
}
.reaction-popover-btn:hover {
    transform: scale(1.3) translateY(-3px);
}
</style>
