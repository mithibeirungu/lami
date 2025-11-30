<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminInvitation;
use Illuminate\Support\Carbon;

class MakeAdminInvite extends Command
{
    protected $signature = 'make:admin-invite {email?} {--expires= : Minutes until expiration}';
    protected $description = 'Generate an admin invite code';

    public function handle()
    {
        $email = $this->argument('email');
        $expires = $this->option('expires');

        $expiresAt = null;
        if ($expires) {
            $expiresAt = Carbon::now()->addMinutes((int)$expires);
        }

        $invite = AdminInvitation::generate($email, $expiresAt);

        $this->info('Admin invite created:');
        $this->line('Code: ' . $invite->code);
        if ($invite->email) $this->line('Email: ' . $invite->email);
        if ($invite->expires_at) $this->line('Expires At: ' . $invite->expires_at);

        return 0;
    }
}
