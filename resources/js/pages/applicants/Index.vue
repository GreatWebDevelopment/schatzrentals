<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    type Applicant,
    type ApplicantStatus,
    experienceLabels,
    formatDate,
    formatMoney,
    leadTimeLabels,
    statusLabels,
    statusStyles,
} from '@/types/applicant';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, ShieldCheck, XCircle } from 'lucide-vue-next';

const props = defineProps<{
    applicants: Applicant[];
    counts: Record<'all' | ApplicantStatus, number>;
    activeStatus: 'all' | ApplicantStatus;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Applicants', href: '/applicants' }];

const tabs: { key: 'all' | ApplicantStatus; label: string }[] = [
    { key: 'all', label: 'All' },
    { key: 'new', label: 'New' },
    { key: 'interviewing', label: 'Interviewing' },
    { key: 'hired', label: 'Hired' },
    { key: 'rejected', label: 'Rejected' },
];
</script>

<template>
    <Head title="Applicants" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-5 p-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl font-semibold">Cleaner Applicants</h1>
                    <p class="text-sm text-muted-foreground">Sorted by score, then newest first.</p>
                </div>
                <div class="flex gap-1 rounded-lg border p-1">
                    <Link
                        v-for="tab in tabs"
                        :key="tab.key"
                        :href="route('applicants.index', tab.key === 'all' ? {} : { status: tab.key })"
                        class="rounded-md px-3 py-1.5 text-sm transition-colors"
                        :class="props.activeStatus === tab.key ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted'"
                        preserve-scroll
                    >
                        {{ tab.label }}
                        <span class="ml-1 text-xs opacity-70">{{ props.counts[tab.key] }}</span>
                    </Link>
                </div>
            </div>

            <div v-if="props.applicants.length === 0" class="flex flex-1 items-center justify-center rounded-xl border border-dashed p-16">
                <div class="text-center">
                    <p class="font-medium">No applicants{{ props.activeStatus !== 'all' ? ` with status "${statusLabels[props.activeStatus]}"` : ' yet' }}.</p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Share the application link:
                        <a :href="route('apply')" target="_blank" class="underline">{{ route('apply') }}</a>
                    </p>
                </div>
            </div>

            <div v-else class="overflow-x-auto rounded-xl border">
                <table class="w-full min-w-[820px] text-sm">
                    <thead>
                        <tr class="border-b bg-muted/50 text-left text-xs text-muted-foreground uppercase">
                            <th class="px-4 py-3 font-medium">Applicant</th>
                            <th class="px-4 py-3 font-medium">Applied</th>
                            <th class="px-4 py-3 font-medium">Experience</th>
                            <th class="px-4 py-3 font-medium">Turnovers</th>
                            <th class="px-4 py-3 font-medium">Insured</th>
                            <th class="px-4 py-3 font-medium">Lead time</th>
                            <th class="px-4 py-3 font-medium">2BR rate</th>
                            <th class="px-4 py-3 font-medium">Score</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="a in props.applicants"
                            :key="a.id"
                            class="cursor-pointer border-b last:border-b-0 transition-colors hover:bg-muted/40"
                            @click="$inertia.visit(route('applicants.show', a.id))"
                        >
                            <td class="px-4 py-3">
                                <Link :href="route('applicants.show', a.id)" class="font-medium hover:underline" @click.stop>{{ a.name }}</Link>
                                <p v-if="a.business_name" class="text-xs text-muted-foreground">{{ a.business_name }}</p>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">{{ formatDate(a.created_at) }}</td>
                            <td class="px-4 py-3">{{ experienceLabels[a.years_experience] ?? a.years_experience }}</td>
                            <td class="px-4 py-3">
                                <CheckCircle2 v-if="a.has_turnover_experience" class="h-4 w-4 text-emerald-600" />
                                <XCircle v-else class="h-4 w-4 text-muted-foreground/50" />
                            </td>
                            <td class="px-4 py-3">
                                <ShieldCheck v-if="a.is_insured" class="h-4 w-4 text-emerald-600" />
                                <XCircle v-else class="h-4 w-4 text-red-500/70" />
                            </td>
                            <td class="px-4 py-3">{{ leadTimeLabels[a.lead_time] ?? a.lead_time }}</td>
                            <td class="px-4 py-3">{{ formatMoney(a.price_2br) }}</td>
                            <td class="px-4 py-3">
                                <span v-if="a.score_total !== null" class="font-semibold">{{ a.score_total }}<span class="text-xs font-normal text-muted-foreground">/30</span></span>
                                <span v-else class="text-muted-foreground">—</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium" :class="statusStyles[a.status]">
                                    {{ statusLabels[a.status] }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
