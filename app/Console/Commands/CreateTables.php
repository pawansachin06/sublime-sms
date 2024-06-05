<?php

namespace App\Console\Commands;

use App\Enums\ModelStatusEnum;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Profile;
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
                $table->bigInteger('uid')->unsigned()->autoIncrement();
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


        $this->newLine();
        $this->info('Dynamic table creation complete');
    }
}
