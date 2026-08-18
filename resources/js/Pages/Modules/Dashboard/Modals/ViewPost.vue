<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 650px;" header-class="p-3 bg-light" title="Post" class="v-modal-custom" modal-class="zoomIn" centered scrollable no-close-on-backdrop>
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        <template v-else-if="post">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                            <img class="rounded-circle avatar-xs" :src="post.user.avatar" alt="">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="fs-13 fw-semibold mb-0">{{ post.user.name }}</h6>
                    <p class="fs-11 text-muted mb-0">{{ post.posted_at }}</p>
                </div>
                <div class="flex-shrink-0">
                    <a :href="`/posts/${post.id}/print`" target="_blank" class="btn btn-sm btn-light" title="Print / View as PDF">
                        <i class="ri-printer-line align-middle"></i>
                    </a>
                </div>
            </div>

            <div v-if="post.type_id === SIGNATORY_POST_TYPE_ID" class="d-flex align-items-start gap-3">
                <div class="flex-shrink-0">
                    <img src="/images/thumbnails/preview.jpg" alt="Signatory document" class="rounded" style="width: 110px; height: 140px; object-fit: cover;">
                </div>
                <div class="flex-grow-1 min-w-0">
                    <h5 v-if="post.title" class="fs-12 fw-semibold text-primary">{{ post.title }}</h5>
                    <div class="fs-12 post-content" v-html="post.content"></div>
                </div>
            </div>
            <template v-else>
                <h5 v-if="post.title" class="fs-12 fw-semibold text-primary">{{ post.title }}</h5>
                <div class="fs-12 post-content" v-html="post.content"></div>
            </template>

            <div class="d-flex align-items-center justify-content-between border-top border-bottom py-2 my-3 fs-12 text-muted">
                <span>{{ post.reactions_count }} reaction{{ post.reactions_count === 1 ? '' : 's' }}</span>
                <span>{{ post.comments_count }} comment{{ post.comments_count === 1 ? '' : 's' }}</span>
            </div>

            <div class="mb-3">
                <ReactionPicker :post-id="post.id" :reaction-types="reactionTypes" :my-reaction="post.my_reaction" @updated="onReacted" />
            </div>

            <simplebar style="max-height: 300px;">
                <div v-if="!comments.length" class="text-center text-muted fs-12 py-4">
                    No comments yet. Be the first to comment.
                </div>

                <div v-for="comment in comments" :key="comment.id" class="mb-3">
                    <div class="d-flex">
                        <img :src="comment.user.avatar" class="rounded-circle me-2" style="width:28px;height:28px;object-fit:cover;">
                        <div class="flex-grow-1">
                            <div class="bg-light-subtle rounded-3 px-3 py-2">
                                <h6 class="fs-12 fw-semibold mb-0">{{ comment.user.name }}</h6>
                                <p class="fs-12 mb-0" style="white-space: pre-wrap;">{{ comment.comment }}</p>
                            </div>
                            <div class="fs-11 text-muted mt-1 d-flex gap-2">
                                <span>{{ comment.posted_at }}</span>
                                <a href="javascript:void(0)" @click="startReply(comment)">Reply</a>
                            </div>

                            <div v-for="reply in comment.replies" :key="reply.id" class="d-flex mt-2 ms-3">
                                <img :src="reply.user.avatar" class="rounded-circle me-2" style="width:24px;height:24px;object-fit:cover;">
                                <div class="flex-grow-1">
                                    <div class="bg-light-subtle rounded-3 px-3 py-2">
                                        <h6 class="fs-12 fw-semibold mb-0">{{ reply.user.name }}</h6>
                                        <p class="fs-12 mb-0" style="white-space: pre-wrap;">{{ reply.comment }}</p>
                                    </div>
                                    <div class="fs-11 text-muted mt-1">{{ reply.posted_at }}</div>
                                </div>
                            </div>

                            <div v-if="replyTo === comment.id" class="d-flex mt-2 ms-3 gap-2">
                                <input v-model="replyText" type="text" class="form-control form-control-sm" placeholder="Write a reply..." @keyup.enter="submitReply(comment)">
                                <button type="button" class="btn btn-sm btn-primary" :disabled="!replyText.trim() || submittingReply" @click="submitReply(comment)">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </simplebar>
        </template>

        <template v-slot:footer>
            <div class="d-flex w-100 gap-2">
                <input v-model="commentText" type="text" class="form-control" placeholder="Write a comment..." @keyup.enter="submitComment">
                <b-button variant="primary" :disabled="!commentText.trim() || submittingComment" @click="submitComment">Send</b-button>
            </div>
        </template>
    </b-modal>
</template>
<script>
import simplebar from "simplebar-vue";
import ReactionPicker from '@/Shared/Components/ReactionPicker.vue';

const SIGNATORY_POST_TYPE_ID = 203; // Special Order posts created from the Signatory module

export default {
    components: { simplebar, ReactionPicker },
    props: ['reactionTypes'],
    emits: ['updated'],
    data(){
        return {
            showModal: false,
            loading: false,
            post: null,
            comments: [],
            commentText: '',
            submittingComment: false,
            replyTo: null,
            replyText: '',
            submittingReply: false,
        }
    },
    methods: {
        show(postId){
            this.showModal = true;
            this.loading = true;
            this.post = null;
            this.comments = [];
            this.commentText = '';
            this.replyTo = null;
            this.replyText = '';

            axios.get(`/posts/${postId}`)
                .then(response => {
                    this.post = response.data.post;
                    this.comments = response.data.comments;
                })
                .catch(err => console.log(err))
                .finally(() => { this.loading = false; });
        },
        onReacted(payload){
            this.post.reactions_count = payload.reactions_count;
            this.post.my_reaction = payload.my_reaction;
            this.$emit('updated', { id: this.post.id, ...payload });
        },
        startReply(comment){
            this.replyTo = this.replyTo === comment.id ? null : comment.id;
            this.replyText = '';
        },
        submitComment(){
            if (!this.commentText.trim() || this.submittingComment) return;
            this.submittingComment = true;

            axios.post(`/posts/${this.post.id}/comments`, {
                comment: this.commentText,
                parent_id: null,
            })
            .then(response => {
                this.comments.push({ ...response.data, replies: [] });
                this.commentText = '';
                this.post.comments_count += 1;
                this.$emit('updated', { id: this.post.id, comments_count: this.post.comments_count });
            })
            .catch(err => console.log(err))
            .finally(() => { this.submittingComment = false; });
        },
        submitReply(comment){
            if (!this.replyText.trim() || this.submittingReply) return;
            this.submittingReply = true;

            axios.post(`/posts/${this.post.id}/comments`, {
                comment: this.replyText,
                parent_id: comment.id,
            })
            .then(response => {
                comment.replies.push(response.data);
                this.replyText = '';
                this.replyTo = null;
                this.post.comments_count += 1;
                this.$emit('updated', { id: this.post.id, comments_count: this.post.comments_count });
            })
            .catch(err => console.log(err))
            .finally(() => { this.submittingReply = false; });
        },
    }
}
</script>
<style scoped>
.post-content :deep(img) {
    max-width: 100%;
    max-height: 420px;
    height: auto;
    object-fit: contain;
    border-radius: 6px;
    display: block;
    margin: 0.5rem 0;
}
</style>
