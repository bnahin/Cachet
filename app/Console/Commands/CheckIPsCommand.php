<?php
/**
 * Check up/down of services via IP addresses for ECR
 * @author Blake Nahin <bnahin@live.com>
 */

namespace CachetHQ\Cachet\Console\Commands;

use Illuminate\Console\Command;

class CheckIPsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecrchs:ipcheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of ECRCHS services.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** Ping IPs */
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $option = 'n';
        } else {
            $option = 'c';
        }
        //
        $ip = '1.1.1.1';
        exec("ping -$option 3 $ip", $output, $status);
        if (!$status) {
            $this->line('Online');
        } else {
            $this->line('Offline');
        }
    }
}
