<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import {
    type Applicant,
    type ApplicantStatus,
    crewLabels,
    experienceLabels,
    formatDate,
    formatMoney,
    leadTimeLabels,
    statusLabels,
    statusStyles,
} from '@/types/applicant';
import { Head, router, useForm } from '@inertiajs/vue3';
import { CheckCircle2, Mail, MapPin, Phone, Trash2, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    applicant: Applicant;
    criteria: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Applicants', href: '/applicants' },
    { title: props.applicant.name, href: `/applicants/${props.applicant.id}` },
];

const form = useForm({
    status: props.applicant.status as ApplicantStatus,
    admin_notes: props.applicant.admin_notes ?? '',
    interview_at: props.applicant.interview_at ? props.applicant.interview_at.slice(0, 16) : '',
    scores: Object.fromEntries(
        Object.keys(props.criteria).map((key) => [key, props.applicant.scores?.[key] ?? null]),
    ) as Record<string, number | null>,
});

const liveTotal = computed(() => {
    const values = Object.values(form.scores).filter((v): v is number => v !== null);
    return values.length ? values.reduce((a, b) => a + b, 0) : null;
});

function setScore(key: string, value: number) {
    form.scores[key] = form.scores[key] === value ? null : value;
}

function save() {
    form.transform((data) => ({
        ...data,
        interview_at: data.interview_at || null,
    })).patch(route('applicants.update', props.applicant.id), { preserveScroll: true });
}

function destroy() {
    if (confirm(`Delete ${props.applicant.name}'s application? This can't be undone.`)) {
        router.delete(route('applicants.destroy', props.applicant.id));
    }
}

const boolRows = computed(() => [
    { label: 'Move-out / turnover experience', value: props.applicant.has_turnover_experience },
    { label: 'Backup crew if unavailable', value: props.applicant.has_backup },
    { label: 'Weekend availability', value: props.applicant.weekend_availability },
    { label: 'Insured', value: props.applicant.is_insured },
    { label: 'Bonded', value: props.applicant.is_bonded },
    { label: 'Provides invoices', value: props.applicant.provides_invoices },
    { label: 'Free re-clean guarantee', value: props.applicant.reclean_guarantee },
    { label: 'Sends completion photos', value: props.applicant.sends_photos },
]);

const statusOptions: ApplicantStatus[] = ['new', 'interviewing', 'hired', 'rejected'];
</script>

