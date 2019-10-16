# 常用命令

##### [](#bviemt)1.查看cpu,内存,硬盘
    lscpu
    free -m
    df -h
##### [](#bviemt)2.查看php扩展
    php -m | less
##### [](#bviemt)3.编辑显示行号
    vi -n xxx
##### [](#bviemt)4.ab压测
    yum -y install httpd-tools
    ab -n 1000 -c 200 http://xxxxxxx
##### [](#bviemt)5.重启nignx
    systemctl restart nginx
##### [](#bviemt)6.重启php-fpm
    systemctl restart php-fpm
##### [](#bviemt)7.安装php扩展
    cd ~/lnmp/src
    tar -vxf php-7.2.10.tar.gz
    cd php-7.2.10/ext/fileinfo
     /usr/local/php/bin/phpize
    ./configure --with-php-config=/usr/local/php/bin/php-config
    make && make install
    vi /etc/php.ini
    extension=fileinfo.so
    systemctl restart php-fpm
##### [](#bviemt)8.安装es和ik
    cd /usr/local
    yum install java-1.8.0-openjdk-src.x86_64 -y
    cd /usr/bin
    ./java -version
    
    wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.4.1.tar.gz
    tar -vxf elasticsearch-6.4.1.tar.gz	
    useradd elasticer
    chown -R elasticer elasticsearch-6.4.1
    su elastic
    cd elasticsearch-6.4.1
    ./bin/elasticsearch 
    nohup ./bin/elasticsearch &
    
    出现 [WARN ][o.e.b.BootstrapChecks    ] [PayzBKz] max virtual memory areas vm.max_map_count [65530] is too low, increase to at least [262144]:
    vi /etc/sysctl.conf 
    vm.max_map_count=655360
    sysctl -p
    
    访问不通 47.106.210.3:9200：
    vi config/elasticsearch.yml
    修改netword.host为0.0.0.0即可；
    
    cd /usr/local
    wget https://artifacts.elastic.co/downloads/kibana/kibana-6.4.1-linux-x86_64.tar.gz
    tar -vxf kibana-6.4.1-linux-x86_64.tar.gz
    cd kibana-6.4.1-linux-x86_64
    nohup ./bin/kibana &
    
    访问 47.106.210.3:5601不通则检查下阿里的防火墙是否开启;
    出现Generating a random key for xpack.reporting.encryptionKey.To prevent pending reports from failing on restart, please setxpack.reporting.encryptionKey in kibana.yml,则在config 文件中添加 ：	
    xpack.reporting.encryptionKey: "a_random_string"
    xpack.security.encryptionKey: 		"something_at_least_32_characters"
    
    cd /usr/local
    cd elaXXX/plugins/ik
    wget https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v6.4.1/elasticsearch-analysis-ik-6.4.1.zip
    unzip elae_ikXXX 
##### [](#bviemt)9.安装 nodejs/composer
    cd /usr/local
    yum install nodejs
    yum install composer
##### [](#bviemt)10.composer install 出现问题:
    vi /usr/local/php/etc/php.ini
    删除disable_functions=xxx报错的方法
##### [](#bviemt)11.mysql.sock缺失
    vi /etc/my.cnf
    #socket=/tmp/mysqld.sock
    socket = /var/run/mysqld/mysqld.sock 
    systemctl restart mysqld
    mv /var/run/mysqld/mysql.sock  /tmp/mysql.sock
    systemctl restart mysqld
##### [](#bviemt)12.查看crond状态
    systemctl status crond.service 
