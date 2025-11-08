<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckOrganizerData extends Command
{
    protected $signature = 'organizer:check';
    protected $description = 'Check organizer verification data';

    public function handle()
    {
        $users = User::where('is_organizer', true)->get();
        
        foreach ($users as $user) {
            $this->info("User: {$user->name} ({$user->email})");
            $this->info("Organizer Status: {$user->organizer_status}");
            $this->info("Verification Data: " . json_encode($user->organizer_verification_data, JSON_PRETTY_PRINT));
            $this->line("------------------------");
        }
        
        return Command::SUCCESS;
    }
}