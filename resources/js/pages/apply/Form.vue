<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

interface ReferenceRow {
    name: string;
    phone: string;
    relationship: string;
}

const form = useForm({
    name: '',
    email: '',
    phone: '',
    business_name: '',
    service_area: '',
    years_experience: '',
    has_turnover_experience: null as boolean | null,
    experience_details: '',
    services: [] as string[],
    lead_time: '',
    crew_size: '',
    has_backup: null as boolean | null,
    weekend_availability: null as boolean | null,
    is_insured: null as boolean | null,
    is_bonded: null as boolean | null,
    provides_invoices: null as boolean | null,
    price_1br: '',
    price_2br: '',
    price_3br: '',
    pricing_notes: '',
    reclean_guarantee: null as boolean | null,
    sends_photos: null as boolean | null,
    references: [
        { name: '', phone: '', relationship: '' },
        { name: '', phone: '', relationship: '' },
    ] as ReferenceRow[],
    additional_notes: '',
});

type BoolKey =
    | 'has_turnover_experience'
    | 'has_backup'
    | 'weekend_availability'
    | 'is_insured'
    | 'is_bonded'
    | 'provides_invoices'
    | 'reclean_guarantee'
    | 'sends_photos';

const experienceOptions = [
    { value: 'lt1', label: 'Less than 1 year' },
    { value: '1-3', label: '1–3 years' },
    { value: '3-5', label: '3–5 years' },
    { value: '5plus', label: '5+ years' },
];

const leadTimeOptions = [
    { value: 'same_day', label: 'Same day' },
    { value: '1_2_days', label: '1–2 days' },
    { value: '3_5_days', label: '3–5 days' },
    { value: '1_week_plus', label: 'A week or more' },
];

const crewOptions = [
    { value: 'solo', label: 'Just me' },
    { value: '2_3', label: 'Crew of 2–3' },
    { value: '4_plus', label: 'Crew of 4+' },
];

const serviceOptions = [
    'Deep clean (move-out standard)',
    'Trash-out / haul-away',
    'Carpet shampooing',
    'Inside appliances',
    'Blinds & baseboards',
    'Interior windows',
    'Wall washing / scuff removal',
    'Odor treatment',
];

const protectionQuestions: { key: BoolKey; label: string; hint?: string }[] = [
    { key: 'is_insured', label: 'Are you insured?', hint: 'We ask for a certificate of insurance before the first job.' },
    { key: 'is_bonded', label: 'Are you bonded?' },
    { key: 'provides_invoices', label: 'Do you provide written invoices or receipts?' },
];

const qualityQuestions: { key: BoolKey; label: string; hint?: string }[] = [
    { key: 'reclean_guarantee', label: 'If a walkthrough finds missed items, will you re-clean at no charge?' },
    { key: 'sends_photos', label: 'Can you send photos of the finished unit?' },
];

function toggleService(service: string) {
    const i = form.services.indexOf(service);
    if (i === -1) form.services.push(service);
    else form.services.splice(i, 1);
}

function submit() {
    form.post(route('apply.store'), { preserveScroll: true });
}

const sections = [
    { num: '01', title: 'About you' },
    { num: '02', title: 'Experience' },
    { num: '03', title: 'Services offered' },
    { num: '04', title: 'Availability' },
    { num: '05', title: 'Business & pricing' },
    { num: '06', title: 'Quality & references' },
];
</script>

