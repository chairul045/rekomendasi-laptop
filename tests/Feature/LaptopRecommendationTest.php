<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Laptop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LaptopRecommendationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_register_and_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'mahasiswa',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_can_fill_questionnaire_and_get_recommendation(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($user)->post('/questionnaire', [
            'budget_min' => 5000000,
            'budget_max' => 15000000,
            'peruntukan' => 'programming',
            'ranking_kriteria' => ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'],
            'merek_pilihan' => ['semua'],
            'kondisi_pilihan' => 'keduanya',
            'frekuensi_membawa' => 'Rutin',
            'judul' => 'Konsultasi Testing',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('kuisioner_jawaban', [
            'user_id' => $user->id,
        ]);

        $jawaban = $user->kuisionerJawaban()->latest()->first();
        $this->assertNotNull($jawaban);

        $resultResponse = $this->actingAs($user)->get("/recommendation/{$jawaban->id}");
        $resultResponse->assertStatus(200);
    }

    public function test_user_can_download_pdf(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($user)->post('/questionnaire', [
            'budget_min' => 5000000,
            'budget_max' => 15000000,
            'peruntukan' => 'programming',
            'ranking_kriteria' => ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'],
            'merek_pilihan' => ['semua'],
            'kondisi_pilihan' => 'keduanya',
            'frekuensi_membawa' => 'Rutin',
            'judul' => 'Konsultasi PDF Test',
        ]);

        $jawaban = $user->kuisionerJawaban()->latest()->first();
        $this->assertNotNull($jawaban);
        $response = $this->actingAs($user)->get("/recommendation/{$jawaban->id}/pdf");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_access_admin_dashboard_and_save_criteria(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $criteria = \App\Models\Kriteria::first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $postResponse = $this->actingAs($admin)->post("/admin/criteria/{$criteria->id}", [
            'nama' => 'Harga Baru',
            'tipe' => 'cost',
            'deskripsi' => 'Bobot kriteria harga yang diperbarui',
        ]);

        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('kriteria', [
            'id' => $criteria->id,
            'nama' => 'Harga Baru',
        ]);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}
