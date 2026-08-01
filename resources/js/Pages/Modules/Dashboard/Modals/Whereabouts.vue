<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 520px;" header-class="p-3 bg-light" title="Whereabouts" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.25rem;width:2.25rem;">
                        <span :class="['avatar-title rounded-circle fs-16', 'bg-' + meta.color + '-subtle', 'text-' + meta.color]">
                            <i :class="meta.icon"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14">{{ status }}</h5>
                    <p class="text-muted mb-0 fs-11">
                        {{ station ? station + ' · ' : '' }}{{ users.length }} employee{{ users.length === 1 ? '' : 's' }}
                    </p>
                </div>
            </div>

            <div class="mb-2" v-if="users.length > 5">
                <input type="text" v-model="keyword" class="form-control form-control-sm" placeholder="Search name...">
            </div>

            <simplebar style="max-height: 360px;">
                <ul class="list-group list-group-flush border-dashed mb-0">
                    <li class="list-group-item px-0" v-for="user in filteredUsers" :key="user.user_id">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <img v-if="user.avatar" :src="user.avatar" alt="" class="avatar-img rounded-circle">
                                    <div v-else class="avatar-title rounded-circle bg-danger-subtle text-danger fs-12">{{ user.name.charAt(0) }}</div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fs-13">{{ user.name }}</h6>
                                <p v-if="user.station" class="text-muted mb-0 fs-11">{{ user.station }}</p>
                            </div>
                        </div>
                    </li>
                    <li v-if="!filteredUsers.length" class="list-group-item px-0 text-center text-muted fs-12">
                        No matching employees.
                    </li>
                </ul>
            </simplebar>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
import simplebar from "simplebar-vue";

const META = {
    'Present': { icon: 'ri-checkbox-circle-fill', color: 'success' },
    'Absent': { icon: 'ri-close-circle-fill', color: 'danger' },
    'OB/On Travel': { icon: 'ri-flight-takeoff-line', color: 'info' },
    'Holiday': { icon: 'ri-calendar-event-fill', color: 'warning' },
    'Suspension': { icon: 'ri-alert-fill', color: 'primary' },
};

export default {
    components: { simplebar },
    data(){
        return {
            showModal: false,
            status: null,
            station: null,
            users: [],
            keyword: null,
        }
    },
    computed: {
        meta(){
            return META[this.status] || { icon: 'ri-user-3-fill', color: 'secondary' };
        },
        filteredUsers(){
            if (!this.keyword) return this.users;
            const kw = this.keyword.toLowerCase();
            return this.users.filter(user => user.name.toLowerCase().includes(kw));
        }
    },
    methods: {
        show(group){
            this.status = group.status;
            this.station = group.station;
            this.users = group.users;
            this.keyword = null;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 50%;
}
</style>
