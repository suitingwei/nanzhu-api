@servers(['test_web' => 'root@nanzhu_test','web1' => 'root@nanzhu_web1','web2' => '-p 2233 root@nanzhu_web2','web3' => 'root@nanzhu_web3'])

#测试环境部署
@task('test_deploy', ['on' => 'test_web'])
    cd /usr/share/nginx/html/deploy

    git pull

    php artisan migrate
@endtask

#正式环境部署
@task('production_deploy', ['on' => ['web1','web2','web3'],'confirm' => true])
    cd /usr/share/nginx/html/api

    git pull

    php artisan migrate
@endtask

