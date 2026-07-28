<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text" v-model="search" placeholder="Search Session" class="form-control" style="width: 30%;">
                     
                    <b-button type="button" variant="primary" @click="generateLinksPdf">
                        <i class="ri-printer-fill search-icon me-1"></i> Generate Links
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 460px);">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 4%;"></th>
                    <th style="width: 16%;">Session</th>
                    <th class="text-center">Registration Link</th>
                    <th class="text-center">VIP Link</th>
                    <th class="text-center">Attendance Link</th>
                </tr>
            </thead>
            <tbody v-if="filteredSessions.length > 0">
                <tr v-for="(list,index) in filteredSessions" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.title}}</h5>
                        <p class="fs-12 text-muted mb-0">{{ dateRange(list.schedules) }}</p>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" readonly :value="list.registration" @click="$event.target.select()">
                            <b-button type="button" :variant="isCopied(index,'registration') ? 'success' : 'soft-primary'"
                                v-b-tooltip.hover :title="isCopied(index,'registration') ? 'Copied!' : 'Copy'"
                                @click="copyLink($event, index, 'registration')">
                                <i :class="isCopied(index,'registration') ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                            </b-button>
                            <b-button type="button" variant="soft-secondary" v-b-tooltip.hover title="Open in new tab"
                                @click="openLink(list.registration)">
                                <i class="ri-external-link-line"></i>
                            </b-button>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" readonly :value="list.vip" @click="$event.target.select()">
                            <b-button type="button" :variant="isCopied(index,'vip') ? 'success' : 'soft-primary'"
                                v-b-tooltip.hover :title="isCopied(index,'vip') ? 'Copied!' : 'Copy'"
                                @click="copyLink($event, index, 'vip')">
                                <i :class="isCopied(index,'vip') ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                            </b-button>
                            <b-button type="button" variant="soft-secondary" v-b-tooltip.hover title="Open in new tab"
                                @click="openLink(list.vip)">
                                <i class="ri-external-link-line"></i>
                            </b-button>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" readonly :value="list.attendance" @click="$event.target.select()">
                            <b-button type="button" :variant="isCopied(index,'attendance') ? 'success' : 'soft-primary'"
                                v-b-tooltip.hover :title="isCopied(index,'attendance') ? 'Copied!' : 'Copy'"
                                @click="copyLink($event, index, 'attendance')">
                                <i :class="isCopied(index,'attendance') ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                            </b-button>
                            <b-button type="button" variant="soft-secondary" v-b-tooltip.hover title="Open in new tab"
                                @click="openLink(list.attendance)">
                                <i class="ri-external-link-line"></i>
                            </b-button>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center text-muted">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['id','sessions'],
    data(){
        return {
            search: '',
            copied: null,
        }
    },
    computed: {
        filteredSessions(){
            if(!this.search) return this.sessions;
            const term = this.search.toLowerCase();
            return this.sessions.filter(list => list.title.toLowerCase().includes(term));
        }
    },
    methods: {
        isCopied(index, type){
            return this.copied === `${index}-${type}`;
        },
        markCopied(index, type){
            const key = `${index}-${type}`;
            this.copied = key;
            setTimeout(() => {
                if (this.copied === key) this.copied = null;
            }, 2000);
        },
        openLink(url){
            if (!url) return;
            window.open(url, '_blank', 'noopener');
        },
        generateLinksPdf(){
            const ids = this.filteredSessions.map(list => list.id).join(',');
            if (!ids) return;
            window.open('/sessions?option=links&ids='+ids, '_blank', 'noopener');
        },
        copyLink(event, index, type){
            const input = event.target.closest('.input-group').querySelector('input');
            if (!input || !input.value) return;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(input.value).then(() => {
                    this.markCopied(index, type);
                }).catch(() => {});
                return;
            }

            input.select();
            input.setSelectionRange(0, 99999);
            try {
                document.execCommand('copy');
                this.markCopied(index, type);
            } catch (err) {
                // ignore copy failures silently
            }
            window.getSelection().removeAllRanges();
        },
        dateRange(schedules) {
            if (!schedules || schedules.length === 0) return 'No date';

            let start = schedules[0].date;
            let end = schedules[0].date;

            schedules.forEach(item => {
                if (item.date < start) start = item.date;
                if (item.date > end) end = item.date;
            });

            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            return start === end
                ? formatDate(start)
                : `${formatDate(start)} - ${formatDate(end)}`;
        }
    }
}
</script>
