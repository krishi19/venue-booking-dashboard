<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\TestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
protected $admin;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin=$admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->admin->notify(new TestNotification());

    }
}
