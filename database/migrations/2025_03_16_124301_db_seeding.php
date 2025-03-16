<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // owners table
        DB::statement("INSERT INTO `owners` (`name`,`billing_name`, `address_country`,
                      `address_zip`, `address_city`, `address_street`, `vat_number`, `created_at`, `updated_at`)
            VALUES
            ('WebOrigo Magyarország Zrt.','WebOrigo Magyarország Zrt.', '348', '1027', 'Budapest', 'Bem József utca 9. fszt.', '28767116-2-42', NULL, NULL),
            ('Ahmed Farouk','Ahmed Farouk', '123', '1028', 'Cairo', 'omar ben khattab St', '28767116-2-41', NULL, NULL)");

        //devices
        DB::statement("INSERT INTO `devices` (`device_id`, `type`, `activation_code`, `leasing_plan_id`, `owner_id`, `registered_at`, `remember_token`, `created_at`, `updated_at`) VALUES
            ('NW-H-20-0018', 'free', NULL, NULL, NULL, NULL, 'trbBuoDNFGRMD2DexRpNRhS9GYiCSX', NULL, '2025-03-16 09:14:30'),
            ('NW-H-20-0017', 'leasing', 'XB67FGC2561XDFG2', 1, 1, '2025-03-16', 'SGYJFQcPTW1y1DEWZfv9hgVLDRV3Jo', NULL, '2025-03-16 08:21:02')");

        //activation codes
        DB::statement("INSERT INTO `activation_codes` (`code`, `leasing_plan_id`, `created_at`, `updated_at`) VALUES
            ('XB67FGC2561XDFG2', 1, NULL, NULL),
            ('XB67FGC2871XDFG2', 2, NULL, NULL),
            ('XB67FGC2411XDFG2', 3, NULL, NULL)");

        //leasing plans
        DB::statement("INSERT INTO `leasing_plans` (`maximum_trainings`, `maximum_date`, `next_check_at`, `actual_period_start_date`, `created_at`, `updated_at`) VALUES
            (100, '2025-03-31', '2025-03-31', '2025-02-04', NULL, NULL),
            (10000, '2025-03-31', '2025-03-31', '2025-02-04', NULL, NULL),
            (1000, '2024-03-31', '2024-03-31', '2024-02-04', NULL, NULL)");

        //leasing plans history
        DB::statement("INSERT INTO `leasing_plan_history` (`leasing_plan_id`, `device_id`, `created_at`, `updated_at`) VALUES
            (1, 'NW-H-20-0017', NULL, NULL),
            (2, 'NW-H-20-0017', NULL, NULL),
            (3, 'NW-H-20-0017', NULL, NULL)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
