<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('tenants:run payments:verify')->everyMinute()->withoutOverlapping();
