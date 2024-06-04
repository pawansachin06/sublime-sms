<?php

namespace App\Console\Commands;

use App\Enums\ModelStatusEnum;
use App\Models\Contact;
use App\Models\ContactGroup;
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

        $table_name = app(ContactGroup::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->bigInteger('uid')->unsigned()->autoIncrement();
                $table->string('name');
                $table->uuid('user_id')->nullable();
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('meta')->nullable();
                $table->timestamps();
            });
            $this->info($table_name .' table created');
        } else {
            $this->error(' '. $table_name .' table already exist ');
        }


        $table_name = app(Contact::class)->getTable();
        if(!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->uuid('id')->unique()->primary;
                $table->uuid('contact_group_id')->nullable();
                $table->string('name');
                $table->string('lastname')->nullable();
                $table->string('phone');
                $table->string('country');
                $table->string('status')->default(ModelStatusEnum::DRAFT);
                $table->text('meta')->nullable();
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
