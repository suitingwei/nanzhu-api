<?php

Route::group(['namespace' => 'Api', 'prefix' => 'api'], function () {
    /*
     *--------------------------------------------------------------
     * All about the notices.
     *--------------------------------------------------------------
     * 1. Get Daily notices' list.
     * 2. Get Prepare notices' list.
     * 3. Send the notice's file to the receivers.
     * 4. Redo the notice's file.
     * 5. Get the notice file's receivers details.
     * 6. Get the notice file's detail.
     * 7. Get the candidates to be notified for the prepare notice file.
     */
    Route::group(['prefix' => 'notices'], function () {

        //每日通告单列表
        Route::get('/daily', 'NoticesController@daily');

        //预备通告单列表
        Route::get('/prepare', 'NoticesController@prepare');

        //通告单详情
        Route::get('/{notice_id}', 'NoticesController@show');

        //发送通告单某一个文件
        Route::post('/files/{notice_file_id}/send', 'NoticesController@sendNoticeFile');

        //撤销通告单文件
        Route::post('/files/{notice_file_id}/undo', 'NoticesController@undo');

        //查看通告单的某一个文件接受详情
        Route::get('/files/{notice_file_id}/receivers', 'NoticesController@receivers');

        //预备通告单选择接收人
        Route::get('/prepare/files/{notice_file_id}/choose', 'NoticesController@choose');

    });

    /*
     | ------------------------------------------------
     |  参考大计划接口
     |  2016-12-01
     | ------------------------------------------------
     | 1. 大计划列表
     | 2. 大计划文件发送
     | 3. 大计划文件撤销发送
     | 4. 大计划文件接受详情
     | 5. 大计划文件发送选择接收人
     | 6. 大计划文件详情
     */
    Route::group(['prefix' => 'plans'], function () {
        //获取参考大计划列表
        Route::get('/', 'ReferencePlansController@index');

        //发送
        Route::post('/{planId}/send', 'ReferencePlansController@send');

        //撤回
        Route::post('/{planId}/undo', 'ReferencePlansController@undo');

        //接受详情
        Route::get('/{planId}/receivers', 'ReferencePlansController@receivers');

        //选择接收人
        Route::get('/{planId}/choose', 'ReferencePlansController@choose');

        //大计划详情
        Route::get('/{planId}', 'ReferencePlansController@show');
    });
    /*
     | ------------------------------------------------
     |  剧组通知接口
     |  2016-12-12
     | ------------------------------------------------
     | 1. 剧组通知列表
     | 2. 新建剧组通知
     | 3. 剧组通知详情
     */
    Route::group(['prefix' => 'notifications'], function () {
        //剧组通知列表
        Route::get('/', 'MessagesController@index');

        //新建剧组通知
        Route::post('/', 'MessagesController@createNew');

        //剧组通知详情
        Route::get('/{notificationId}', 'MessagesController@showDetail');

        //撤销剧组通知
        Route::post('/{notificationId}/undo', 'MessagesController@undo');

        //剧组通知接受详情
        Route::get('/{notificationId}/receivers', 'MessagesController@receivers');
    });
    /*
     | ------------------------------------------------
     |  剧本扉页接口
     |  2016-12-12
     | ------------------------------------------------
     | 1. 剧组扉页列表
     | 2. 新建剧本扉页
     | 3. 剧组扉页详情
     */
    Route::group(['prefix' => 'title-pages'], function () {
        //剧组扉页列表
        Route::get('/', 'MessagesController@index');

        //新建剧组扉页
        Route::post('/', 'MessagesController@createNew');

        //剧本扉页详情
        Route::get('/{pageId}', 'MessagesController@showDetail');

        //撤销剧组通知
        Route::post('/{pageId}/undo', 'MessagesController@undo');

        //剧本扉页接受详情
        Route::get('/{pageId}/receivers', 'MessagesController@receivers');
    });
    /*
     | ------------------------------------------------
     |  场记日报表接口
     |  2016-12-14
     | ------------------------------------------------
     | 1. 日报表列表
     | 2. 新建日报表
     | 3. 日报表详情
     */
    Route::group(['prefix' => 'daily-reports'], function () {

        //场记日报表列表
        Route::get('/', 'DailyReportsController@index');

        //新建日报表
        Route::post('/', 'DailyReportsController@store');

        //场记日报表详情
        Route::get('/{dailyReportId}', 'DailyReportsController@show');

        //Daily reprot's update records.
        Route::get('/{dailyReportId}/update-records', 'DailyReportsController@updateRecords');

        //场记日报表更新
        Route::put('/{dailyReportId}', 'DailyReportsController@update');
    });
    //支付回调
    Route::post('/pays/callback', 'PaysController@callback');
    //支付
    Route::post('/pays/charge', 'PaysController@charge');
    /**
     * ----------------------------------------------
     *  天气相关
     * ----------------------------------------------
     * 1. 获取天气信息
     * 2. 获取所有省信息
     * 3. 获取所有市信息
     * 4. 获取所有地区信息
     */
    Route::group(['prefix' => 'weather'], function () {
        //Get the weather info for a user.
        Route::get('/', 'WeatherController@index');
        //Get all provinces.
        Route::get('/provinces', 'WeatherController@getProvinces');
        //Get all cities of a province.
        Route::get('/cities', 'WeatherController@getCities');
        //Get all districts of a city.
        Route::get('/districts', 'WeatherController@getDistricts');
    });

    //Get the verify code.
    Route::get('account/verify_code', 'AccountsController@verify_code');

    //Login.
    Route::post('account/login', 'AccountsController@login');

    //Update the user's account info.
    Route::post('account/update', 'AccountsController@update');

    //App's splash pictures.
    Route::get('pictures', 'PicturesController@index');

    //Don't konw what's that.
    Route::post('pictures/callback', 'PicturesController@callback');

    //Upload a new pictures to aliyun oss.
    Route::post('pictures', 'PicturesController@upload');

    /*
     *--------------------------------------------------------------
     * All about user.
     *--------------------------------------------------------------
     */
    Route::group(['prefix' => 'users/{user_id}'], function () {
        //发布的招聘信息
        Route::get('/recruits', 'UsersController@recruits');

        //更新备忘录
        Route::put('/blogs/{id}', 'UsersController@blog_update');

        //删除备忘录
        Route::delete('/blogs/{id}', 'UsersController@blog_delete');

        //更新用户选择的天气的地址
        Route::put('/weather/location', 'WeatherController@updateLocation');

        //获取用户选择的天气的地址
        Route::get('/weather/location', 'WeatherController@getLocation');

        //获取用户的所有备忘录
        Route::get('/blogs', 'UsersController@blogs');

        //用户的所有消息通知,现在包括了剧组通知,系统通知,好友消息等
        Route::get('/messages', 'UsersController@messages');

        //用户的喜欢的
        Route::get('/favorites', 'UsersController@favorites');

        //更新个人资料
        Route::post('/profiles/update', 'UsersController@profile_update');

        //该用户收到的所有好友申请
        Route::get('/friend_applications', 'FriendsController@applications');

        //向用户发出好友申请
        Route::post('/friend_applications', 'FriendsController@applyUserBeFriend');

        //同意好友申请
        Route::put('/friend_applications/{applicationId}', 'FriendsController@approveApplication');

        //删除好友
        Route::delete('/friends/{friendId}', 'FriendsController@deleteFriend');

        //用户的所有好友
        Route::get('/friends', 'FriendsController@friends');

        //用户所在的所有剧组所有部门
        Route::get('/joined_movie_groups', 'HxGroupController@joinedMovieGroups');

        //获取用户创建的环信群组信息
        Route::get('/joined_app_create_chat_groups/', 'HxGroupController@joinedAppCreateGroups');

        //获取用户加入的所有的环信剧组
        Route::get('/joined_all_chat_groups/', 'HxGroupController@joinedAllHxGroups');

        //发送邀请,邀请用户注册app或者打开好友聊天或者发起好友申请
        Route::post('/invites', 'UsersController@sendInvitation');

        //获取用户信息
        Route::get('/info', 'UsersController@getUserInfo');

        //获取群组详情
        Route::get('/chat_groups/{hxGroupId}', 'HxGroupController@show');

        //群组添加用户
        Route::post('/chat_groups/{hxGroupId}/users/{userId}', 'HxGroupController@addMember');

        //群组删除用户(只有群主可以操作)
        Route::delete('/chat_groups/{hxGroupId}/users/{userId}', 'HxGroupController@deleteMember');

        //解散群组
        Route::delete('/chat_groups/{hxGroupId}', 'HxGroupController@dismissGroup');

        //群成员退出群组(群主必须先移交权限)
        Route::post('/chat_groups/{hxGroupId}/exit', 'HxGroupController@exitGroup');

        //转让群主
        Route::put('/chat_groups/{hxGroupId}/transfor_owner/{newUserId}', 'HxGroupController@transforOwner');

        //更新群组公告
        Route::put('/chat_groups/{hxGroupId}', 'HxGroupController@updateHxGroupInfo');

        //环信加入黑名单
        Route::post('/chat_groups/{hxGroupId}/blacklists/users/{userId}', 'HxGroupController@groupBlockUser');

        //环信用户剔除黑名单
        Route::delete('/chat_groups/{hxGroupId}/blacklists/users/{userId}', 'HxGroupController@groupUnBlockUser');

        //获取环信群聊黑名单
        Route::get('/chat_groups/{hxGroupId}/blacklists', 'HxGroupController@groupBlackLists');

        //用户自己创建的群聊
        Route::post('/app_create_group', 'HxGroupController@appCreateHxGroup');

        //把im用户加入某人黑名单
        Route::post('/blacklists/users/{userid}', 'UsersController@userBlockUser');

        //把im用户移除某人黑名单
        Route::delete('/blacklists/users/{userId}', 'UsersController@userUnBlockUser');

        //查看用户的im黑名单
        Route::get('/blacklists', 'UsersController@userBlackLists');

        //获取用户的协助编辑列表
        Route::get('/can_edit_profiles/', 'UsersController@canEditProfiles');

        //获取用户的当前的位置
        Route::get('/location', 'LocationsController@getUserLocation');

        //更新用户的当前位置(经纬度,60s前端调用一次)
        Route::post('/location', 'LocationsController@storeUserLocation');

        //60s保存一次这之间所有的数据
        Route::post('/location-per-minute', 'LocationsController@storeUserLocationPerMinute');

        //获取用户手机通讯录里的联系人信息,是否注册,是否好友
        Route::get('getPhoneContactUserInfo', 'UsersController@getPhoneContactUserInfo');

        //Get all joined groups in movie.
        Route::get('/joinedGroupsInMovie', 'UsersController@joinedGroupsInMovie');
    });

    //A user's profile detail.
    Route::resource('users', 'UsersController', ['only' => ['show']]);

    //The banners of the app's ground tab.
    Route::resource('/banners', 'BannersController');

    //Purchase orders of the professional videos.
    //I change to english because of the fucking sougou input source.
    Route::resource('/shoot_orders', 'ShootOrdersController');

    //业内动态
    Route::get('/blogs/types', 'BlogsController@types');

    //The recent trend news in the movie industry.
    Route::resource('/blogs', 'BlogsController');

    //Recurits info.
    Route::resource('/recruits', 'RecruitsController');

    //All masive messages,e.g. JUZU,BLOG,DAILY_REPORT
    Route::resource('/messages', 'MessagesController');

    //Create a new report.
    Route::post('/reports', 'ReportsController@store');

    //Delete a user's favorites thing.
    Route::post('/favorites/delete', 'FavoritesController@destroy');

    //Create a new favorite thing record.
    Route::post('/favorites', 'FavoritesController@store');

    //Create a new feedback.
    Route::post('/feedbacks', 'FeedbacksController@store');

    //Fuck the likes.
    Route::post('/likes/delete', 'LikesController@destroy');

    //Fuck a new like.
    Route::post('/likes', 'LikesController@store');

    //Get a certain type of profiles.
    Route::get('/profiles/types', 'ProfilesController@types');

    //CURD of the user's profiles.
    Route::resource('/profiles', 'ProfilesController');

    //CURD of the movie groups.
    Route::resource('/groups', 'GroupsController');

    //A todo's resources controller.
    Route::resource('/todos', 'TodosController');

    //All todo's update records.
    Route::get('todos/{todo}/update-records', 'TodosController@updateRecords');

    //Get the fucking example professional viedo.
    Route::get('/video/professional', 'GroundController@getProfessionalVideoUrl');

    //All banners of the app's ground tab.
    Route::resource('/ground', 'GroundController');

    //Get the app's menus tab,only for workstation.
    Route::resource('/menus', 'MenusController');

    Route::group(['prefix' => '/movies'], function () {
        //Search the movie.
        Route::get('/search', 'MoviesController@search');

        //Get all groups in movie.
        Route::get('/{movie}/groups', 'MoviesController@groups');

        //Join the group.
        Route::post('/{movie}/join', 'MoviesController@join');
    });

    //CURD of a movie.
    Route::resource('/movies', 'MoviesController');

    //Get the movie contacts.
    Route::get('movies/{movie}/contacts', 'MoviesController@contacts');

    //Get the movie's all members.
    Route::get('/movies/{movie}/members', 'MoviesController@members');
    //Get movie's public contacts.
    Route::get('movies/{movie}/public-contacts', 'MoviesController@publicContacts');
    //Update the movie's public contacts.
    Route::put('movies/{movie}/public-contacts', 'MoviesController@updatePublicContacts');

    //iOS's app version updater.Check if device needs to update.
    Route::get('/appUpdate', 'VersionsController@index');

    //App's version CURD operations.
    Route::resource('/versions', 'VersionsController');

    /*
     | ----------------------------------------------
     | All concerns about the user.
     | ----------------------------------------------
     */
    Route::group(['prefix' => 'im'], function () {
        //Get a user's info for webim.
        Route::get('/getUsersInfo', 'ImController@getUsersInfo');

        //Get a hx chat group info.
        Route::get('/getGroupsInfo', 'ImController@getGroupsInfo');
    });

    //Get all wechat config for h5 page's wechat share.
    Route::get('wechat/get-config', 'WeChatController@getConfig');

    /*
     *--------------------------------------------------------------
     * The mall of nanzhu.
     *--------------------------------------------------------------
     * 1. Products
     * 2. Product sizes.
     * 3. Product prices.
     * 4. Purchases.
     * 5. Payments.
     */
    Route::group(['prefix' => 'malls'], function () {
        //CURD of the products.
        Route::resource('products', 'ProductsController');

        Route::resource('/brands', 'BrandsController');

        Route::delete('/product-brands/delete', 'ProductBrandsController@destroy');

        Route::resource('/product-brands', 'ProductBrandsController');

        //CURD of the product sizes.
        Route::resource('product-sizes', 'ProductSizesController');

        //CURD of the product prices.
        Route::resource('product-prices', 'ProductPricesController');

        //Things about the purchases.
        Route::resource('purchases', 'PurchasesController');

        //Calculate the express prices for the current purchaseitems.
        Route::post('/purchases/express-prices', 'PurchasesController@calculateExpressPrice');

        Route::group(['prefix' => 'purchases/{purchaseId}'], function () {
            //Update the purchase status.
            Route::put('/status', 'PurchasesController@updateStatus');

            //Recreate the charge object.
            Route::post('/recharge', 'PurchasesController@recharge');

            //Update the purchase address.
            Route::put('/address', 'PurchasesController@updateAddress');

            //Update the purchase totalprice
            Route::put('/totalprice', 'PurchasesController@updateTotalprice');
        });

//        //Things about the payments.
//        Route::resource('payments', 'PaymentsController');

        //CURD of a user's addresses.
        Route::resource('user-addresses', 'UserAddressesController');

        //Get all purchase items.
        Route::resource('purchase-items', 'PurchaseItemsController');
    });

    /*
     *--------------------------------------------------------------
     * The social security service.
     *--------------------------------------------------------------
     * 1. Social Security
     */
    Route::group(['prefix' => 'social-securities'], function () {
        //Create a new social security record.
        Route::post('/', 'SocialSecurityController@store');

        Route::get('/service-info', 'SocialSecurityController@serviceInfo');

        Route::get('/options', 'SocialSecurityController@options');

        //Get all social security order
        Route::get('/orders', 'SocialSecurityOrdersController@index');

        Route::group(['prefix' => '/orders/{orderId}'], function () {
            //Get the social security order detail.
            Route::get('/', 'SocialSecurityOrdersController@show');

            //Update the order's status.
            Route::put('/status', 'SocialSecurityOrdersController@updateStatus');

            //Get the ping++ charge object.
            Route::post('/pay', 'SocialSecurityOrdersController@pay');

            //Continue the social security's order.
            Route::post('/continue-pay', 'SocialSecurityOrdersController@continuePay');
        });
    });

    //Get the previous prospect's update records.
    Route::get('previous-prospects/{id}/update-records', 'PreviousProspectsController@updateRecords');

    /**
     * Things about previous prospect.
     */
    Route::resource('previous-prospects', 'PreviousProspectsController');

    /**
     *--------------------------------------------------------------
     * User in group routes.
     *--------------------------------------------------------------
     */
    Route::group(['prefix' => '/users/{id}/in-group'], function () {
        Route::get('/', 'UserInGroupsController@index');

        Route::put('/', 'UserInGroupsController@update');

        Route::get('/all-groups', 'UserInGroupsController@allGroups');

        Route::post('/exit-movie', 'UserInGroupsController@exitMovie');

        Route::post('/exit-group', 'UserInGroupsController@exitGroup');

        Route::post('/join-group', 'UserInGroupsController@joinGroup');
    });

    //CURD of the group user feed back.
    Route::resource('groupuser-feedbacks', 'GroupUserFeedbacksController');

    Route::get('/h5_union_entry', 'UsersController@indexH5UnionUrl');

    Route::get('/blogs/{blog}/clear-cache', 'BlogsController@clearCache');

    Route::get('/search', 'SearchController@search');
    Route::get('/searchMore', 'SearchController@searchMore');
    Route::get('/trade-resources', 'TradeResourcesController@index');
});


