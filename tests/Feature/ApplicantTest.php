<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantTest extends TestCase
{
    use RefreshDatabase;

    private function validApplication(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Cleaner',
            'email' => 'jane@example.com',
            'phone' => '555-123-4567',
            'business_name' => 'Sparkle Co',
            'service_area' => 'Denver metro',
            'years_experience' => '3-5',
            'has_turnover_experience' => true,
            'experience_details' => 'Post-eviction unit with heavy trash-out.',
            'services' => ['Deep clean (move-out standard)', 'Trash-out / haul-away'],
            'lead_time' => '1_2_days',
            'crew_size' => '2_3',
            'has_backup' => true,
            'weekend_availability' => true,
            'is_insured' => true,
            'is_bonded' => false,
            'provides_invoices' => true,
            'price_1br' => 180,
            'price_2br' => 250,
            'price_3br' => 340,
            'pricing_notes' => null,
            'reclean_guarantee' => true,
            'sends_photos' => true,
            'references' => [
                ['name' => 'Bob Landlord', 'phone' => '555-987-6543', 'relationship' => 'Landlord'],
                ['name' => '', 'phone' => '', 'relationship' => ''],
            ],
            'additional_notes' => null,
        ], $overrides);
    }

    public function test_the_application_form_renders_for_guests(): void
    {
        $this->get('/apply')->assertOk();
    }

    public function test_home_redirects_to_the_application_form(): void
    {
        $this->get('/')->assertRedirect('/apply');
    }

    public function test_a_guest_can_submit_an_application(): void
    {
        $this->post('/apply', $this->validApplication())
            ->assertRedirect(route('apply.thanks'));

        $applicant = Applicant::sole();
        $this->assertSame('Jane Cleaner', $applicant->name);
        $this->assertSame('new', $applicant->status);
        $this->assertTrue($applicant->is_insured);
        $this->assertCount(2, $applicant->services);
        $this->assertCount(1, $applicant->references); // empty row dropped
    }

    public function test_required_fields_are_validated(): void
    {
        $this->post('/apply', [])
            ->assertSessionHasErrors(['name', 'email', 'phone', 'years_experience', 'lead_time', 'crew_size', 'is_insured']);
    }

    public function test_guests_cannot_view_the_applicants_dashboard(): void
    {
        $this->get('/applicants')->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_view_and_filter_applicants(): void
    {
        $this->post('/apply', $this->validApplication());

        $this->actingAs(User::factory()->create())
            ->get('/applicants?status=new')
            ->assertOk();
    }

    public function test_scoring_an_applicant_computes_the_total(): void
    {
        $this->post('/apply', $this->validApplication());
        $applicant = Applicant::sole();

        $this->actingAs(User::factory()->create())
            ->patch("/applicants/{$applicant->id}", [
                'status' => 'interviewing',
                'scores' => [
                    'experience' => 5,
                    'reliability' => 4,
                    'protection' => 5,
                    'pricing' => 3,
                    'quality' => 4,
                    'references' => 5,
                ],
                'admin_notes' => 'Strong candidate.',
            ])
            ->assertRedirect();

        $applicant->refresh();
        $this->assertSame(26, $applicant->score_total);
        $this->assertSame('interviewing', $applicant->status);
        $this->assertSame('Strong candidate.', $applicant->admin_notes);
    }

    public function test_an_applicant_can_be_deleted(): void
    {
        $this->post('/apply', $this->validApplication());
        $applicant = Applicant::sole();

        $this->actingAs(User::factory()->create())
            ->delete("/applicants/{$applicant->id}")
            ->assertRedirect(route('applicants.index'));

        $this->assertSame(0, Applicant::count());
    }
}
