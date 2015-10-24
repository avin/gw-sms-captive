<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SmsRu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'smsRu'; }
}