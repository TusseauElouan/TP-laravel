<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Bouncer::role()->where('name', 'admin')->exists()) {
            Bouncer::role()->create([
                'name' => 'admin',
                'title' => 'Administrator'
            ]);
        }

        Bouncer::allow('admin')->to('user-create');
        Bouncer::allow('admin')->to('user-update');
        Bouncer::allow('admin')->to('user-retrieve');
        Bouncer::allow('admin')->to('user-delete');

        Bouncer::allow('admin')->to('motif-create');
        Bouncer::allow('admin')->to('motif-update');
        Bouncer::allow('admin')->to('motif-retrieve');
        Bouncer::allow('admin')->to('motif-delete');

        Bouncer::allow('admin')->to('absence-create');
        Bouncer::allow('admin')->to('absence-update');
        Bouncer::allow('admin')->to('absence-retrieve');
        Bouncer::allow('admin')->to('absence-delete');

        $user = User::create([
            'nom' => 'admin',
            'prenom' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'isAdmin' => true
        ]);

        $user->assign('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();

        if ($user) {
            Bouncer::retract('admin')->from($user);

            $user->delete();
        }

        Bouncer::disallow('admin')->to('user-create');
        Bouncer::disallow('admin')->to('user-update');
        Bouncer::disallow('admin')->to('user-retrieve');
        Bouncer::disallow('admin')->to('user-delete');

        Bouncer::disallow('admin')->to('motif-create');
        Bouncer::disallow('admin')->to('motif-update');
        Bouncer::disallow('admin')->to('motif-retrieve');
        Bouncer::disallow('admin')->to('motif-delete');

        Bouncer::disallow('admin')->to('absence-create');
        Bouncer::disallow('admin')->to('absence-update');
        Bouncer::disallow('admin')->to('absence-retrieve');
        Bouncer::disallow('admin')->to('absence-delete');

        Bouncer::role()->where('name', 'admin')->delete();
    }
};
