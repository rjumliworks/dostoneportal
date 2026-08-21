<template>
    <div>
        <h5 class="fs-14 text-primary mb-3">Permanent & Home Address</h5>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel value="Permanent Address" :message="form.errors['permanent.address']"/>
                            <TextInput @click="addLocation('permanent')" readonly v-model="permanentLabel" type="text" class="form-control" placeholder="House No., Street, Barangay, City/Municipality, Province" :light="true" />
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="addLocation('permanent')" type="button" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mb-0 mt-n1"/></BCol>
                <BCol lg="10" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="(form.errors.is_same) ? 'text-danger' : ''">Is your Permanent Address the same as your Home Address? Please indicate yes or no.</BCol>
                <BCol lg="2" style="margin-top: 13px; margin-bottom: -12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="sameYes" class="custom-control-input me-2" :value="true" v-model="form.is_same">
                                <label class="custom-control-label fw-normal fs-12" for="sameYes">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="sameNo" class="custom-control-input me-2" :value="false" v-model="form.is_same">
                                <label class="custom-control-label fw-normal fs-12" for="sameNo">No</label>
                            </div>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n2"/></BCol>
                <template v-if="form.is_same === false">
                    <BCol lg="12" class="mt-n2">
                        <div class="d-flex">
                            <div style="width: 100%;">
                                <InputLabel value="Home Address" :message="form.errors['home.address']"/>
                                <TextInput readonly v-model="homeLabel" type="text" class="form-control" placeholder="House No., Street, Barangay, City/Municipality, Province" :light="true" />
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="addLocation('home')" type="button" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                </template>
            </BRow>
        </form>
        <Location :regions="dropdowns.regions" @submit="handleSubmit" ref="location"/>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Location from '@/Shared/Layouts/Components/Modals/Location.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';

function toSelection(a){
    if (!a) return null;
    return {
        info: a.address ?? null,
        zip_code: a.zip_code ?? null,
        region: a.region ? { value: a.region_code, name: a.region.name } : null,
        province: a.province ? { value: a.province_code, name: a.province.name } : null,
        municipality: a.municipality ? { value: a.municipality_code, name: a.municipality.name } : null,
        barangay: a.barangay ? { value: a.barangay_code, name: a.barangay.name } : null,
        latitude: a.latitude ?? null,
        longitude: a.longitude ?? null,
    };
}

function formatLabel(selection){
    if (!selection) return null;
    return [selection.info, selection.barangay?.name, selection.municipality?.name, selection.province?.name, selection.region?.name].filter(Boolean).join(', ');
}

export default {
    components: { Location, TextInput, InputLabel },
    props: ['data', 'dropdowns'],
    data(){
        const addresses = this.data.addresses || [];
        const permanent = addresses.find(a => a.is_permanent) || null;
        const home = addresses.find(a => !a.is_permanent) || null;
        const permanentSelection = toSelection(permanent);
        const homeSelection = toSelection(home);
        return {
            type: null,
            permanentSelection,
            homeSelection,
            permanentLabel: formatLabel(permanentSelection),
            homeLabel: formatLabel(homeSelection),
            form: useForm({
                permanent: {
                    address: permanent?.address ?? null,
                    zip_code: permanent?.zip_code ?? null,
                    region_code: permanent?.region_code ?? null,
                    province_code: permanent?.province_code ?? null,
                    municipality_code: permanent?.municipality_code ?? null,
                    barangay_code: permanent?.barangay_code ?? null,
                    latitude: permanent?.latitude ?? null,
                    longitude: permanent?.longitude ?? null,
                },
                home: {
                    address: home?.address ?? null,
                    zip_code: home?.zip_code ?? null,
                    region_code: home?.region_code ?? null,
                    province_code: home?.province_code ?? null,
                    municipality_code: home?.municipality_code ?? null,
                    barangay_code: home?.barangay_code ?? null,
                    latitude: home?.latitude ?? null,
                    longitude: home?.longitude ?? null,
                },
                is_same: (home && permanent) ? (home.address === permanent.address) : null,
                option: 'address'
            }),
        }
    },
    methods: {
        addLocation(type){
            this.type = type;
            this.$refs.location.openEdit(type === 'permanent' ? this.permanentSelection : this.homeSelection);
        },
        handleSubmit(data){
            if (this.type === 'permanent') {
                this.permanentSelection = data.form;
                this.permanentLabel = data.address;
                this.form.permanent.address = data.form.info;
                this.form.permanent.zip_code = data.form.zip_code;
                this.form.permanent.region_code = data.form.region.value;
                this.form.permanent.province_code = data.form.province.value;
                this.form.permanent.municipality_code = data.form.municipality.value;
                this.form.permanent.barangay_code = data.form.barangay.value;
                this.form.permanent.latitude = data.form.latitude;
                this.form.permanent.longitude = data.form.longitude;
                this.form.clearErrors('permanent.address');
            } else {
                this.homeSelection = data.form;
                this.homeLabel = data.address;
                this.form.home.address = data.form.info;
                this.form.home.zip_code = data.form.zip_code;
                this.form.home.region_code = data.form.region.value;
                this.form.home.province_code = data.form.province.value;
                this.form.home.municipality_code = data.form.municipality.value;
                this.form.home.barangay_code = data.form.barangay.value;
                this.form.home.latitude = data.form.latitude;
                this.form.home.longitude = data.form.longitude;
                this.form.clearErrors('home.address');
            }
        },
        proceed(){
            this.form.put('/profile/updated', {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => this.$emit('saved'),
                onError: () => this.$emit('failed'),
            });
        }
    }
}
</script>
