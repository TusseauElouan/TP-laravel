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

        $user = User::where('email', 'tusseauelouan@gmail.com');
        Bouncer::assign('admin')->to($user);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
