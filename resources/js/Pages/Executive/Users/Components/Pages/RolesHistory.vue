<template>
   <div class="table-responsive table-card" style="height: calc(100vh - 497px); overflow: auto;">
        <table class="table table-nowrap align-middle mb-0">
           <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th style="width: 4%;"></th>
                    <th>Role</th>
                    <th style="width: 18%;" class="text-center">Added By</th>
                    <th style="width: 18%;" class="text-center">Removed By</th>
                    <th style="width: 26%;" class="text-center">Date</th>
                </tr>
            </thead>
            <tbody v-if="inactiveRoles.length > 0">
                <tr v-for="(list,index) in inactiveRoles" v-bind:key="index">
                    <td class="text-center">{{ index+1 }}.</td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{ list.name }}</h5>
                    </td>
                    <td class="text-center">{{ list.added }}</td>
                    <td class="text-center">{{ list.removed }}</td>
                    <td class="text-center">{{ list.created_at }} - {{ list.removed_at }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center text-muted">No role history found.</td>
                </tr>
            </tbody>
        </table>
   </div>
</template>
<script>
export default {
    props: ['roles'],
    computed: {
        inactiveRoles(){
            if(!this.roles) return [];
            return this.roles
                .filter(list => list.is_active === 0)
                .slice()
                .sort((a,b) => new Date(b.removed_at_raw) - new Date(a.removed_at_raw));
        }
    }
}
</script>
