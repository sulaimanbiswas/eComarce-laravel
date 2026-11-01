<?php

namespace App\Console\Commands;

use Database\Seeders\InitialSetupSeeder;
use Illuminate\Console\Command;
use LogicException;

class SetupLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:local {--reset-passwords : Reset passwords only for the predefined local demo email addresses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed local accounts, storefront defaults, sample catalog, navigation, and accounting defaults';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            InitialSetupSeeder::ensureLocalEnvironment();
        } catch (LogicException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $previousResetValue = config('local_setup.reset_passwords', false);
        config(['local_setup.reset_passwords' => (bool) $this->option('reset-passwords')]);

        try {
            return $this->call('db:seed', ['--class' => InitialSetupSeeder::class, '--no-interaction' => true]);
        } finally {
            config(['local_setup.reset_passwords' => $previousResetValue]);
        }
    }
}
