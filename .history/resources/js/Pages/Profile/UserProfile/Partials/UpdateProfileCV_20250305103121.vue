<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const user = usePage().props.auth.user;

const submit = () =>{
    form.post(route('profile.save.cvfile'));
};

const form = useForm({
    cv_file: null,
});

onMounted(() => {
    displayCV();
});
function displayCV() {
    if(user.cv_file){
        var url = "../storage/uploads/cv/"+user.cv_file;
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
            var scale = 1.0;
            var viewport = page.getViewport({ scale: scale });
            
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            var renderContext = {
              canvasContext: context,
              viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                canvasRef.value = canvas;
                
                if(isBlurred.value){
                    generateBlur();
                }

            });

            pdfViewer.value.innerHTML = '';
            pdfViewer.value.appendChild(canvas);
            });
        });
    }
}
const removeCV = () => {
    if(pdfViewer.value){
        pdfViewer.value.innerHTML = '';
    }
    form.post(route('profile.destroycv'),{
        onFinish: () => {location.reload();}
    });
}; 



function generateBlur(){
    if(canvasRef.value){
        const ctx = canvasRef.value.getContext("2d");
        ctx.globalAlpha = 1;
        ctx.filter = "blur(8px)";
        ctx.drawImage(canvasRef.value,0,0); 
        isBlurred.value = true;
    }
}

function removeBlur(){
    if(canvasRef.value){
        const ctx = canvasRef.value.getContext("2d");
        ctx.filter = "none";
        isBlurred.value = false;
        trigger.value = true;
        displayCV(); 
    }
}

</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Önéletrajz
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ezen az oldalon tudja feltölteni / törölni önéletrajzát
            </p>
        </header>
        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <div>
                <InputLabel for="cv_file" value="Profile Photo" />

                <input type="file" name="cv_file" id="cv_file" @change="renderImage">

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
    </section>
</template>
