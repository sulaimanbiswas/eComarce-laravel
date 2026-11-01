<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Investor;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LocalAccountsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InitialSetupSeeder::ensureLocalEnvironment();
        $password = Hash::make(InitialSetupSeeder::PASSWORD);

        foreach (
            [
                ['name' => 'Admin', 'email' => 'admin@example.com', 'role_id' => Admin::ADMIN],
                ['name' => 'Manager', 'email' => 'manager@example.com', 'role_id' => Admin::MANAGER],
                ['name' => 'Salesman', 'email' => 'salesman@example.com', 'role_id' => Admin::SALESMAN],
                ['name' => 'Uploader', 'email' => 'uploader@example.com', 'role_id' => Admin::UPLOADER],
            ] as $staff
        ) {
            $admin = Admin::query()->firstOrCreate(['email' => $staff['email']], [
                'name' => $staff['name'],
                'role_id' => $staff['role_id'],
                'password' => $password,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $this->resetDemoPassword($admin, $password);
        }

        foreach (
            [
                ['name' => 'Customer', 'email' => 'customer@example.com', 'phone_number' => '+8801700000101', 'shop_name' => null, 'is_verified' => false],
                ['name' => 'Reseller', 'email' => 'reseller@example.com', 'phone_number' => '+8801700000102', 'shop_name' => 'Demo Shop', 'is_verified' => true],
                ['name' => 'Pending Reseller', 'email' => 'pending@example.com', 'phone_number' => '+8801700000103', 'shop_name' => 'Pending Demo Shop', 'is_verified' => false],
            ] as $account
        ) {
            $user = User::query()->firstOrCreate(['email' => $account['email']], [
                'name' => $account['name'],
                'phone_number' => $account['phone_number'],
                'bkash_number' => $account['phone_number'],
                'shop_name' => $account['shop_name'],
                'is_verified' => $account['is_verified'],
                'address' => 'Dhaka, Bangladesh',
                'password' => $password,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->resetDemoPassword($user, $password);

            $affiliateService = app(AffiliateService::class);
            $affiliateService->ensureAffiliateProfile($user, '');
            if ($user->wasRecentlyCreated && $user->is_verified) {
                $affiliateService->processVerification($user);
            }
        }

        $investor = Investor::query()->where('email', 'investor@example.com')->first();
        if (! $investor) {
            $investor = Investor::query()->create([
                'name' => 'Investor',
                'email' => 'investor@example.com',
                'phone_number' => '+8801700000104',
                'bkash_number' => '+8801700000104',
                'address' => 'Dhaka, Bangladesh',
                'password' => $password,
                'referral_code' => Investor::generateReferralCode(),
                'is_active' => true,
            ]);
        }
        $this->resetDemoPassword($investor, $password);
    }

    private function resetDemoPassword(Model $account, string $password): void
    {
        if (config('local_setup.reset_passwords', false) && ! $account->wasRecentlyCreated) {
            $account->forceFill(['password' => $password])->saveQuietly();
        }
    }
}
