<template>
    <b-modal v-model="showModal" @shown="onShown" style="--vz-modal-width: 900px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Building' : 'Add Building'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>

        <form class="customform">
            <BRow class="g-3 p-2">
                <BCol lg="6">
                    <BRow class="g-3">
                        <BCol lg="12" class="mt-1">
                            <InputLabel for="name" value="Name" :message="form.errors.name"/>
                            <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                        </BCol>
                        <BCol lg="12" class="mt-0">
                            <InputLabel for="station" value="Station" :message="form.errors.station_id"/>
                            <Multiselect :options="dropdowns.stations" :searchable="true" label="name" v-model="form.station_id" placeholder="Select Station" @input="handleInput('station_id')"/>
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel for="region" value="Region"/>
                            <Multiselect :options="dropdowns.regions" object :searchable="true" label="name" v-model="region" placeholder="Select Region"/>
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel for="province" value="Province"/>
                            <Multiselect :options="provinces" object :searchable="true" label="name" v-model="province" placeholder="Select Province"/>
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel for="municipality" value="Municipality"/>
                            <Multiselect :options="municipalities" object :searchable="true" label="name" v-model="municipality" placeholder="Select Municipality"/>
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel for="barangay" value="Barangay"/>
                            <Multiselect :options="barangays" object :searchable="true" label="name" v-model="barangay" placeholder="Select Barangay"/>
                        </BCol>
                        <BCol lg="12" class="mt-1">
                            <InputLabel for="address" value="Street, Landmark, Block, Lot, Unit" :message="form.errors.address"/>
                            <TextInput id="address" v-model="form.address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true"/>
                        </BCol>
                    </BRow>
                </BCol>
                <BCol lg="6" class="d-flex flex-column">
                    <div class="flex-grow-1">
                        <Map @set="handleCoordinates" ref="map" class="leaflet-map" height="100%"/>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Map from '@/Shared/Layouts/Components/Modals/Map.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Map },
    props: ['dropdowns'],
    data(){
        return {
            id: null,
            editable: false,
            suppressWatch: false,
            region: null,
            province: null,
            municipality: null,
            barangay: null,
            provinces: [],
            municipalities: [],
            barangays: [],
            form: useForm({
                name: null,
                address: null,
                longitude: null,
                latitude: null,
                region_code: null,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                station_id: null,
            }),
            showModal: false
        }
    },
    watch: {
        region(newVal){
            this.form.region_code = newVal?.value ?? null;
            if(!this.suppressWatch){
                this.province = null;
                this.municipality = null;
                this.barangay = null;
            }
            this.fetchProvinces(newVal?.value);
        },
        province(newVal){
            this.form.province_code = newVal?.value ?? null;
            if(!this.suppressWatch){
                this.municipality = null;
                this.barangay = null;
            }
            this.fetchMunicipalities(newVal?.value);
        },
        municipality(newVal){
            this.form.municipality_code = newVal?.value ?? null;
            if(!this.suppressWatch){
                this.barangay = null;
            }
            this.fetchBarangays(newVal?.value);
        },
        barangay(newVal){
            this.form.barangay_code = newVal?.value ?? null;
            if(newVal && !this.suppressWatch){
                this.flyToBarangay(newVal);
            }
        }
    },
    methods: {
        show(){
            this.editable = false;
            this.id = null;
            this.showModal = true;
        },
        edit(data){
            this.editable = true;
            this.id = data.id;
            this.suppressWatch = true;

            this.form.name = data.name;
            this.form.address = data.address;
            this.form.station_id = data.station_id;
            this.form.longitude = data.longitude;
            this.form.latitude = data.latitude;

            this.region = data.region ? { value: data.region.code, name: data.region.region } : null;
            this.province = data.province ? { value: data.province.code, name: data.province.name } : null;
            this.municipality = data.municipality ? { value: data.municipality.code, name: data.municipality.name } : null;
            this.barangay = data.barangay ? { value: data.barangay.code, name: data.barangay.name } : null;

            this.showModal = true;

            this.$nextTick(() => {
                this.suppressWatch = false;
            });
        },
        onShown(){
            this.$refs.map.view();
            if(this.editable && this.form.latitude && this.form.longitude){
                this.$refs.map.setPin(parseFloat(this.form.latitude), parseFloat(this.form.longitude));
            }
        },
        async flyToBarangay(barangay){
            const query = [
                barangay.name,
                this.municipality?.name,
                this.province?.name,
                this.region?.name,
                "Philippines"
            ]
            .filter(Boolean)
            .join(", ");

            try {
                const res = await axios.get("https://nominatim.openstreetmap.org/search", {
                    params: { q: query, format: "json", limit: 1 }
                });

                if(res.data.length > 0){
                    const lat = parseFloat(res.data[0].lat);
                    const lng = parseFloat(res.data[0].lon);
                    this.form.latitude = lat;
                    this.form.longitude = lng;
                    this.$refs.map.setPin(lat, lng);
                }
            } catch (err) {
                console.log(err);
            }
        },
        handleCoordinates(coords){
            this.form.latitude = coords.lat;
            this.form.longitude = coords.lng;
        },
        fetchProvinces(code){
            if(!code){ this.provinces = []; return; }
            axios.get('/search',{ params: { option: 'provinces', code: code } })
            .then(response => { this.provinces = response.data; })
            .catch(err => console.log(err));
        },
        fetchMunicipalities(code){
            if(!code){ this.municipalities = []; return; }
            axios.get('/search',{ params: { option: 'municipalities', code: code } })
            .then(response => { this.municipalities = response.data; })
            .catch(err => console.log(err));
        },
        fetchBarangays(code){
            if(!code){ this.barangays = []; return; }
            axios.get('/search',{ params: { option: 'barangays', code: code } })
            .then(response => { this.barangays = response.data; })
            .catch(err => console.log(err));
        },
        submit(){
            if(this.editable){
                this.form.put(`/buildings/${this.id}`,{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.hide();
                    },
                });
            }else{
                this.form.post('/buildings',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.hide();
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.editable = false;
            this.id = null;
            this.suppressWatch = false;
            this.region = null;
            this.province = null;
            this.municipality = null;
            this.barangay = null;
            this.provinces = [];
            this.municipalities = [];
            this.barangays = [];
            this.$refs.map?.empty();
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
.multiselect__option--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
