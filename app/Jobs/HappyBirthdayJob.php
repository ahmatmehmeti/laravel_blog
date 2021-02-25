<?php

namespace App\Jobs;

use App\Mail\happyBirthdayMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class happyBirthdayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereMonth('birthDate', date('m'))
            ->whereDay('birthDate', date('d'))
            ->get();

        foreach ($users as $user) {

            Mail::to($user->email)->send(new happyBirthdayMail($user));

        }

        //users that has birthday in this day to send happy Birthday Mail

    }
}
