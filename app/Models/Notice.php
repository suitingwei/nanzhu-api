<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    const NOTICE_GROUPS = 'A,B,C,D,E';

    const TYPE_DAILY   = 10;
    const TYPE_PREPARE = 20;

    protected $table = "t_biz_noticeexcel";

    protected $fillable = ['is_delete'];

    public $timestamps = false;

    public $hidden = ['FFILEADD', 'FRECEIVEDETAIL', 'FSENDDATE', 'FORSEND'];

    public function type_desc()
    {
        if ($this->FNOTICEEXCELTYPE == 10) {
            return "每日通告单";
        }
        if ($this->FNOTICEEXCELTYPE == 20) {
            return "预备通告单";
        }
    }

    public function excels()
    {
        $data = NoticeExcel::where("FNOTICEEXCELID", $this->FID)->get();
        $arr  = array();
        if ($data->count() > 0) {
            foreach ($data as $d) {
                $arr[$d->FNUMBER] = $d->FFILEADD;
            }
        }
        return $arr;
    }

    public function excelinfos()
    {
        $data = NoticeExcel::where("FNOTICEEXCELID", $this->FID)->get();
        return $data;
    }

    public function excel_is_send($FID)
    {
        $excel_is_send = DB::table('messages')
                           ->where('notice_file_id', $FID)
                           ->where('is_undo', '<>', 1)
                           ->count();
        if ($excel_is_send > 0) {
            return true;
        }
        return false;
    }

    /**
     * 一个通告单属于一个剧组
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'FMOVIEID', 'FID');
    }

    public function isDaily()
    {
        return $this->FNOTICEEXCELTYPE == self::TYPE_DAILY;
    }

    public function isPrepare()
    {
        return $this->FNOTICEEXCELTYPE == self::TYPE_PREPARE;
    }

}

