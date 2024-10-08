<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

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

        $user = User::create(['nom'=> 'admin','prenom'=> 'admin', 'email' => 'admin@gmail.com', 'password' => 'password', 'isAdmin'=> true]);
        Bouncer::assign('admin')->to($user);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Find the admin user and remove the role and permissions
        $user = User::where('email', 'admin@gmail.com')->first();

        if ($user) {
            // Unassign the admin role from the user
            Bouncer::retract('admin')->from($user);

            // Delete the user
            $user->delete();
        }

        // Remove all permissions assigned to the 'admin' role
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

        // Optionally, you could delete the role if it's not used elsewhere
        Bouncer::role()->where('name', 'admin')->delete();
    }
};

