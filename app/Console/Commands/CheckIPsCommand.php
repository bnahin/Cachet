<?php
/**
 * Check up/down of services via IP addresses for ECR
 * @author Blake Nahin <bnahin@live.com>
 */

namespace CachetHQ\Cachet\Console\Commands;

use CachetHQ\Cachet\Foundation\Common\Bnahin\EcrchsServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckIPsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecrchs:ipcheck 
    {--r|refresh : Refresh services table from the JSON file.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of ECRCHS services.';

    /*
     * The ECRCHS Services instance.
     * @var \CachetHQ\Cachet\Foundation\Common\Bnahin\EcrchsServices
     */
    protected $ecrchs;

    /**
     * Create a new command instance.
     *
     * @param \CachetHQ\Cachet\Foundation\Common\Bnahin\EcrchsServices $services
     */

    public function __construct(EcrchsServices $services)
    {
        parent::__construct();
        $this->ecrchs = $services;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("");
        if ($this->option('refresh')) {
            $this->ecrchs->updateServicesTable($this);
        }

        return $this->ecrchs->checkAllServices($this);
    }
}
