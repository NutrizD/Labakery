<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending to specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email sending to: {$email}");
        
        try {
            Mail::raw('Test email dari Toko Kue - ' . now(), function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Toko Kue');
            });
            
            $this->info('Email test berhasil dikirim!');
        } catch (\Exception $e) {
            $this->error('Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
