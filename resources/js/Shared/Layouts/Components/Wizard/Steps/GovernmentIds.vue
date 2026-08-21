<template>
    <div>
        <h5 class="fs-14 text-primary mb-1">Government Issued ID Numbers</h5>
        <p class="text-muted fs-12 mb-3">Leave blank any you don't have yet — you can add these later from your Profile page.</p>
        <div class="card-body bg-light-subtle p-4 rounded-3">
            <form class="customform">
                <BRow class="g-3 mt-1">
                    <BCol lg="3" class="mt-0" v-for="(account, index) in form.accounts" :key="account.name">
                        <InputLabel :value="account.name" :message="form.errors['accounts.'+index+'.number']"/>
                        <TextInput v-model="account.number" type="text" class="form-control"/>
                    </BCol>
                </BRow>
            </form>
        </div>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';

const DEFAULT_ACCOUNTS = [
    { name: 'UMID', number: null, deduction: null, is_contribution: false },
    { name: 'Pag-Ibig', number: null, deduction: null, is_contribution: true },
    { name: 'PhilHealth', number: null, deduction: null, is_contribution: true },
    { name: 'PhilSys', number: null, deduction: null, is_contribution: false },
    { name: 'TIN', number: null, deduction: null, is_contribution: false },
    { name: 'SSS', number: null, deduction: null, is_contribution: true },
    { name: 'GSIS', number: null, deduction: null, is_contribution: true },
    { name: 'LandBank', number: null, deduction: null, is_contribution: false },
];

function normalizeAccounts(value){
    if (Array.isArray(value)) return value;
    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value);
            if (Array.isArray(parsed)) return parsed;
        } catch (e) {
            // legacy double-encoded/corrupted data, fall through to default
        }
    }
    return [];
}

export default {
    components: { TextInput, InputLabel },
    props: ['data'],
    data(){
        const existing = normalizeAccounts(this.data.userInformation?.accounts);
        const accounts = DEFAULT_ACCOUNTS.map(def => {
            const match = existing.find(a => a.name === def.name);
            return match ? { ...def, ...match } : { ...def };
        });
        return {
            form: useForm({
                accounts,
                option: 'government_ids'
            })
        }
    },
    methods: {
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
