<?php

namespace App\Console\Commands;

use App\Mail\ServerFMDown;
use App\Models\Database;
use GearboxSolutions\EloquentFileMaker\Database\Query\Grammars\FMGrammar;
use GearboxSolutions\EloquentFileMaker\Support\Facades\FM;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Output\StreamOutput;

class MonitorDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:dbs {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor FM DB Server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        if ($id != null) {
            $db = Database::find($id);
            Http::globalOptions([
                //'verify' => false,
                'timeout' => 30,
            ]);

            $conn = uniqid("fms_");

            Config::set("database.connections.$conn", Config::get("database.connections.fms"));

            Config::set("database.connections.$conn.host", $db->host);
            Config::set("database.connections.$conn.database", $db->database);
            Config::set("database.connections.$conn.username", $db->username);
            Config::set("database.connections.$conn.password", $db->password); // */
            $this->newLine();

            $this->info('Checking connection to database: ' . FM::connection($conn)->getDatabaseName());
            try {
                $tmp = FM::connection($conn)
                    ->table($db->layout ?? 'default')
                    ->first();
                FM::disconnect();

                $this->info('Connected to database: ' . $db->name);
            } catch (\Exception $e) {
                $this->warn('Failed to connect to database: ' . $db->name . ' - Error: ' . $e->getMessage()) . " | Type: " . get_class($e);

                if ($db->layout != "" && !str_contains($e, "Layout is missing")) {
                    Mail::to(config('mail.to'))->send(new ServerFMDown($db, $e->getMessage()));

                    $db->last_check_at = now();
                    $db->save();
                    $this->error($e->getMessage());
                } else {
                    $this->warn('Layout not found. Error: ' . $e->getMessage());
                }
            }
        } else {
            $dbs = Database::toCheck()->get();

            foreach ($dbs as $db) {
                Artisan::call("monitor:dbs", ["id" => $db->id], new StreamOutput(fopen('php://stdout', 'w+')));
            }
        }

        FM::clearResolvedInstances();

        $this->newLine();
    }
}
