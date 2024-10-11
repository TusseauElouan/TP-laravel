<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\Motif;
use App\Models\User;
use Tests\TestCase;

class MotifControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_motif_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('motif.index'));

        $response->assertStatus(200);
    }

    public function test_motif_create()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('motif.create'));

        $response->assertOk();
    }

    public function test_motif_store()
    {

        $user = User::factory()->create();
        $motif = Motif::factory()->create();
        $response = $this->actingAs($user)->post(route('motif.store'), ['libelle' => $motif->libelle, 'is_accessible_salarie' => $motif->is_accessible_salarie]);

        $response->assertStatus(302);
    }

    public function test_motif_show()
    {
        $user = User::factory()->create();
        $motif = Motif::factory()->create();
        $response = $this->actingAs($user)->get(route('motif.show', $motif));

        $response->assertStatus(302);
    }

    public function test_motif_edit()
    {
        $user = User::factory()->create();
        $motif = Motif::factory()->create();

        $response = $this->actingAs($user)->get(route('motif.edit', $motif));
        $response->assertOk();
    }

    public function test_motif_update()
    {
        $user = User::factory()->create();
        $motif = Motif::factory()->create();
        $response = $this->actingAs($user)->put(route('motif.update', $motif), ['libelle' => $motif->libelle, 'is_accessible_salarie' => $motif->is_accessible_salarie]);

        $response->assertStatus(302);
    }

    public function test_motif_destroy_no_absence_link()
    {

        $user = User::factory()->create();
        $motif = Motif::factory()->create();
        $motif->save();
        $response = $this->actingAs($user)->delete(route('motif.destroy', $motif), ['libelle' => $motif->libelle, 'is_accessible_salarie' => $motif->is_accessible_salarie]);

        $response->assertStatus(302)->assertSessionHas('success', 'Motif supprimé.');
    }

    public function test_motif_destroy_with_absence_link()
    {
        $user = User::factory()->create();
        $motif = Motif::factory()->create();
        Absence::factory()->create(['motif_id' => $motif->id]);
        $response = $this->actingAs($user)->delete(route('motif.destroy', $motif), ['libelle' => $motif->libelle, 'is_accessible_salarie' => $motif->is_accessible_salarie]);

        $response->assertStatus(302)->assertSessionHas('error', 'Ce motif est utilisé dans 1 absence(s).');
    }
}
