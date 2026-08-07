<template>
    <div>
        <h5 class="fs-14 text-primary mb-3">Family Background</h5>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12"><span class="fw-semibold fs-12 text-body">Spouse</span><hr class="mt-1 mb-0"/></BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Lastname"/>
                    <TextInput v-model="form.spouse.lastname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Firstname"/>
                    <TextInput v-model="form.spouse.firstname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Middlename"/>
                    <TextInput v-model="form.spouse.middlename" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Suffix"/>
                    <TextInput v-model="form.spouse.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Occupation"/>
                    <TextInput v-model="form.spouse.occupation" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Employer / Business Name"/>
                    <TextInput v-model="form.spouse.company" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Contact No."/>
                    <TextInput v-model="form.spouse.contact_no" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Business Address"/>
                    <TextInput v-model="form.spouse.address" type="text" class="form-control" :light="true"/>
                </BCol>

                <BCol lg="12" class="mt-3"><span class="fw-semibold fs-12 text-body">Father</span><hr class="mt-1 mb-0"/></BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Lastname"/>
                    <TextInput v-model="form.parents.father.lastname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Firstname"/>
                    <TextInput v-model="form.parents.father.firstname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Middlename"/>
                    <TextInput v-model="form.parents.father.middlename" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Suffix"/>
                    <TextInput v-model="form.parents.father.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                </BCol>

                <BCol lg="12" class="mt-3"><span class="fw-semibold fs-12 text-body">Mother's Maiden Name</span><hr class="mt-1 mb-0"/></BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Lastname"/>
                    <TextInput v-model="form.parents.mother.lastname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Firstname"/>
                    <TextInput v-model="form.parents.mother.firstname" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Middlename"/>
                    <TextInput v-model="form.parents.mother.middlename" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-2">
                    <InputLabel value="Suffix"/>
                    <TextInput v-model="form.parents.mother.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                </BCol>

                <BCol lg="12" class="mt-3 d-flex align-items-center justify-content-between">
                    <span class="fw-semibold fs-12 text-body">Children</span>
                    <b-button variant="soft-primary" size="sm" type="button" @click="addChild()"><i class="ri-add-line align-bottom"></i> Add Child</b-button>
                </BCol>
                <BCol lg="12"><hr class="mt-1 mb-2"/></BCol>
                <BCol lg="12" v-if="form.children.length === 0" class="text-muted fs-12">No children added yet.</BCol>
                <template v-for="(child, index) in form.children" :key="index">
                    <BCol lg="7" class="mt-1">
                        <InputLabel :value="'Child ' + (index+1) + ' Full Name'"/>
                        <TextInput v-model="child.name" type="text" class="form-control" :light="true"/>
                    </BCol>
                    <BCol lg="4" class="mt-1">
                        <InputLabel value="Birthdate"/>
                        <TextInput v-model="child.birthdate" type="date" class="form-control" :light="true"/>
                    </BCol>
                    <BCol lg="1" class="mt-1 d-flex align-items-end">
                        <b-button variant="soft-danger" size="sm" type="button" @click="removeChild(index)"><i class="ri-delete-bin-fill"></i></b-button>
                    </BCol>
                </template>
            </BRow>
        </form>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { TextInput, InputLabel },
    props: ['data'],
    data(){
        const backgrounds = this.data.userInformation?.backgrounds || {};
        return {
            form: useForm({
                parents: {
                    father: {
                        lastname: backgrounds.parents?.father?.lastname ?? null,
                        firstname: backgrounds.parents?.father?.firstname ?? null,
                        middlename: backgrounds.parents?.father?.middlename ?? null,
                        suffix: backgrounds.parents?.father?.suffix ?? null,
                        address: backgrounds.parents?.father?.address ?? null,
                    },
                    mother: {
                        lastname: backgrounds.parents?.mother?.lastname ?? null,
                        firstname: backgrounds.parents?.mother?.firstname ?? null,
                        middlename: backgrounds.parents?.mother?.middlename ?? null,
                        suffix: backgrounds.parents?.mother?.suffix ?? null,
                        address: backgrounds.parents?.mother?.address ?? null,
                    },
                },
                spouse: {
                    lastname: backgrounds.spouse?.lastname ?? null,
                    firstname: backgrounds.spouse?.firstname ?? null,
                    middlename: backgrounds.spouse?.middlename ?? null,
                    suffix: backgrounds.spouse?.suffix ?? null,
                    address: backgrounds.spouse?.address ?? null,
                    contact_no: backgrounds.spouse?.contact_no ?? null,
                    occupation: backgrounds.spouse?.occupation ?? null,
                    company: backgrounds.spouse?.company ?? null,
                },
                children: backgrounds.children ? backgrounds.children.map(c => ({ ...c })) : [],
                option: 'family_background'
            })
        }
    },
    methods: {
        addChild(){
            this.form.children.push({ name: null, birthdate: null });
        },
        removeChild(index){
            this.form.children.splice(index, 1);
        },
        proceed(){
            this.form.post('/profile/pds', {
                preserveScroll: true,
                onSuccess: () => this.$emit('saved'),
                onError: () => this.$emit('failed'),
            });
        }
    }
}
</script>
