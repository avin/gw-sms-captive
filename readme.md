## Captive portal с регистрацией через SMS

1.  GW по умолчанию пересылает все сетевые запросы на веб-сервер каптив-портала
2.  Каптив портал предлагает пользователю ввести ключ верификации
3.  В случае если ключа у пользоватлея нет - он вводит свой номер сотового телефона для получения ключа
5.  Тем временем система регистрирует в базе новый ключ с привязкой к указанному номеру телефона и высылает его 
посредством СМС. Ключ имеет определенный срок действия, после которого является недействительным.
6.  В случае ввода коректного ключа - система дает команду фаерволу на разрешение форвардинга запросов в интернет. 
(привязка проходит по мак мак адресу)
7.  По крону каждую минуту система находит просроченные сессии - помечает их и блокирует на фарволе (переходим к пункту 1)
  
### Установка

Вся установка описана на примере Ubuntu server 14.04

В последующем подразумевается, что eth0 смотрит в интернет, а eth1 в локалку (к пользователям)
eth1 ip-адрес используем 10.0.0.1
Пример /etc/network/interfaces:

```    
iface lo inet loopback

auto eth0
iface eth0 inet dhcp

auto eth1
iface eth1 inet static
address 10.0.0.1
network 10.0.0.0
netmask 255.0.0.0
```
    

Установка необходимых пакетов

```
sudo add-apt-repository ppa:ondrej/php5-5.6
sudo apt-get update
sudo apt-get install python-software-properties conntrack php5 php5-curl php5-json php5-fpm php5-mysql php5-mcrypt \
    php5-cli git conntrack nginx mysql-server isc-dhcp-server iptables-persistent
``` 

Настройка dhcp сервера
  
```
sudo sed -i -e 's/INTERFACES=""/INTERFACES="eth1"/' /etc/default/isc-dhcp-server
sudo sed -i -e 's/#authoritative/authoritative/' /etc/dhcp/dhcpd.conf
```

```
sudo vim /etc/dhcp/dhcpd.conf
```

```
# Добавить в конец
subnet 10.0.0.0 netmask 255.0.0.0 {
range 10.0.0.10 10.0.254.254;
option domain-name-servers 77.88.8.88, 77.88.8.2;
option routers 10.0.0.1;
option broadcast-address 10.255.255.255;}

```
     
Установка БД
```
mysql -u root
```
```
create database smscaptive character set utf8 COLLATE utf8_general_ci;
exit
```
     
Скачивание и установка данного приложения

```   
sudo mkdir -p /var/www/apps
cd /var/www/apps
sudo git clone https://github.com/avin/gw-sms-captive.git    
cd ./gw-sms-captive
sudo chmod -R 777 ./storage
curl -sS https://getcomposer.org/installer | sudo php
sudo php composer.phar install
sudo php -r "copy('.env.example', '.env');"
sudo php artisan key:generate
sudo php artisan migrate
sudo cp ./utils/rmtrack.sh /usr/local/bin/
sudo chmod +x /usr/local/bin/rmtrack.sh
sudo chown -R www-data:www-data ../gw-sms-captive
```
    
В файле .env выставить необходимые параметры (обязательно выставление корректного ключа SMS.RU)    

Добавить в cron
 
```   
sudo crontab -u www-data -e
```
```
* * * * * php /var/www/apps/gw-sms-captive/artisan schedule:run >> /dev/null 2>&1
```
        
    
Настройка nginx
     
```
sudo vim /etc/nginx/sites-enabled/default
```
        
```
server {
        listen   80;
                
        root /var/www/apps/gw-sms-captive/public;
        index index.php;        

        location / {
                 try_files $uri $uri/ /index.php?$query_string;
        }

        error_page 404 /index.php;

        error_page 500 502 503 504 /50x.html;
        location = /50x.html {
              root /usr/share/nginx/www;
        }

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        location ~ \.php$ {
                try_files $uri =404;
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
        }
}
```
```
sudo service nginx reload
```

Добавить пользователя www-data в sudo-users для запуска iptables и conntrack

```
sudo vim /etc/sudoers
```

```
# Добавить в конец
www-data ALL=(ALL) NOPASSWD: /sbin/iptables
www-data ALL=(ALL) NOPASSWD: /usr/bin/awk
www-data ALL=(ALL) NOPASSWD: /usr/local/bin/rmtrack.sh
# записать и выйти :wq!    
```

Добавить в файл /etc/sysctl.conf

```
net.ipv4.ip_forward=1
```

Первоночальная настройка iptables

```
sudo iptables -A POSTROUTING -t nat -o eth0 -j MASQUERADE
sudo iptables -t mangle -N internet

sudo iptables -t mangle -A PREROUTING -i eth1 -p tcp -m tcp -j internet
sudo iptables -t mangle -A PREROUTING -i eth1 -p udp -m udp -j internet

sudo iptables -t mangle -A internet -j MARK --set-mark 99
sudo iptables -t nat -A PREROUTING -i eth1 -p tcp -m mark --mark 99 -m tcp --dport 80 -j DNAT --to-destination 10.0.0.1
sudo iptables -t nat -A PREROUTING -i eth1 -p tcp -m mark --mark 99 -m tcp -m multiport ! --dports 80 -j DNAT --to-destination 127.0.0.1
sudo iptables -t nat -A PREROUTING -i eth1 -p udp -m mark --mark 99 -m udp -m multiport ! --dports 53 -j DNAT --to-destination 127.0.0.1

iptables -t nat -I POSTROUTING 1 -j LOG --log-prefix NETFILTER

sudo /etc/init.d/iptables-persistent save 
```

Добавить в /etc/rsyslog.conf

```
:msg, contains, "NETFILTER"       /var/log/iptables.log
:msg, contains, "NETFILTER"     ~
```

Закоментировать строку 
```
$ActionFileDefaultTemplate RSYSLOG_TraditionalFileFormat
```


### License

[MIT license](http://opensource.org/licenses/MIT)

awk '$0 >= "2015-11-01T10:22:26.990911-05:00" && $0 <= "2015-11-01T10:22:27.157133-05:00"' /var/log/iptables.log

