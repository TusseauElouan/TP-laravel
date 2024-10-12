<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\Motif;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AbsenceControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function create_user_admin()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $user->isAdmin = true;
        $user->save();

        return $user;
    }

    public function getDate()
    {
        $startDate = Carbon::now()->addDay();
        $endDate = $startDate->copy()->addDays(rand(1, 14));

        return [$startDate->toDateString(), $endDate->toDateString()];
    }

    public function getAbsence(bool $isValidated, bool $is_deleted)
    {
        $motif = Motif::factory()->create();
        $anotherUser = User::factory()->create();
        $date = $this->getDate();

        $absence = new Absence;
        $absence->motif_id = $motif->id;
        $absence->user_id_salarie = $anotherUser->id;
        $absence->date_absence_debut = $date[0];
        $absence->date_absence_fin = $date[1];
        $absence->isValidated = $isValidated;
        $absence->is_deleted = $is_deleted;
        $absence->save();

        return $absence;
    }

    public function test_absence_log_out_Test(): void
    {
        $response = $this->get(route('absence.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_absence_log_in_Test(): void
    {
        $user = $this->create_user_admin();
        $response = $this
            ->actingAs($user)
            ->get(route('absence.index'));
        $response->assertStatus(200);
    }

    public function test_create_absence_log_out_Test(): void
    {
        $response = $this->get(route('absence.create'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', '');
    }

    public function test_create_absence_log_in_Test(): void
    {
        $user = $this->create_user_admin();
        $response = $this
            ->actingAs($user)
            ->get(route('absence.create'));
        $response->assertStatus(200);
    }

    public function test_can_store_absence()
    {
        $user = $this->create_user_admin();
        $motif = Motif::factory()->create();
        $anotherUser = User::factory()->create();
        $date = $this->getDate();

        $response = $this
            ->actingAs($user)
            ->post(route('absence.store'), [
                'user_id_salarie' => $anotherUser->id,
                'motif_id' => $motif->id,
                'date_absence_debut' => $date[0],
                'date_absence_fin' => $date[1],
                'is_deleted' => false,
            ]);
        $response->assertRedirect(route('absence.index'));
    }

    public function test_edit_log_out_Test()
    {
        $absence = Absence::factory()->create();
        $response = $this->get(route('absence.edit', $absence->id));

        $response->assertRedirect(route('login'));
    }

    public function test_edit_log_in_Test()
    {
        $absence = Absence::factory()->create();
        $user = $this->create_user_admin();
        $motif = Motif::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('absence.edit', $absence->id));

        $response->assertStatus(200);
    }

    public function test_edit_log_in_isValidated()
    {
        $absence = $this->getAbsence(true, false);

        $user = $this->create_user_admin();
        $response = $this
            ->actingAs($user)
            ->get(route('absence.edit', $absence->id));

        $response->assertStatus(302);
    }

    public function test_update_absence()
    {
        $user = $this->create_user_admin();
        $motif = Motif::factory()->create();
        $anotherUser = User::factory()->create();
        $date = $this->getDate();
        $absence = $this->getAbsence(false, false);

        $response = $this
            ->actingAs($user)
            ->put(route('absence.update', $absence->id), [
                'motif_id' => $motif->id,
                'user_id_salarie' => $anotherUser->id,
                'date_absence_debut' => $date[0],
                'date_absence_fin' => $date[1],
                'is_deleted' => false,
                'isValidated' => false,
            ]);

        $response->assertRedirect(route('absence.index'));
    }

    public function test_update_absence_isValidated()
    {
        $user = $this->create_user_admin();
        $motif = Motif::factory()->create();
        $anotherUser = User::factory()->create();
        $date = $this->getDate();
        $absence = $this->getAbsence(true, false);

        $response = $this
            ->actingAs($user)
            ->put(route('absence.update', $absence->id), [
                'motif_id' => $motif->id,
                'user_id_salarie' => $anotherUser->id,
                'date_absence_debut' => $date[0],
                'date_absence_fin' => $date[1],
                'is_deleted' => false,
                'isValidated' => true,
            ]);

        $response->assertRedirect(route('absence.index'));
        $response->assertSessionHas('error', 'Cette absence est déjà validée.');
    }

    public function test_show()
    {
        $user = $this->create_user_admin();
        $absence = Absence::factory()->create();
        $response = $this->actingAs($user)->get(route('absence.show', $absence->id));

        $response->assertRedirect(route('absence.index'));
    }

    public function test_delete()
    {
        $user = $this->create_user_admin();
        $absence = Absence::factory()->create();
        $response = $this->actingAs($user)->delete(route('absence.destroy', $absence->id));
        $response->assertRedirect(route('absence.index'));
        $absence->refresh();
        $this->assertEquals(1, $absence->is_deleted);
    }

    public function test_validate()
    {
        $user = $this->create_user_admin();

        $absence = $this->getAbsence(false, false);
        $response = $this->actingAs($user)->post(route('absence.validate', $absence->id));

        $response->assertRedirect(route('absence.index'));
    }

    public function test_restore()
    {
        $user = $this->create_user_admin();
        $absence = $this->getAbsence(false, true);
        $response = $this->actingAs($user)->post(route('absence.restore', $absence->id));
        $response->assertRedirect(route('absence.index'));
        $response->assertSessionHas('success', 'Absence restaurée.');
    }

    public function test_validation_page()
    {
        $user = $this->create_user_admin();
        $absence = $this->getAbsence(true, false);
        $response = $this->actingAs($user)->get(route('absence.confirmValidation', $absence->id));
        $response->assertViewIs('absence.confirm');
    }
}
