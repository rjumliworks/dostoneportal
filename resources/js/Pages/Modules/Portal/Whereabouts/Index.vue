<template>
    <Head title="DTR"/>
    <PageHeader title="Daily Time Record" pageTitle="List" />
    <BRow>
        
    </BRow>
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect },
    data(){
        return {
            selected: {},
            month: new Date().toLocaleString('default', { month: 'long' }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            icons: ['ri-flight-takeoff-fill','ri-car-fill','ri-calendar-2-fill'],
            index: null,
            year: new Date().getFullYear(),
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/dtr';
            axios.get(page_url,{
                params : {
                    month: this.month,
                    year: this.year,
                    count: 10, 
                    option: 'dtr'
                }
            })
            .then(response => {
                if(response){
                    this.selected = response.data;        
                }
            })
            .catch(err => console.log(err));
        }
    }
}
</script>