<?php

namespace App\Console\Commands;

use App\Enums\ModelStatusEnum;
use App\Enums\UserRoleEnum;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Profile;
use App\Models\Template;
use App\Models\User;
use App\Models\Sms;
use App\Models\SmsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Event\ConsoleEvent;

class CreateTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create dynamic tables without migration files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * Create Profile Table
         * profile has parent child relationship
         */
        $table_name = app(Profile::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->string('name');
                $table->uuid('parent_id')->nullable();
                $table->uuid('author_id');
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('meta')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        /**
         * Create SMS Template table
         */
        $table_name = app(Template::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->string('name');
                $table->uuid('profile_id')->nullable();
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('message')->nullable();
                $table->text('meta')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }



        /**
         * Create Pivot Table as user can have many profiles
         */
        $table_name = Profile::$pivot_table;
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('profile_id')->constrained()->cascadeOnDelete();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        /**
         * Create Contact Group Table
         */
        $table_name = app(ContactGroup::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->string('uid')->nullable();
                $table->string('name');
                $table->uuid('user_id')->nullable();
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('meta')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        /**
         * Create Contact Table
         */
        $table_name = app(Contact::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->string('name');
                $table->string('lastname')->nullable();
                $table->string('phone');
                $table->string('company')->nullable();
                $table->string('country');
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('comments')->nullable();
                $table->text('meta')->nullable();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        /**
         * Create Pivot table as contact can be in many contact groups
         */
        $table_name = Contact::$pivot_table;
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->foreignUuid('contact_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('contact_group_id')->constrained()->cascadeOnDelete();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        $existing_user = User::where('username', 'bgc')->first(['id']);
        if(empty($existing_user)){
            $user = User::factory()->create([
                'name'=> 'BGC',
                'username'=> 'bgc',
                'role'=> UserRoleEnum::ADMIN,
                'email'=> 'bgc@example.com',
            ]);

            Profile::factory()->create([
                'name'=> 'BGCG Fixed Income Solutions',
                'author_id'=> $user->id,
                'status'=> ModelStatusEnum::PUBLISHED,
            ]);
        }

        /**
         * SMS CronJob table
         */
        $table_name = app(SmsJob::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->string('name')->nullable();
                $table->uuid('profile_id')->nullable();
                $table->dateTime('send_at')->nullable();
                $table->uuid('template_id')->nullable();
                $table->mediumText('list_uids')->nullable();
                $table->mediumText('numbers')->nullable();
                $table->text('message');

                $table->string('user_id')->nullable();
                $table->string('from')->nullable();
                $table->string('validity')->nullable();
                $table->string('replies_to_email')->nullable();

                $table->string('tracked_link_url')->nullable();
                $table->string('link_hits_callback')->nullable();
                $table->string('dlr_callback')->nullable();
                $table->string('reply_callback')->nullable();

                $table->boolean('scheduled')->default(false);
                $table->string('status')->default('DRAFT');
                $table->text('meta')->nullable();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }

        /**
         * SMS table
         */
        $table_name = app(Sms::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->id();
                $table->uuid('sms_job_id')->nullable();
                $table->string('sms_id')->nullable();
                $table->string('user_id')->nullable();
                $table->text('message');
                $table->string('to')->nullable();
                $table->string('list_id')->nullable();
                $table->string('countrycode')->nullable();
                $table->string('from')->nullable();
                $table->dateTime('send_at')->nullable();
                $table->string('validity')->nullable();
                $table->string('replies_to_email')->nullable();
                $table->string('tracked_link_url')->nullable();
                $table->string('link_hits_callback')->nullable();
                $table->string('dlr_callback')->nullable();
                $table->string('reply_callback')->nullable();
                $table->string('status')->nullable();
                $table->dateTime('delivered_at')->nullable();
                $table->string('local_status')->default('DRAFT');
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        $this->newLine();
        $this->info('Dynamic table creation complete');
    }
}
