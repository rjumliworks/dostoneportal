<template>
    <b-modal v-model="showModal" @shown="onShown" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Address' : 'Add Address'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <BRow class="g-3">
                        <BCol lg="6" class="mt-3">
                            <InputLabel value="Region"/>
                            <Multiselect :options="regions" object v-model="form.region" label="name" :searchable="true" placeholder="Select Region" />
                        </BCol>
                        <BCol lg="6" class="mt-3">
                            <InputLabel value="Province"/>
                            <Multiselect :options="provinces" object v-model="form.province" label="name" :searchable="true" placeholder="Select Province" />
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel value="Municipality" :message="form.errors.municipality_code"/>
                            <Multiselect :options="municipalities" object v-model="form.municipality" label="name" :searchable="true" placeholder="Select Municipality" />
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel value="Barangay" :message="form.errors.barangay_code"/>
                            <Multiselect :options="barangays" object v-model="form.barangay" label="name" :searchable="true" placeholder="Select Barangay" />
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel value="Street, Landmark, Block, Lot, Unit"/>
                            <TextInput v-model="form.address" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                        </BCol>
                        <BCol lg="6" class="mt-1">
                            <InputLabel value="Zip Code"/>
                            <TextInput v-model="form.zip_code" type="text" class="form-control" :light="true" />
                        </BCol>
                    </BRow>  
                </BCol>
                <BCol lg="12">
                    <!-- <hr class="text-muted mt-3 mb-3"/> -->
                     <div class="alert alert-warning alert-border-left alert-dismissible fade show material-shadow fs-12 fw-semibold mt-3" role="alert">
                       
                        Once the map loads, please pin your location by clicking on the map
                    </div>
                    <div class="mt-2">
                        <Map @set="handleCoordinates" ref="map" class="leaflet-map" style="height: 200px;"/>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing || !isFormValid" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Map from './Map.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: {InputLabel, TextInput, Multiselect, Map },
    props: ['regions'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                address: null,
                zip_code: null,
                region: null,
                province: null,
                municipality: null,
                longitude: null,
                latitude: null,
                barangay: null
            }),
            coordinates: {},
            provinces: [],
            municipalities: [],
            barangays: [],
            index: null,
            showModal: false,
            editable: false,
            selected: null,
            pendingPin: null,
        }
    },
    watch: {
        "form.region"(newVal){
            if(!newVal){
                this.form.province = null;
                this.form.municipality = null;
                this.form.barangay = null;
            }
            this.fetchProvince(newVal?.value);
        },
        "form.province"(newVal){
            if(!newVal){
                this.form.municipality = null;
                this.form.barangay = null;
            }
            this.fetchMunicipality(newVal?.value);
        },
        "form.municipality"(newVal){
            if(!newVal){
                this.form.barangay = null;
            }
            this.fetchBarangay(newVal?.value);
        },
        "form.barangay": function (val) {
            if (val) {
                this.flyToBarangay(val);
            }
        }
    },
    computed: {
        isFormValid() {
            return this.form.region && this.form.province && this.form.municipality && this.form.barangay && this.form.address && this.form.latitude && this.form.longitude;
        }
    },
    methods: { 
        async flyToBarangay(barangay) {
            if (!barangay) return;

            const query = [
                barangay.name,
                this.form.municipality?.name,
                this.form.province?.name,
                this.form.region?.name,
                "Philippines"
            ]
            .filter(Boolean)
            .join(", ");

            try {
                const res = await axios.get(
                    "https://nominatim.openstreetmap.org/search",
                    {
                        params: {
                            q: query,
                            format: "json",
                            limit: 1
                        }
                    }
                );

                if (res.data.length > 0) {
                    const lat = parseFloat(res.data[0].lat);
                    const lng = parseFloat(res.data[0].lon);

                    this.form.latitude = lat;
                    this.form.longitude = lng;

                    this.$refs.map.flyTo(lat, lng);
                }
            } catch (err) {
                console.log(err);
            }
        },
        handleCoordinates(coords) {
            this.coordinates = coords;
            this.form.longitude = this.coordinates.lng;
            this.form.latitude = this.coordinates.lat;
        },
        onShown(){
            this.$refs.map.view();
            if (this.pendingPin) {
                this.$refs.map.setPin(this.pendingPin.lat, this.pendingPin.lng);
            } else {
                this.$refs.map.empty();
            }
        },
        openEdit(selection = null){
            this.editable = !!selection;
            this.form.region = selection?.region ?? null;
            this.form.province = selection?.province ?? null;
            this.form.municipality = selection?.municipality ?? null;
            this.form.barangay = selection?.barangay ?? null;
            this.form.address = selection?.info ?? null;
            this.form.zip_code = selection?.zip_code ?? null;
            this.form.latitude = selection?.latitude ?? null;
            this.form.longitude = selection?.longitude ?? null;

            this.pendingPin = (selection?.latitude && selection?.longitude)
                ? { lat: parseFloat(selection.latitude), lng: parseFloat(selection.longitude) }
                : null;

            this.showModal = true;
        },
        submit(){
            const address = `${this.form.address}, ${this.form.barangay.name}, ${this.form.municipality.name}, ${this.form.province.name}, ${this.form.region.name}`;
            this.$emit('submit', {
                address: address,
                index: this.index,
                form: {
                    info: this.form.address,
                    zip_code: this.form.zip_code,
                    region: this.form.region,
                    province: this.form.province,
                    municipality: this.form.municipality,
                    barangay: this.form.barangay,
                    longitude: this.form.longitude,
                    latitude: this.form.latitude,
                }
            });

            this.form.region = null;
            this.form.province = null;
            this.form.municipality = null;
            this.form.barangay = null;
            this.form.address = null;
            this.form.zip_code = null;
            this.hide();
        },
        fetchProvince(code){
            if(!code){ this.provinces = []; return; }
            axios.get('/search',{
                params: {
                    option: 'provinces',
                    code: code
                }
            })
            .then(response => {
                this.provinces = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchMunicipality(code){
            if(!code){ this.municipalities = []; return; }
            axios.get('/search',{
                params: {
                    option: 'municipalities',
                    code: code
                }
            })
            .then(response => {
                this.municipalities = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchBarangay(code){
            if(!code){ this.barangays = []; return; }
            axios.get('/search',{
                params: {
                    option: 'barangays',
                    code: code
                }
            })
            .then(response => {
                this.barangays = response.data;
            })
            .catch(err => console.log(err));
        },
        emptyMap(){
            this.$refs.map.empty();
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
.multiselect.is-active {
    z-index: 9999;
}
.multiselect-dropdown {
    z-index: 10000 !important;
}
</style>