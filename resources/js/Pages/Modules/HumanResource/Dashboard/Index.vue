<template>
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="List" />
    
    <BRow class="g-3" style="height: calc(100vh - 300px); overflow: auto;">
        <BCol xl="4">
            <Employee :employee="employee"/>
        </BCol>
        <BCol xl="8">
            <Bar ref="bar"/>
        </BCol>
        <BCol xl="4" class="mt-n1">
            <Absent :absences="absences"/>
        </BCol>
    </BRow>

</template>
<script>
import Bar from './Components/Bar.vue';
import Employee from './Components/Employee.vue';
import Absent from './Components/Absent.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['employee','counts','divisions'],
    components: { PageHeader, Employee, Bar, Absent },
    data(){
        return {
            absences: [],
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(){
            axios.get('/humanresource',{
                params : {
                    option: 'top'
                }
            })
            .then(response => {
                this.absences = response.data.absences; 
            })
            .catch(err => console.log(err));
        }
    }
}
</script>