<?php

namespace App\Console\Commands;

use App\Config;
use App\ReservedTimeDates;
use Illuminate\Console\Command;
use Morilog\Jalali\Jalalian;

class DayCreateTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'times:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create each day times';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

//        var_dump(Jalalian::forge('today')->format('%A'));
        $configs = Config::all();
        foreach($configs as $config) {
//        $reservedTimeDate = ReservedTimeDates::where('date' , '=', $data['date'])->first();
            for ($day = 0; $day < $config->open_days; $day++) {
                $temp = explode(' ', jdate()->addDays($day));
                $data['date'] = $temp[0]; // date

                $today = Jalalian::forge('today')->addDays($day)->format('%A');
                $todayDB = $this->getDay($today);

                $reservedTimeDate = ReservedTimeDates::where('date', '=', $data['date'])->first();
                if (!$reservedTimeDate) {
                    if ($config->closed_days == 0) {
                        if ($config->$todayDB == Config::OPEN) {
                            for ($i = 1; $i < 23; $i++) {
                                $t = 'h' . $i;
                                $g = 'h' . $i . '_gender';
                                $data[$t] = $config->$t;
                                $data[$g] = $config->$g;
                            }
                            $time = ReservedTimeDates::create($data);
                        }
                    } else {
                        $config->closed_days = $config->closed_days - 1;
                        $config->save();
                    }

                }
            }
        }
    }
    public function getDay($day){
        switch ($day){
            case 'شنبه':
                return 'd1';
            case 'یکشنبه':
                return 'd2';
            case 'دوشنبه':
                return 'd3';
            case 'سه شنبه':
                return 'd4';
            case 'چهارشنبه':
                return 'd5';
            case 'پنجشنبه':
                return 'd6';
            case 'جمعه':
                return 'd7';
        }
    }
}