<template>
    <Head title="Apply — Turnover Cleaner">
        <meta
            name="description"
            content="Schatz Rentals is hiring move-out / turnover cleaners for rental units. Apply in about five minutes."
        />
    </Head>

    <div class="apply-page min-h-screen bg-[#f6f1e7] text-[#1c352a]">
        <!-- Top rule -->
        <div class="h-1.5 w-full bg-[#1c352a]" />

        <!-- Masthead -->
        <header class="mx-auto max-w-4xl px-6 pt-14 pb-10 sm:pt-20">
            <p class="text-xs font-semibold tracking-[0.35em] text-[#5a7261] uppercase">Schatz Rentals · Now hiring</p>
            <h1 class="font-display mt-5 text-4xl leading-[1.05] font-semibold sm:text-6xl">
                Turnover cleaners<br />
                <span class="italic text-[#c97f2b]">wanted.</span>
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-[#42584c]">
                We manage rental units and need dependable cleaners for move-out turnovers — the real kind, not light dusting.
                Steady work, fast pay, clear expectations. This application takes about five minutes.
            </p>
            <div class="mt-8 flex flex-wrap gap-2 text-sm">
                <span class="rounded-full border border-[#1c352a]/20 bg-[#fffdf8] px-4 py-1.5">Ongoing work</span>
                <span class="rounded-full border border-[#1c352a]/20 bg-[#fffdf8] px-4 py-1.5">Paid per unit</span>
                <span class="rounded-full border border-[#1c352a]/20 bg-[#fffdf8] px-4 py-1.5">Flexible schedule</span>
            </div>
        </header>

        <div class="border-t border-[#1c352a]/15" />

        <form class="mx-auto max-w-4xl px-6 pt-12 pb-24" @submit.prevent="submit" novalidate>
            <!-- 01 · About you -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[0].num }}</span>
                    <h2 class="section-title">{{ sections[0].title }}</h2>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="field">
                        <label class="flabel" for="name">Full name <span class="req">*</span></label>
                        <input id="name" v-model="form.name" type="text" class="finput" autocomplete="name" />
                        <p v-if="form.errors.name" class="ferror">{{ form.errors.name }}</p>
                    </div>
                    <div class="field">
                        <label class="flabel" for="business_name">Business name <span class="opt">(if any)</span></label>
                        <input id="business_name" v-model="form.business_name" type="text" class="finput" autocomplete="organization" />
                    </div>
                    <div class="field">
                        <label class="flabel" for="email">Email <span class="req">*</span></label>
                        <input id="email" v-model="form.email" type="email" class="finput" autocomplete="email" />
                        <p v-if="form.errors.email" class="ferror">{{ form.errors.email }}</p>
                    </div>
                    <div class="field">
                        <label class="flabel" for="phone">Phone <span class="req">*</span></label>
                        <input id="phone" v-model="form.phone" type="tel" class="finput" autocomplete="tel" />
                        <p v-if="form.errors.phone" class="ferror">{{ form.errors.phone }}</p>
                    </div>
                    <div class="field sm:col-span-2">
                        <label class="flabel" for="service_area">City / area you work in</label>
                        <input id="service_area" v-model="form.service_area" type="text" class="finput" placeholder="e.g. Denver metro" />
                    </div>
                </div>
            </section>

            <!-- 02 · Experience -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[1].num }}</span>
                    <h2 class="section-title">{{ sections[1].title }}</h2>
                </div>
                <div class="space-y-7">
                    <div class="field">
                        <span class="flabel">Years of professional cleaning experience <span class="req">*</span></span>
                        <div class="pill-group">
                            <button
                                v-for="opt in experienceOptions"
                                :key="opt.value"
                                type="button"
                                class="pill"
                                :class="{ 'pill-active': form.years_experience === opt.value }"
                                @click="form.years_experience = opt.value"
                            >
                                {{ opt.label }}
                            </button>
                        </div>
                        <p v-if="form.errors.years_experience" class="ferror">{{ form.errors.years_experience }}</p>
                    </div>

                    <div class="field">
                        <span class="flabel">Have you done move-out / turnover cleans for landlords or property managers? <span class="req">*</span></span>
                        <div class="pill-group">
                            <button type="button" class="pill" :class="{ 'pill-active': form.has_turnover_experience === true }" @click="form.has_turnover_experience = true">Yes</button>
                            <button type="button" class="pill" :class="{ 'pill-active': form.has_turnover_experience === false }" @click="form.has_turnover_experience = false">No</button>
                        </div>
                        <p v-if="form.errors.has_turnover_experience" class="ferror">{{ form.errors.has_turnover_experience }}</p>
                    </div>

                    <div class="field">
                        <label class="flabel" for="experience_details">
                            Tell us about the roughest unit you've cleaned — what condition was it in, and what did you do?
                        </label>
                        <textarea id="experience_details" v-model="form.experience_details" rows="4" class="finput resize-y" />
                        <p class="fhint">Post-eviction units can be rough. We want someone who doesn't flinch.</p>
                    </div>
                </div>
            </section>

            <!-- 03 · Services -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[2].num }}</span>
                    <h2 class="section-title">{{ sections[2].title }}</h2>
                </div>
                <p class="mb-4 -mt-2 text-sm text-[#5a7261]">Check everything you offer.</p>
                <div class="grid gap-2.5 sm:grid-cols-2">
                    <button
                        v-for="service in serviceOptions"
                        :key="service"
                        type="button"
                        class="service-card"
                        :class="{ 'service-card-active': form.services.includes(service) }"
                        @click="toggleService(service)"
                    >
                        <span class="checkbox-dot" :class="{ 'checkbox-dot-active': form.services.includes(service) }">
                            <svg v-if="form.services.includes(service)" viewBox="0 0 12 12" class="h-2.5 w-2.5 fill-none stroke-[#f6f1e7] stroke-2"><path d="M2 6.5 4.5 9 10 3" /></svg>
                        </span>
                        {{ service }}
                    </button>
                </div>
            </section>

            <!-- 04 · Availability -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[3].num }}</span>
                    <h2 class="section-title">{{ sections[3].title }}</h2>
                </div>
                <div class="space-y-7">
                    <div class="field">
                        <span class="flabel">How much lead time do you need for a turnover? <span class="req">*</span></span>
                        <div class="pill-group">
                            <button
                                v-for="opt in leadTimeOptions"
                                :key="opt.value"
                                type="button"
                                class="pill"
                                :class="{ 'pill-active': form.lead_time === opt.value }"
                                @click="form.lead_time = opt.value"
                            >
                                {{ opt.label }}
                            </button>
                        </div>
                        <p v-if="form.errors.lead_time" class="ferror">{{ form.errors.lead_time }}</p>
                    </div>

                    <div class="field">
                        <span class="flabel">Who shows up to the job? <span class="req">*</span></span>
                        <div class="pill-group">
                            <button
                                v-for="opt in crewOptions"
                                :key="opt.value"
                                type="button"
                                class="pill"
                                :class="{ 'pill-active': form.crew_size === opt.value }"
                                @click="form.crew_size = opt.value"
                            >
                                {{ opt.label }}
                            </button>
                        </div>
                        <p v-if="form.errors.crew_size" class="ferror">{{ form.errors.crew_size }}</p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="field">
                            <span class="flabel">If you're sick or double-booked, do you have backup? <span class="req">*</span></span>
                            <div class="pill-group">
                                <button type="button" class="pill" :class="{ 'pill-active': form.has_backup === true }" @click="form.has_backup = true">Yes</button>
                                <button type="button" class="pill" :class="{ 'pill-active': form.has_backup === false }" @click="form.has_backup = false">No</button>
                            </div>
                            <p v-if="form.errors.has_backup" class="ferror">{{ form.errors.has_backup }}</p>
                        </div>
                        <div class="field">
                            <span class="flabel">Available on weekends? <span class="req">*</span></span>
                            <div class="pill-group">
                                <button type="button" class="pill" :class="{ 'pill-active': form.weekend_availability === true }" @click="form.weekend_availability = true">Yes</button>
                                <button type="button" class="pill" :class="{ 'pill-active': form.weekend_availability === false }" @click="form.weekend_availability = false">No</button>
                            </div>
                            <p v-if="form.errors.weekend_availability" class="ferror">{{ form.errors.weekend_availability }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 05 · Business & pricing -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[4].num }}</span>
                    <h2 class="section-title">{{ sections[4].title }}</h2>
                </div>
                <div class="space-y-7">
                    <div v-for="q in protectionQuestions" :key="q.key" class="field">
                        <span class="flabel">{{ q.label }} <span class="req">*</span></span>
                        <div class="pill-group">
                            <button type="button" class="pill" :class="{ 'pill-active': form[q.key] === true }" @click="form[q.key] = true">Yes</button>
                            <button type="button" class="pill" :class="{ 'pill-active': form[q.key] === false }" @click="form[q.key] = false">No</button>
                        </div>
                        <p v-if="q.hint" class="fhint">{{ q.hint }}</p>
                        <p v-if="form.errors[q.key]" class="ferror">{{ form.errors[q.key] }}</p>
                    </div>

                    <div class="field">
                        <span class="flabel">Your flat rate for a full move-out clean</span>
                        <p class="fhint mb-3">Ballpark is fine — empty unit, moderate condition.</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div v-for="(label, key) in { price_1br: '1 bed / 1 bath', price_2br: '2 bed / 1 bath', price_3br: '3 bed / 2 bath' }" :key="key">
                                <label :for="key" class="mb-1.5 block text-xs font-medium text-[#5a7261]">{{ label }}</label>
                                <div class="relative">
                                    <span class="absolute top-1/2 left-3 -translate-y-1/2 text-sm text-[#5a7261]">$</span>
                                    <input :id="key" v-model="form[key]" type="number" min="0" step="5" class="finput pl-7" placeholder="0" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="flabel" for="pricing_notes">Anything else about your pricing?</label>
                        <textarea id="pricing_notes" v-model="form.pricing_notes" rows="2" class="finput resize-y" placeholder="Hourly rates, add-on charges, minimums…" />
                    </div>
                </div>
            </section>

            <!-- 06 · Quality & references -->
            <section class="form-section">
                <div class="section-head">
                    <span class="section-num">{{ sections[5].num }}</span>
                    <h2 class="section-title">{{ sections[5].title }}</h2>
                </div>
                <div class="space-y-7">
                    <div v-for="q in qualityQuestions" :key="q.key" class="field">
                        <span class="flabel">{{ q.label }} <span class="req">*</span></span>
                        <div class="pill-group">
                            <button type="button" class="pill" :class="{ 'pill-active': form[q.key] === true }" @click="form[q.key] = true">Yes</button>
                            <button type="button" class="pill" :class="{ 'pill-active': form[q.key] === false }" @click="form[q.key] = false">No</button>
                        </div>
                        <p v-if="q.hint" class="fhint">{{ q.hint }}</p>
                        <p v-if="form.errors[q.key]" class="ferror">{{ form.errors[q.key] }}</p>
                    </div>

                    <div class="field">
                        <span class="flabel">References <span class="opt">(landlords or property managers preferred)</span></span>
                        <div class="mt-3 space-y-3">
                            <div v-for="(ref, i) in form.references" :key="i" class="grid gap-3 rounded-xl border border-[#1c352a]/15 bg-[#fffdf8] p-4 sm:grid-cols-3">
                                <input v-model="ref.name" type="text" class="finput" :placeholder="`Reference ${i + 1} name`" />
                                <input v-model="ref.phone" type="tel" class="finput" placeholder="Phone" />
                                <input v-model="ref.relationship" type="text" class="finput" placeholder="Relationship (e.g. landlord)" />
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="flabel" for="additional_notes">Anything else you'd like us to know?</label>
                        <textarea id="additional_notes" v-model="form.additional_notes" rows="3" class="finput resize-y" />
                    </div>
                </div>
            </section>

            <!-- Submit -->
            <div class="mt-14 border-t border-[#1c352a]/15 pt-10">
                <div v-if="Object.keys(form.errors).length" class="mb-6 rounded-xl border border-[#b3431e]/30 bg-[#b3431e]/5 px-5 py-4 text-sm text-[#b3431e]">
                    A few required questions are missing — scroll up to the fields marked in red.
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="group inline-flex items-center gap-3 rounded-full bg-[#1c352a] px-9 py-4 text-base font-semibold text-[#f6f1e7] transition-colors hover:bg-[#c97f2b] disabled:opacity-60"
                >
                    <span v-if="form.processing">Sending…</span>
                    <span v-else>Submit application</span>
                    <svg viewBox="0 0 20 20" class="h-4 w-4 fill-none stroke-current stroke-2 transition-transform group-hover:translate-x-1"><path d="M3 10h13m-5-5 5 5-5 5" /></svg>
                </button>
                <p class="mt-4 text-sm text-[#5a7261]">We read every application and reply within a couple of days.</p>
            </div>
        </form>

        <footer class="border-t border-[#1c352a]/15 py-8 text-center text-xs tracking-widest text-[#5a7261] uppercase">
            Schatz Rentals
        </footer>
    </div>
