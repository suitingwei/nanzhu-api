<?php
Route::group(['namespace' => 'Mobile', 'prefix' => 'mobile'], function () {

    Route::get("/shoot_orders/success", "ShootOrdersController@success");
    Route::resource("/shoot_orders", "ShootOrdersController");

    Route::get("/auth/login", "AuthController@login");
    Route::get("/auth", "AuthController@index");
    Route::get("/auth_callback", "AuthController@callback");


    /*
    | --------------------------------------------------------------------
    |  权限相关
    |  1. Contact  power.
    |  2. Progress power.
    |  3. Receive  power.
    |  4. Daily report power.
    |  5. Reference plan power.
    |  6. Previous prospect power.
    |  7. Groupuser feedback power.
    |  7. Transform admin's power.
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => '/permissions', 'middleware' => ['mobile.can_visit_permission']], function () {
        //权限与设置首页
        Route::get("/", "PermissionsController@index");

        //剧组通讯录相关
        Route::group(['prefix' => 'contact'], function () {
            //查看已经赋予剧组通讯录进度查看权限的人
            Route::get("/", "PermissionsController@indexContact");

            //显示添加/删除剧组通讯录权限的界面
            Route::get('/create', 'PermissionsController@createContact');

            //保存更新剧组通讯录权限名单
            Route::post('/', 'PermissionsController@storeContact');
        });

        //拍摄进度相关
        Route::group(['prefix' => 'progress'], function () {
            //查看已经赋予拍摄进度查看权限的人
            Route::get("/", "PermissionsController@indexProgress");

            //显示添加/删除拍摄进度权限的界面
            Route::get('/create', 'PermissionsController@createProgress');

            //保存更新拍摄进度权限名单
            Route::post('/', 'PermissionsController@storeProgress');
        });

        //拍摄进度相关
        Route::group(['prefix' => 'receive'], function () {
            //查看已经赋予接受详情查看权限的人
            Route::get("/", "PermissionsController@indexReceive");

            //显示添加/删除拍接受详情权限的界面
            Route::get('/create', 'PermissionsController@createReceive');

            //保存更新接受详情权限名单
            Route::post('/', 'PermissionsController@storeReceive');
        });

        //拍摄进度相关
        Route::group(['prefix' => 'transfor'], function () {
            //查看最高权限移交
            Route::get("/", "PermissionsController@indexTransfor");

            //显示添加/删除最高权限转移的界面
            Route::get('/create', 'PermissionsController@createTransfor');

            //保存更新最高权限转移名单
            Route::post('/', 'PermissionsController@storeTransfor');
        });

        //参考大计划进度相关
        Route::group(['prefix' => 'reference-plan'], function () {
            //更新参考大计划权限界面
            Route::get('/create', 'PermissionsController@createReferencePlan');

            //更新参考大计划权限
            Route::post('/', 'PermissionsController@storeReferencePlan');
        });

        //场记日报表权限相关
        Route::group(['prefix' => 'daily-report'], function () {
            //显示添加/删除拍摄进度权限的界面
            Route::get('/create', 'PermissionsController@createDailyReport');

            //保存更新拍摄进度权限名单
            Route::post('/', 'PermissionsController@storeDailyReport');
        });

        //Manage the previous prospect power.
        Route::group(['prefix' => 'previous-prospect'], function () {
            //显示添加/删除拍摄进度权限的界面
            Route::get('/create', 'PermissionsController@createPreviousProspect');
            //保存更新拍摄进度权限名单
            Route::post('/', 'PermissionsController@storePreviousProspect');
        });

        //Manage the groupuser feedback power.
        Route::group(['prefix' => 'groupuser-feedback'], function () {
            Route::get('/create', 'PermissionsController@createGroupuserFeedback');
            Route::post('/', 'PermissionsController@storeGroupuserFeedback');
            Route::post('/toggle', 'PermissionsController@toggleGroupUserFeedback');
        });
    });

    /*
    | --------------------------------------------------------------------
    |  公开电话相关
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => '/users/public_contact'], function () {
        //显示剧组的所有公开电话
        Route::get('/', 'UsersController@indexPublicContact');

        //显示添加公开电话的界面
        Route::get('/create', 'UsersController@createPublicContact');

        //保存公开电话信息
        Route::post('/', 'UsersController@storePublicContact');

        //删除公开电话(更新删除字段)
        Route::get('/delete', 'UsersController@deletePublicContact');
    });


    /*
    | --------------------------------------------------------------------
    |  部门管理相关
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => 'groups'], function () {

        //部门管理index界面
        Route::get('manage', 'GroupsController@manage');

        //部门管理,删除成员界面
        Route::get('{group}/delete', 'GroupsController@delete');

        //部门管理,微信分享界面
        Route::get('{group}/wechat_share', 'GroupsController@wechatShare');

        //将手机号添加到通讯录
        Route::get('{group}/addContact/{groupUser}', 'GroupsController@addPhoneToContact');

        //将手机号从剧组通讯录移除
        Route::get('{group}/removeContact/{groupUser}', 'GroupsController@removePhoneFromContact');

        //剔除组员
        Route::get('{group}/member/{groupUser}/delete', 'GroupsController@removeMember');

        //创建剧组选择部门
        Route::get('/templates', 'GroupsController@templates');

        //添加电影
        Route::post('/add_movie', 'GroupsController@add_movie');
    });

    //Rest
    Route::resource('/groups', 'GroupsController');

    Route::get("/result", "ResultsController@index");


    /*
    | --------------------------------------------------------------------
    |  每日数据,总数据相关
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => 'charts'], function () {
        //总数据界面
        Route::get('/all', 'ChartsController@all');

        //更新总数据
        Route::post('/update_all', 'ChartsController@update_all');

        //每日数据界面
        Route::get('/daily', 'ChartsController@daily');

        //更新每日数据
        Route::post('/update_daily', 'ChartsController@update_daily');

        //index
        Route::get('/', 'ChartsController@index');
    });

    /*
    | --------------------------------------------------------------------
    |  通告单相关
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => 'notices'], function () {

        //发送通告单
        Route::post('/send', 'NoticesController@send');

        //撤销发送通告单
        Route::post('/redo', 'NoticesController@redo');

        //选择
        Route::post('/choose', 'NoticesController@choose');

        //查看通告单的接受人
        Route::get('/{notice_id}/receivers', 'NoticesController@receivers');

        //查看通告单内容
        Route::get('/{notice_id}', 'NoticesController@show');

    });

    Route::resource('/notices', 'NoticesController');


    /*
    | --------------------------------------------------------------------
    |  剧组通知,剧本扉页相关
    |---------------------------------
    |  1.查看通知的接受详情
    |  2.撤销发送
    |  3.查看某一个剧组通知
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => '/messages'], function () {

        //查看某一个通知的接受详情
        //谁接受了这个剧组通知,什么时候接受的
        Route::get('/{id}/receivers', 'MessagesController@receivers');

        //撤销发送剧组通知
        Route::get('/{id}/redo', 'MessagesController@redo');

        //查看某一个剧组通知
        Route::get('/{id}', 'MessagesController@show');
    });


    /*
    | --------------------------------------------------------------------
    |  查看某一个人的剧组通知
    |---------------------------------
    |  1.查看某一个用户的剧组通知
    |  2.创建剧组通知
    |  3.显示创建剧组通知/剧本扉页界面
    | --------------------------------------------------------------------
    */
    Route::group(['prefix' => '/users/{id}/messages'], function () {

        //查看某一个人的剧组通知消息
        Route::get('/', 'MessagesController@index');

        //显示创建一个剧组通知/剧本扉页消息界面
        Route::get('/create', 'MessagesController@create');

        //保存一个剧组通知
        Route::post('/', 'MessagesController@store');
    });


    /*
    | --------------------------------------------------------------------
    |  用户行为
    | --------------------------------------------------------------------
    */
    Route::get('/users/contact', 'UsersController@contact');

    Route::group(['prefix' => '/users/{id}'], function () {

        //显示艺人资料
        Route::get('/', 'UsersController@show');

        //我在本组
        Route::get('/group', 'UsersController@group');

        //w我在本阻力的所有部门详情页
        Route::get('/all-groups', 'UsersController@allGroups');

        //练习
        Route::get('/practice', 'UsersController@practice');

        //显示申请加入其它部门界面
        Route::get('/join_other_group', 'UsersController@createJoinOtherGroup');

        //申请加入其他部门
        Route::post('/join_other_group', 'UsersController@storeJoinOtherGroup');

        //显示申请退出部门界面
        Route::get('/exit_group', 'UsersController@createExitGroup');

        //退出部门
        Route::post('/exit_group/{groupId}', 'UsersController@storeExitGroup');

        //退出剧组
        Route::post('/exit_movie/{movieId}', 'UsersController@storeExitMovie');

        //同意某人加入部门
        Route::post('/approve_join_group/{joinId}', 'UsersController@approveJoinGroup');

        //拒绝某人加入部门
        Route::post('/decline_join_group/{joinId}', 'UsersController@declineJoinGroup');

        //更新我在本组的信息
        Route::post('/group', 'UsersController@updateGroupInfo');

        //分享
        Route::get('/share', 'UsersController@share');

        //添加分享
        Route::get('/add_share', 'UsersController@add_share');

        //更新分享
        Route::post('/post_share', 'UsersController@post_share');

        //删除分享
        Route::delete('/delete_share', 'UsersController@delete_share');

    });


    Route::get('/blogs/{id}', 'BlogsController@show');

    Route::get('/recruits/{id}', 'RecruitsController@show');

    Route::get('/reports/create', 'ReportsController@create');

    Route::get('/reports/{id}', 'ReportsController@show');

    Route::post('/reports', 'ReportsController@store');

    Route::delete('/likes/{id}', 'LikesController@destory');

    Route::post('/likes', 'LikesController@store');

    Route::group(['prefix' => 'movies'], function () {

        Route::post('/validate', 'MoviesController@validateMovieNames');
        //搜索剧组
        Route::get('/search', 'MoviesController@search');

        //申请加入剧组
        Route::get('/access_join', 'MoviesController@access_join');

        //加入剧组
        Route::post('/post_join', 'MoviesController@post_join');

        Route::get('/{id}/members', 'MoviesController@members');
    });

    //电影相关
    Route::resource('/movies', 'MoviesController');

    //菜单
    Route::resource('/menus', 'MenusController');

    //业内资源相关
    Route::group(['prefix' => 'trade-resources'], function () {

        //业内资源一级菜单界面
        Route::get('/', 'TradeResourcesController@index');

        //公司
        Route::get('/companies', 'TradeResourcesController@indexCompanies');

        //公司详情
        Route::get('/companies/{companyId}', 'TradeResourcesController@showCompany');

        //可交易剧本
        Route::get('/scripts', 'TradeResourcesController@indexScripts');

        //剧本详情
        Route::get('/scripts/{scriptId}', 'TradeResourcesController@showScript');

        //检测profile
        Route::post('/sendCV', 'TradeResourcesController@sendCV');
        //发送profile 链接
        Route::post('/sendProfile/{type}', 'TradeResourcesController@sendProfile');
        //跳转到合作邀约
        Route::get('/coopration/{type}', 'TradeResourcesController@sendCoopration');
        //发送合作邀约
        Route::post('/cooprationpost', 'TradeResourcesController@cooprationpost');
        //大基地
        Route::get('/basements', 'TradeResourcesController@indexBasement');
        //大基地详情
        Route::get('/basements/{basementId}', 'TradeResourcesController@showBasement');
        //摄影器材
        Route::get('/photographEquipments', 'TradeResourcesController@indexPhotographEquipment');
        //light
        Route::get('/lightEquipments', 'TradeResourcesController@indexLightEquipment');
        //overseas
        Route::get('/overseasRecords', 'TradeResourcesController@indexOverseasRecord');
        //yinxiao company
        Route::get('/yinxiaoCompanies', 'TradeResourcesController@indexYinxiaoCompany');
        //economy company
        Route::get('/economyCompanies', 'TradeResourcesController@indexEconomyCompany');
        //texiao
        Route::get('/specialEffects', 'TradeResourcesController@indexSpecialEffect');
        //pray
        Route::get('/prays', 'TradeResourcesController@indexPray');
        //lawassis
        Route::get('/lawassist', 'TradeResourcesController@indexLawassist');
        //items
        Route::get('/items', 'TradeResourcesController@indexItems');
        //desserts
        Route::get('/desserts', 'TradeResourcesController@indexDesserts');
        //uniforms
        Route::get('/uniforms', 'TradeResourcesController@indexUniforms');
        //studios
        Route::get('/studios', 'TradeResourcesController@indexStudios');
        //insurances
        Route::get('/insurances', 'TradeResourcesController@indexInsurances');

        Route::get('/hotels', 'TradeResourcesController@indexHotels');
    });


    //unions
    Route::group(['prefix' => 'unions'], function () {
        Route::get('/', 'UnionsController@index');
        Route::get('/{unionId}', 'UnionsController@UnionIndex');
        //introduction
        Route::get('/intro/{unionId}', 'UnionsController@showUnion');
        //the detial of notice bignews coopration
        Route::get('/detail/{detailType}/{unionId}/{messageId}', 'UnionsController@Detail');
        //big new
        Route::get('/bignews/{unionId}', 'UnionsController@Bignews');
        //notice
        Route::get('/notice/{unionId}', 'UnionsController@Notice');
        //coopration
        Route::get('/coopration/{unionId}', 'UnionsController@Coopration');
        //vip company
        Route::get('/vipcompanies/{unionId}', 'UnionsController@UnionVipCompany');
        //艺人推荐
        Route::get('/recommend/{unionId}', 'UnionsController@UnionStar');
        //person vip
        Route::get('/vip/{unionId}', 'UnionsController@UnionVip');
        //person feedback
        Route::get('/feedback/{unionId}', 'UnionsController@Feedback');
        Route::post('/feedbackpost', 'UnionsController@Feedbackpost');
        Route::get('/apply/{unionId}', 'UnionsController@apply');
        Route::post('/postapply', 'UnionsController@postapply');

    });

    /**
     * 参考大计划
     */
    Route::group(['prefix' => '/plans'], function () {
        //接受详情
        Route::get('/{planId}/receivers', 'ReferencePlansController@receivers');

        //大计划详情
        Route::get('/{planId}/', 'ReferencePlansController@show');
    });

    //日报表接受详情
    Route::get('/daily-reports/{dailyReportId}/receivers', 'DailyReportsController@receivers');

    //Get the product detail.
    Route::get('/malls/products/{productId}', 'ProductsController@show');

    //Previous prospect receivers detail page.
    Route::get('/previous-prospects/{previousProspect}/receivers', 'PreviousProspectsController@receivers');

    //Groupuser feedback receivers page.
    Route::get('/groupuser-feedbacks/{groupuserfeedback}/receivers', 'GroupUserFeedbacksController@receivers');


    Route::get('/daodao-location', function () {

        $locations = \App\Models\UserLocation::where('user_id', 21100)
                                             ->orderBy('created_at', 'desc')
                                             ->take(20)
                                             ->get();
        return view('location', compact('locations'));
    });

    Route::get('/test', function () {
        return view('test');
    });

    Route::get('/education-schools/easy-education', 'EducationSchoolsController@index');
    Route::get('/education-schools/easy-education/join', 'EducationSchoolsController@createJoinSchool');
    Route::post('/education-schools/easy-education/join', 'EducationSchoolsController@storeJoinSchool');

    Route::get('/clients', 'InvestmentsController@clients');
    Route::post('/clients', 'InvestmentsController@storeClients');
    Route::get('/movie-companies', 'InvestmentsController@movies');
    Route::post('/movie-companies', 'InvestmentsController@storeMovies');

});
