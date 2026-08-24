export interface ApplicantReference {
    name: string;
    phone: string;
    relationship: string;
}

export interface Applicant {
    id: number;
    name: string;
    email: string;
    phone: string;
    business_name: string | null;
    service_area: string | null;
    years_experience: string;
    has_turnover_experience: boolean;
    experience_details: string | null;
    services: string[] | null;
    lead_time: string;
    crew_size: string;
    has_backup: boolean;
    weekend_availability: boolean;
    is_insured: boolean;
    is_bonded: boolean;
    provides_invoices: boolean;
    price_1br: string | null;
    price_2br: string | null;
    price_3br: string | null;
    pricing_notes: string | null;
    reclean_guarantee: boolean;
    sends_photos: boolean;
    references: ApplicantReference[] | null;
    additional_notes: string | null;
    status: ApplicantStatus;
    scores: Record<string, number> | null;
    score_total: number | null;
    admin_notes: string | null;
    interview_at: string | null;
    created_at: string;
    updated_at: string;
}

export type ApplicantStatus = 'new' | 'interviewing' | 'hired' | 'rejected';

export const statusLabels: Record<ApplicantStatus, string> = {
    new: 'New',
    interviewing: 'Interviewing',
    hired: 'Hired',
    rejected: 'Rejected',
};

export const statusStyles: Record<ApplicantStatus, string> = {
    new: 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
    interviewing: 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
    hired: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
    rejected: 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
};

export const experienceLabels: Record<string, string> = {
    lt1: '< 1 year',
    '1-3': '1–3 years',
    '3-5': '3–5 years',
    '5plus': '5+ years',
};

export const leadTimeLabels: Record<string, string> = {
    same_day: 'Same day',
    '1_2_days': '1–2 days',
    '3_5_days': '3–5 days',
    '1_week_plus': 'A week or more',
};

export const crewLabels: Record<string, string> = {
    solo: 'Solo',
    '2_3': 'Crew of 2–3',
    '4_plus': 'Crew of 4+',
};

export function formatDate(iso: string | null): string {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

export function formatMoney(value: string | null): string {
    if (value === null || value === '') return '—';
    return `$${Number(value).toFixed(0)}`;
}
