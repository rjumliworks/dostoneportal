<template>
<div class="">
    <form @submit.prevent="submit()" class="customform">
        <BCard no-body>
            <BCardHeader>
                <BCardTitle>Address Details</BCardTitle>
                <p class="text-muted mb-0">Update your account's profile information and email address.</p>
            </BCardHeader>
            <BCardBody class="p-4" style="height: calc(100vh - 300px); overflow: auto;">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th style="width: 4%;"></th>
                                <th>Address</th>
                                <th style="width: 17%;" class="text-center">Barangay</th>
                                <th style="width: 20%;" class="text-center">Municipality</th>
                                <th style="width: 20%;" class="text-center">Province</th>
                                <th style="width: 15%;" class="text-center">Region</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </BCardBody>
        </BCard>
    </form>
</div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            form: useForm({
                username: this.$page.props.user.data.username,
                email: this.$page.props.user.data.email,
                firstname: this.$page.props.user.data.firstname,
                middlename: this.$page.props.user.data.middlename,
                lastname: this.$page.props.user.data.lastname,
                suffix: this.$page.props.user.data.suffix,
                sex: this.$page.props.user.data.sex,
                mobile: this.$page.props.user.data.mobile,
            }),
        }
    },
    methods: {
        submit(){
            this.form.put('/profile/updated', {
                errorBag: "submit",
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {},
            });
        }
    }
}
</script>