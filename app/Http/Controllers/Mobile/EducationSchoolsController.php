<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\EducationSchoolApply;
use Illuminate\Http\Request;

class EducationSchoolsController extends BaseController
{
    public function index(Request $request)
    {
        if ($courseType = $request->input('type')) {
            switch ($courseType) {
                case 'master':
                    return view('mobile.education_schools.easy_education.master_courses');
                case 'common':
                    return view('mobile.education_schools.easy_education.common_courses');
                default :
                    return view('mobile.education_schools.easy_education.common_courses');
            }
        }
        return view('mobile.education_schools.easy_education.common_courses');
    }

    public function createJoinSchool()
    {
        return view('mobile.education_schools.easy_education.create');
    }

    public function storeJoinSchool(Request $request)
    {
        $data = $request->all();
        if (EducationSchoolApply::where($request->except('_token'))->exists()) {
            return $this->ajaxResponseFail('已经报过名');
        }

        EducationSchoolApply::create($data);
        return $this->ajaxResponseSuccess();
    }
}