<template>
    <Head :title="props.applicant.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid gap-5 p-4 lg:grid-cols-[1fr_380px]">
            <!-- Application details -->
            <div class="space-y-5">
                <div class="rounded-xl border p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h1 class="text-xl font-semibold">{{ props.applicant.name }}</h1>
                            <p v-if="props.applicant.business_name" class="text-sm text-muted-foreground">{{ props.applicant.business_name }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-medium" :class="statusStyles[props.applicant.status]">
                            {{ statusLabels[props.applicant.status] }}
                        </span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                        <a :href="`tel:${props.applicant.phone}`" class="flex items-center gap-1.5 hover:underline">
                            <Phone class="h-3.5 w-3.5 text-muted-foreground" /> {{ props.applicant.phone }}
                        </a>
                        <a :href="`mailto:${props.applicant.email}`" class="flex items-center gap-1.5 hover:underline">
                            <Mail class="h-3.5 w-3.5 text-muted-foreground" /> {{ props.applicant.email }}
                        </a>
                        <span v-if="props.applicant.service_area" class="flex items-center gap-1.5">
                            <MapPin class="h-3.5 w-3.5 text-muted-foreground" /> {{ props.applicant.service_area }}
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-muted-foreground">Applied {{ formatDate(props.applicant.created_at) }}</p>
                </div>

                <div class="rounded-xl border p-5">
                    <h2 class="mb-3 font-semibold">Overview</h2>
                    <dl class="grid gap-x-6 gap-y-2 text-sm sm:grid-cols-2">
                        <div class="flex justify-between gap-4 border-b py-1.5 sm:border-b-0">
                            <dt class="text-muted-foreground">Experience</dt>
                            <dd>{{ experienceLabels[props.applicant.years_experience] ?? props.applicant.years_experience }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b py-1.5 sm:border-b-0">
                            <dt class="text-muted-foreground">Lead time</dt>
                            <dd>{{ leadTimeLabels[props.applicant.lead_time] ?? props.applicant.lead_time }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-1.5">
                            <dt class="text-muted-foreground">Crew</dt>
                            <dd>{{ crewLabels[props.applicant.crew_size] ?? props.applicant.crew_size }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4 grid gap-1.5 sm:grid-cols-2">
                        <div v-for="row in boolRows" :key="row.label" class="flex items-center gap-2 text-sm">
                            <CheckCircle2 v-if="row.value" class="h-4 w-4 shrink-0 text-emerald-600" />
                            <XCircle v-else class="h-4 w-4 shrink-0 text-red-500/60" />
                            <span :class="{ 'text-muted-foreground': !row.value }">{{ row.label }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border p-5">
                    <h2 class="mb-3 font-semibold">Pricing (move-out clean)</h2>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div v-for="(price, label) in { '1BR / 1BA': props.applicant.price_1br, '2BR / 1BA': props.applicant.price_2br, '3BR / 2BA': props.applicant.price_3br }" :key="label" class="rounded-lg bg-muted/50 p-3">
                            <p class="text-lg font-semibold">{{ formatMoney(price) }}</p>
                            <p class="text-xs text-muted-foreground">{{ label }}</p>
                        </div>
                    </div>
                    <p v-if="props.applicant.pricing_notes" class="mt-3 text-sm whitespace-pre-line text-muted-foreground">{{ props.applicant.pricing_notes }}</p>
                </div>

                <div v-if="props.applicant.services?.length" class="rounded-xl border p-5">
                    <h2 class="mb-3 font-semibold">Services offered</h2>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="s in props.applicant.services" :key="s" class="rounded-full bg-muted px-3 py-1 text-xs">{{ s }}</span>
                    </div>
                </div>

                <div v-if="props.applicant.experience_details" class="rounded-xl border p-5">
                    <h2 class="mb-2 font-semibold">Roughest unit they've cleaned</h2>
                    <p class="text-sm whitespace-pre-line text-muted-foreground">{{ props.applicant.experience_details }}</p>
                </div>

                <div v-if="props.applicant.references?.length" class="rounded-xl border p-5">
                    <h2 class="mb-3 font-semibold">References</h2>
                    <div class="space-y-2">
                        <div v-for="(ref, i) in props.applicant.references" :key="i" class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-muted/50 px-4 py-2.5 text-sm">
                            <span class="font-medium">{{ ref.name || '—' }}</span>
                            <span class="text-muted-foreground">{{ ref.relationship || '—' }}</span>
                            <a v-if="ref.phone" :href="`tel:${ref.phone}`" class="hover:underline">{{ ref.phone }}</a>
                        </div>
                    </div>
                </div>

                <div v-if="props.applicant.additional_notes" class="rounded-xl border p-5">
                    <h2 class="mb-2 font-semibold">Additional notes from applicant</h2>
                    <p class="text-sm whitespace-pre-line text-muted-foreground">{{ props.applicant.additional_notes }}</p>
                </div>
            </div>

            <!-- Review panel -->
            <div class="space-y-5 lg:sticky lg:top-4 lg:self-start">
                <div class="rounded-xl border p-5">
                    <h2 class="mb-3 font-semibold">Status</h2>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            v-for="s in statusOptions"
                            :key="s"
                            type="button"
                            class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                            :class="form.status === s ? 'border-primary bg-primary text-primary-foreground' : 'hover:bg-muted'"
                            @click="form.status = s"
                        >
                            {{ statusLabels[s] }}
                        </button>
                    </div>
                    <div v-if="form.status === 'interviewing'" class="mt-3">
                        <label class="mb-1.5 block text-xs font-medium text-muted-foreground" for="interview_at">Interview date & time</label>
                        <input id="interview_at" v-model="form.interview_at" type="datetime-local" class="w-full rounded-lg border bg-background px-3 py-2 text-sm" />
                    </div>
                </div>

                <div class="rounded-xl border p-5">
                    <div class="mb-3 flex items-baseline justify-between">
                        <h2 class="font-semibold">Interview scorecard</h2>
                        <span v-if="liveTotal !== null" class="text-sm font-semibold">{{ liveTotal }}<span class="text-xs font-normal text-muted-foreground">/30</span></span>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(label, key) in props.criteria" :key="key">
                            <p class="mb-1.5 text-sm">{{ label }}</p>
                            <div class="flex gap-1.5">
                                <button
                                    v-for="n in 5"
                                    :key="n"
                                    type="button"
                                    class="h-8 w-8 rounded-md border text-sm font-medium transition-colors"
                                    :class="form.scores[key] === n ? 'border-primary bg-primary text-primary-foreground' : 'hover:bg-muted'"
                                    @click="setScore(key, n)"
                                >
                                    {{ n }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border p-5">
                    <h2 class="mb-2 font-semibold">Notes</h2>
                    <textarea
                        v-model="form.admin_notes"
                        rows="5"
                        class="w-full resize-y rounded-lg border bg-background px-3 py-2 text-sm"
                        placeholder="Interview notes, reference check results, gut feel…"
                    />
                </div>

                <div class="flex items-center justify-between gap-3">
                    <Button :disabled="form.processing" @click="save">
                        {{ form.processing ? 'Saving…' : form.recentlySuccessful ? 'Saved ✓' : 'Save review' }}
                    </Button>
                    <button type="button" class="flex items-center gap-1.5 text-sm text-red-600 hover:underline" @click="destroy">
                        <Trash2 class="h-3.5 w-3.5" /> Delete
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
