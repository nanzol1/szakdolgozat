<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch} from 'vue';
import { NDatePicker } from 'naive-ui';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    }
});

const user = usePage().props.auth.user;

const pdfViewer = ref(null);
const isBlurred = ref(true);
const canvasRef = ref(null);
const trigger = ref(false);
const timer = ref(60);
const blurMessage = ref('');
const driverLicenses = ref([]);


onMounted(() => {
    if(user.driverLicense){
        driverLicenses.value = JSON.parse([user.driverLicense]);
    }
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


const form = useForm({
    username: user.username,
    name: user.name,
    email: user.email,
    sex: user.sex,
    birth_date: user.birth_date,
    phone_number: user.phone_number,
    county: user.county,
    zip: user.zip,
    city: user.city,
    cv_file: null,
    driverLicense: [],
});

watch(driverLicenses,(newValue) => {
    form.driverLicense = newValue;
});



  const removeCV = () => {
    if(pdfViewer.value){
        pdfViewer.value.innerHTML = '';
    }
    form.post(route('profile.destroycv'),{
        onFinish: () => {location.reload();}
    });
  };
  const submit = () =>{
    form.post(route('profile.update'), {
        onFinish: () => {
            location.reload();
        },
    });
  }

  watch(trigger,() => {
        timer.value = 60;
        blurMessage.value = 'A blur sikeresen eltávolítva '+timer.value+' másodpercig';
        const interval = setInterval(() =>{
            timer.value--;
            blurMessage.value = 'A blur sikeresen eltávolítva '+timer.value+' másodpercig';
            if(timer.value <= 0){
                clearInterval(interval);
                blurMessage.value = '';
                generateBlur();
            }
        },1000);
    });

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

        <v-form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <div>
                <v-text-field label="Felhasználónév" type="text" v-model="form.username" :error="!!form.errors.username" :error-messages="form.errors.username"></v-text-field>
            </div>
            <div>
                <v-text-field label="Vezetéknév Keresztnév" type="text" v-model="form.name" :error="!!form.errors.name" :error-messages="form.errors.name"></v-text-field>
            </div>

            <div>
                <v-text-field label="E-mail cím" type="email" v-model="form.email" :error="!!form.errors.email" :error-messages="form.errors.email"></v-text-field>
            </div>
            <div>
                <v-select
                    label="Neme"
                    :items="['Férfi', 'Nő']"
                    v-model="form.sex"
                    :error="!!form.errors.sex"
                    :error-messages="form.errors.sex"
                    clearable
                ></v-select>
            </div>
            <div>
                <n-date-picker v-model:value="form.birth_date" type="date" :default-value="Date.now()"
                :update-value-on-close="true" placeholder="Kérem válasszon dátuom" size="large"/>
            </div>
            <div>
                <v-text-field label="Telefonszám" type="text" v-model="form.phone_number" :error="!!form.phone_number" :error-messages="form.phone_number"></v-text-field>
            </div>
            <div>
                <v-text-field label="Megye" type="text" v-model="form.county" :error="!!form.county" :error-messages="form.county"></v-text-field>
            </div>
            <div>
                <v-text-field label="Irányítószám" type="text" v-model="form.zip" :error="!!form.zip" :error-messages="form.zip"></v-text-field>
            </div>
            <div>
                <v-text-field label="Város" type="text" v-model="form.city" :error="!!form.city" :error-messages="form.city"></v-text-field>
            </div>
            <div>
                <v-select
                clearable
                chips
                label="Jogosítvány"
                :items="['A', 'B', 'C', 'D', 'B+E', 'C+E','D+E']"
                v-model="driverLicenses"
                multiple
                ></v-select>
                {{ driverLicenses }}
                <InputLabel for="none" value="Nem rendelkezem jogosítvánnyal" />
                <Checkbox v-model:checked="driverLicenses" id="none" value="none"></Checkbox>
                <InputLabel for="a" value="A" />
                <Checkbox v-model:checked="driverLicenses" id="a" value="a"></Checkbox>
                <InputLabel for="b" value="B" />
                <Checkbox v-model:checked="driverLicenses" id="b" value="b"></Checkbox>
                <InputLabel for="c" value="C" />
                <Checkbox v-model:checked="driverLicenses" id="c" value="c"></Checkbox>
                <InputLabel for="d" value="D" />
                <Checkbox v-model:checked="driverLicenses" id="d" value="d"></Checkbox>
                <InputLabel for="be" value="B+E" />
                <Checkbox v-model:checked="driverLicenses" id="be" value="be"></Checkbox>
                <InputLabel for="ce" value="C+E" />
                <Checkbox v-model:checked="driverLicenses" id="ce" value="ce"></Checkbox>
                <InputLabel for="de" value="D+E" />
                <Checkbox v-model:checked="driverLicenses" id="de" value="de"></Checkbox>

                <InputError class="mt-2" :message="form.errors.driverlicense" />
            </div>
            <div>
                <input type="file" @change="form.cv_file = $event.target.files[0]">

                <InputError class="mt-2" :message="form.errors.cv_file" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
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
        </v-form>
    </section>
    <section class="w-full">
        <p class="text-white mb-3">Feltöltött CV</p>
        <div ref="pdfViewer"></div>
        <div v-if="user.cv_file">
            <PrimaryButton @click="removeCV" class="mt-3">Törlés</PrimaryButton>
        </div>
        <button
            v-if="isBlurred && user.cv_file"
            @click="removeBlur"
            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded"
        >
            Blur eltávolítása
        </button>
        {{ blurMessage }}
    </section>
</template>
