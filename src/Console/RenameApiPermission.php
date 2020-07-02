<?php

namespace LarfreePermission\Console;

use Illuminate\Console\Command;
use LarfreePermission\Models\Permission\PermissionPermissions;
use Matrix\Exception;

class RenameApiPermission extends Command
{
    protected $signature = 'larfree-permission:api-rename {search} {rename}';

    /**
     * The console command description.
     *
     * @var string
     **/
    protected $description = '批量重命名api分组的问题';

    /**
     * Create a new command instance.
     *
     * @return void
     **/
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $search = $arguments['search'];
        $name = $arguments['rename'];
        $permissions = PermissionPermissions::query()->where('comment', 'like', $search.' :%')->get();
        foreach ($permissions as $permission) {
            $rename = $name.substr($permission->comment, strlen($search));
            $permission->comment = $rename;
            $permission->save();
        }
        echo 'update '.count($permissions).' success!';
    }

}