</template>

<style scoped>
.apply-page {
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    background-image: radial-gradient(rgba(28, 53, 42, 0.045) 1px, transparent 1px);
    background-size: 22px 22px;
}
.font-display {
    font-family: 'Fraunces', Georgia, serif;
}
.form-section {
    padding-top: 3rem;
}
.section-head {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    margin-bottom: 1.75rem;
    border-bottom: 2px solid #1c352a;
    padding-bottom: 0.75rem;
}
.section-num {
    font-family: 'Fraunces', Georgia, serif;
    font-style: italic;
    font-size: 1.1rem;
    color: #c97f2b;
}
.section-title {
    font-family: 'Fraunces', Georgia, serif;
    font-size: 1.65rem;
    font-weight: 600;
}
.flabel {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.925rem;
    font-weight: 600;
    color: #1c352a;
}
.req {
    color: #c97f2b;
}
.opt {
    font-weight: 400;
    color: #5a7261;
}
.finput {
    width: 100%;
    border-radius: 0.65rem;
    border: 1px solid rgba(28, 53, 42, 0.2);
    background: #fffdf8;
    padding: 0.7rem 0.9rem;
    font-size: 0.95rem;
    color: #1c352a;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.finput:focus {
    border-color: #1c352a;
    box-shadow: 0 0 0 3px rgba(28, 53, 42, 0.12);
}
.finput::placeholder {
    color: rgba(90, 114, 97, 0.6);
}
.fhint {
    margin-top: 0.45rem;
    font-size: 0.8rem;
    color: #5a7261;
}
.ferror {
    margin-top: 0.45rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: #b3431e;
}
.pill-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.25rem;
}
.pill {
    border-radius: 9999px;
    border: 1px solid rgba(28, 53, 42, 0.25);
    background: #fffdf8;
    padding: 0.5rem 1.1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #1c352a;
    cursor: pointer;
    transition: all 0.15s;
}
.pill:hover {
    border-color: #1c352a;
}
.pill-active {
    background: #1c352a;
    border-color: #1c352a;
    color: #f6f1e7;
}
.service-card {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(28, 53, 42, 0.18);
    background: #fffdf8;
    padding: 0.8rem 1rem;
    font-size: 0.9rem;
    text-align: left;
    color: #1c352a;
    cursor: pointer;
    transition: all 0.15s;
}
.service-card:hover {
    border-color: #1c352a;
}
.service-card-active {
    border-color: #1c352a;
    background: rgba(28, 53, 42, 0.05);
}
.checkbox-dot {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 1.15rem;
    width: 1.15rem;
    flex-shrink: 0;
    border-radius: 0.3rem;
    border: 1.5px solid rgba(28, 53, 42, 0.35);
    background: transparent;
    transition: all 0.15s;
}
.checkbox-dot-active {
    background: #1c352a;
    border-color: #1c352a;
}
</style>
