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
        Schema::create('user_pds_declarations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // 34. related to appointing/recommending authority
            $table->boolean('related_third_degree')->nullable();
            $table->string('related_third_degree_details', 255)->nullable();
            $table->boolean('related_fourth_degree')->nullable();
            $table->string('related_fourth_degree_details', 255)->nullable();

            // 35. administrative / criminal
            $table->boolean('admin_offense_found_guilty')->nullable();
            $table->string('admin_offense_details', 255)->nullable();
            $table->boolean('criminally_charged')->nullable();
            $table->string('criminal_charge_details', 255)->nullable();
            $table->date('criminal_charge_date_filed')->nullable();
            $table->string('criminal_charge_case_status', 255)->nullable();

            // 36-39
            $table->boolean('convicted_crime')->nullable();
            $table->string('convicted_crime_details', 255)->nullable();
            $table->boolean('separated_from_service')->nullable();
            $table->string('separated_from_service_details', 255)->nullable();
            $table->boolean('election_candidate')->nullable();
            $table->string('election_candidate_details', 255)->nullable();
            $table->boolean('resigned_to_campaign')->nullable();
            $table->string('resigned_to_campaign_details', 255)->nullable();
            $table->boolean('immigrant_status')->nullable();
            $table->string('immigrant_status_country', 100)->nullable();

            // 40. RA 8371 / RA 7277 / RA 11861
            $table->boolean('indigenous_group_member')->nullable();
            $table->string('indigenous_group_details', 255)->nullable();
            $table->boolean('is_pwd')->nullable();
            $table->string('pwd_id_number', 100)->nullable();
            $table->boolean('is_solo_parent')->nullable();
            $table->string('solo_parent_id_number', 100)->nullable();

            // Government ID + declaration
            $table->string('government_id_type', 100)->nullable();
            $table->string('government_id_number', 100)->nullable();
            $table->string('government_id_issued_at', 255)->nullable();
            $table->date('declared_at')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->string('thumbmark_path', 255)->nullable();

            $table->unsignedInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pds_declarations');
    }
};
