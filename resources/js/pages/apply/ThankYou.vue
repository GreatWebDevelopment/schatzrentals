<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Application received" />

    <div class="flex min-h-screen flex-col bg-[#f6f1e7] text-[#1c352a]" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif">
        <div class="h-1.5 w-full bg-[#1c352a]" />
        <main class="mx-auto flex w-full max-w-2xl flex-1 flex-col items-start justify-center px-6 py-24">
            <p class="text-xs font-semibold tracking-[0.35em] text-[#5a7261] uppercase">Schatz Rentals</p>
            <h1 class="mt-5 text-5xl leading-[1.05] font-semibold" style="font-family: 'Fraunces', Georgia, serif">
                Got it. <span class="italic text-[#c97f2b]">Thank you.</span>
            </h1>
            <p class="mt-6 max-w-lg text-lg leading-relaxed text-[#42584c]">
                Your application is in. We read every one, and if it looks like a fit we'll reach out within a couple of
                days to set up a quick call or walkthrough.
            </p>
            <div class="mt-10 rounded-xl border border-[#1c352a]/15 bg-[#fffdf8] px-6 py-5 text-sm leading-relaxed text-[#42584c]">
                <p class="font-semibold text-[#1c352a]">What happens next</p>
                <ol class="mt-2 list-decimal space-y-1 pl-5">
                    <li>We review your experience, availability, and rates.</li>
                    <li>If it's a fit, we call to talk through a real unit.</li>
                    <li>We check references and set up your first turnover.</li>
                </ol>
            </div>
        </main>
        <footer class="border-t border-[#1c352a]/15 py-8 text-center text-xs tracking-widest text-[#5a7261] uppercase">
            Schatz Rentals
        </footer>
    </div>
</template>
