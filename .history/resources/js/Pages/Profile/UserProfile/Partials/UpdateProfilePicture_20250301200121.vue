<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    profile_photo:null,
});

const photoRef = ref(user.profile_photo_path || null);

const submit = () =>{
    form.post(route('profile.save.photo'),{
        onSuccess: () => location.reload(),
    });
};

const renderImage = (event) => {
    const file = event.target.files[0];
    if(file && file.type.startsWith('image/')){
        form.cv_picture = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }else{
        imagePreview.value = null;
        form.cv_picture = null;
    }
};

</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <div>
                <InputLabel for="profile_photo" value="Profile Photo" />

                <input type="file" name="profile_photo" id="profile_photo" @change="renderImage">

                <InputError class="mt-2" :message="form.errors.profile_photo" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
    <section class="w-full">
        <p class="text-white mb-3">Profilkép</p>
        <div v-if="user.profile_photo_path">
            <img :src="'storage/uploads/profile_picture/'+user.id+'/'+photoRef" alt="">
        </div>
    </section>
</template>
