# VCI - Scholar

> Develop vci scholar

## Version
Status: Release alpha version

Submission date: 09/01/2017

## Structure

Resource doc: /resource-doc

Resource doc contain: dump, elastsearch query sample

## Document

Detailed guides in /resource-doc older.


## Technology & Library
Bootstrap

Jquery

Laravel 5

Elastic search

## Requirement system

## Deployment

0. Install elasticsearch

Ubuntu:

        https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-elasticsearch-on-ubuntu-14-04
        https://www.elastic.co/guide/en/elasticsearch/reference/master/deb.html#install-deb
        
        $ sudo ufw allow 9200
        $ sudo ufw allow 5601
        
        In order to open access: set host: 0.0.0.0


1. Create database
      
2. Config file .env
       Constant view .env.example
        ELASTIC_VNU_IP=112.137.131.9:9200
        MAX_RESULT_SEARCH=2000
        
        - Config database
        - Mailer account
        - Generate key
        $ php artisan key:generate

3. Install

Install manual

        $ composer install
        $ composer update
        $ php artisan key:generate
        $ php artisan migrate --seed
        $ php artisan serve
        
        - Open browser localhost:8080
        
4. Account user

        Admin account: 
        
## Development

#### Step 1: Install /vendor & /node_modules
            
            $ composer install
            $ npm install
            
#### Step 2: Database & migration
            $ If imported sql -> skip step2, 3             
            Tạo database uet-thesis
            Copy .env.example > .env, cấu hình lại DB, MAIL (uet.thesis@gmail.com | thesis.uet)
            $ php artisan migrate --seed
            
#### Step 3: Configurations

            $ php artisan key:generate

#### Step 4: Serve

            $ php artisan serve
            
#### Step 5: generate php docs
            
            $ php artisan ide-helper:generate
            $ php artisan ide-helper:models
            $ php artisan ide-helper:meta
            $ php artisan optimize
            $ composer dump-autoload
            $ php artisan config:cache
            $ php artisan cache:cleả
            
#### Debug, print console
            
            Log::info("messages")
            $ tail -f storage/logs/laravel.log | ccze -A

#### Production
            
            Index
            
            sudo php artisan db:seed --class=IndexArticleExecute
            sudo php artisan db:seed --class=IndexJournalExecute
            sudo php artisan db:seed --class=IndexOrganizeExecute
            
#### Infomation
            
            Slack: https://rd320-team.slack.com
            
            
#### Other
            Seed db
            php artisan db:seed --class=js_dump_db
            
                
## Common problem

1. Không seed migrate được:
            edit ".env": set CACHE_DRIVER=array
            $ php artisan config:cache
            $ php artisan migrate:refresh --seed
                        
            $ composer dump-autoload
            $ php artisan db:seed hoặc $ php artisan migrate:refresh --seed
            
2. Form không post được:

            Thêm {{Form::token()}} vào trong form
            
3. Lỗi curl

            Phải install sudo apt-get install php-curl
            
## PhpStorm plugin instructions
    
            Settings > Plugins > Browse repositories... > Tìm 'Laravel plugin' > Cài 
            Settings > Languages and Frameworks > Php > Laravel > Bật 'Enable plugin for this project'
                
      
## Library

        https://github.com/lazychaser/laravel-nestedset
        watson/sitemap
        phpoffice/phpword
        maatwebsite/excel
        zizaco/entrust
# Teachers

Vo Dinh Hieu - UET - hieuvd@vnu.edu.vn

## Developers

Tran Minh Tuan - UET - tuantmtb@gmail.com

Nguyen Van Nhat - UET - nguyenvannhat152@gmail.com

Nguyen Bao Ngoc - UET - baongoc124@gmail.com