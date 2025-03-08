<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';

</script>
<template>
    <GuestPageLayout>
        <div class="flex w-full h-100 flex-col">
            <v-container fluid class="flex bg-gray-300">
                <v-row class="justify-center align-center">
                    <v-col cols="12">
                        <div class="w-full text-center py-6">
                            <div class="text-h3">Sütik</div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height">
                <v-container class="fill-height">
                    <v-row>
                        <v-col cols="12" class="h-100 align-center flex">
                            <h5 class="text-h5">Sütik használata az oldalon</h5>
                            <p>Weboldalunk a legjobb felhasználói élmény biztosítása érdekében sütiket (cookie-kat) alkalmaz. Az oldalon kizárólag olyan sütiket használunk, amelyek elengedhetetlenek a bejelentkezéshez és az oldal biztonságos működéséhez.</p>                  
                            <h6 class="text-h6">A weboldalon alkalmazott sütik a következő célokat szolgálják:</h6>
                            <ul>
                                <li><strong>Bejelentkezési adatkezelés:</strong> A bejelentkezéskor a rendszer egy süti segítségével tárolja a felhasználó adatait, hogy biztosítsa a bejelentkezés utáni zökkenőmentes böngészést. Ezen sütik szükségesek ahhoz, hogy a felhasználó jogosultságait megfelelően kezelhessük.</li>
                                <li><strong>CSRF Token:</strong> A biztonságos műveletek érdekében (például űrlapok beküldése) egy CSRF (Cross-Site Request Forgery) token kerül tárolásra. Ez a süti segít abban, hogy megakadályozza a nem kívánt, harmadik fél általi kéréseket.</li>
                                <li><strong>Laravel Session:</strong> A Laravel keretrendszer által használt session süti biztosítja, hogy a felhasználó munkamenete megfelelően működjön, és a rendszer emlékezzen a felhasználó bejelentkezési állapotára.</li>
                            </ul>
                            <p>Ezek a sütik nem tárolnak személyes adatokat, és kizárólag a weboldal funkcionális működéséhez szükségesek. Az általunk használt sütik nem követik a felhasználókat más weboldalakon.</p>
                            <p>A sütik tárolása és kezelése a felhasználó beleegyezésével történik. A böngésző beállításaival lehetőség van a sütik kezelésére, azok törlésére vagy blokkolására, de ez befolyásolhatja a weboldal működését.</p>
                            <p>További információkért kérjük, olvassa el az adatkezelési tájékoztatónkat.</p>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </GuestPageLayout>
</template>
<style scoped>
    body{
        counter-reset: counter;
    }
    p{
        font-size: 16px;
        margin-bottom: .5em;
    }
    ul{
        padding-left:1em;
        margin: 1em auto;
        font-size: 16px;
    }
    ul li{
        counter-increment: counter;
        margin-bottom: 1em;
    }
    ul li::before{
        content: counter(counter) ". ";
    }
</style>