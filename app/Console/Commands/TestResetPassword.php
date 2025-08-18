<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class TestResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:test-reset {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test password reset functionality for a specific email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing password reset for email: {$email}");
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found in database!");
            return;
        }
        
        $this->info("User found: {$user->name} ({$user->email})");
        
        try {
            $status = Password::sendResetLink(['email' => $email]);
            
            if ($status === Password::RESET_LINK_SENT) {
                $this->info("✅ Reset password link sent successfully to {$email}");
                $this->info("📧 Check the email inbox for reset password link");
            } else {
                $this->error("❌ Failed to send reset password link: " . $status);
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
