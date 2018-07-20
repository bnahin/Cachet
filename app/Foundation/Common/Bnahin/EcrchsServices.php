<?php
/**
 * ECRCHS Services Wrapper
 * @author Blake Nahin <blake@zseartcc.org>
 */

namespace CachetHQ\Cachet\Foundation\Common\Bnahin;


use CachetHQ\Cachet\Mail\ServiceStatusChange;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\EcrchsService;
use CachetHQ\Cachet\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as GuzzleHTTP;

class EcrchsServices extends Command
{
    public static $serviceFileLocation = 'ecrchs/services.json';
    public static $serviceFileDisk = 'local';

    public function getServices()
    {
        try {
            $services = json_decode(
                Storage::disk(self::$serviceFileDisk)->get(self::$serviceFileLocation),
                true);
        } catch (FileNotFoundException $e) {
            report($e);

            return false;
        }

        return $services;
    }

    /**
     * Update services table from JSON
     *
     * @param null|\CachetHQ\Cachet\Console\Commands\CheckIPsCommand $command $command
     *
     * @return bool
     */
    public function updateServicesTable($command = null)
    {
        if ($command) {
            $command->line('Updating services table...');
        }

        EcrchsService::truncate();

        $services = $this->getServices();
        foreach ($services as $name => $target) {
            $component = Component::where('name', $name)->firstOrFail();
            $service = new EcrchsService(
                [
                    'service_name' => $name,
                    'target'       => $target
                ]
            );
            $component->service()->save($service);

            if ($this->checkUp($service)) {
                $service->uptime = Carbon::now();
                $service->save();
            } else {
                $service->downtime = Carbon::now();
                $service->save();
            }
        }

        if ($command) {
            $command->info("Services table updated. \n");
        }

        return true;
    }

    /**
     * Check status of all services
     *
     * @param null|\CachetHQ\Cachet\Console\Commands\CheckIPsCommand $command
     *
     * @return mixed
     */
    public function checkAllServices($command = null)
    {
        $services = EcrchsService::with('component')->get();

        if ($command) {
            $command->line("Checking services...");
        }

        foreach ($services as $service) {
            $status = $this->checkUp($service);

            if ($status) {
                //Service is up
                if ($command) {
                    $command->info("{$service->service_name}: Up");
                }
                if ($service->downtime) {
                    //Was previously down
                    $prevTime = $service->downtime;
                    #$service->downtime = null;
                    #$service->uptime = Carbon::now();

                    $service->component->update(['status' => 1]);
                    $service->saveOrFail();

                    //Send Emails
                    Mail::to(User::find(1))->send(
                        new ServiceStatusChange([
                            'name'        => $service->service_name,
                            'statusColor' => "green",
                            'status'      => 'up',
                            'prevTime'    => new Carbon($prevTime),
                        ]));
                } else {
                    //Still up, do nothing
                }
            } else {
                //Service is down
                if ($command) {
                    $command->info("{$service->service_name}: Down");
                }
                if ($service->uptime) {
                    //Was previously up
                    $prevTime = $service->uptime;
                    $service->uptime = null;
                    $service->downtime = Carbon::now();

                    $service->component->update(['status' => 2]);
                    $service->saveOrFail();

                    //Send Emails
                    Mail::to(User::all())->send(
                        new ServiceStatusChange([
                            'name'        => $service->service_name,
                            'statusColor' => "red",
                            'status'      => "down",
                            'prevTime'    => new Carbon($prevTime),
                        ]));
                } else {
                    //Still down, determine status level
                    $diff = $service->downtime->diffInMinutes(Carbon::now());
                    if ($diff < 3) {
                        //Maintain status of 2 (Performance Issue)
                    } else {
                        if ($diff >= 3 && $diff < 10) {
                            //Escalate to 3 (Partial Outage)
                            $service->component->update(['status' => 3]);
                        } else {
                            if ($diff >= 10) {
                                //Escalate to Major Outage
                                //TODO: Send Email (still down!)
                                $service->component->update(['status' => 4]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Check service status
     *
     * @param $service
     *
     * @return bool
     */
    public
    function checkUp(
        $service
    ) {
        $target = $service->target;
        if (!filter_var($target, FILTER_VALIDATE_URL)) {
            //IP Address
            $status = $this->ping($target);
        } else {
            //URL
            $status = $this->checkUrlUp($target);
        }

        return $status;

    }

    /**
     * Ping IP using system command
     *
     * @param $ip
     *
     * @return bool
     */
    private
    function ping(
        $ip
    ) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $option = 'n';
        } else {
            $option = 'c';
        }
        exec("ping -$option 3 $ip", $output, $status);
        if (!$status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check URL status with GET request
     *
     * @param $url
     *
     * @return bool
     */
    private
    function checkUrlUp(
        $url
    ) {
        $guzzle = new GuzzleHTTP();
        $result = $guzzle->get($url);
        if ($result->getStatusCode() == 200) {
            return true;
        } else {
            return false;
        }
    }

    private function sendEmails()
    {

    }
}