# UET - Elasticsearch

> Develop uet elasticsearch

## Version
Status: Completed

Submission date: 09/05/2017


## Technology & Library
Laravel 5

Elastic search

Redis

Bootstrap

Jquery

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
        ## Development

#### Step 1: Install /vendor & /node_modules
            
            $ composer install
            
#### Step 2: Database & migration

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

#### Other
            Seed db
            php artisan db:seed --class=CLASS_NAME
                

## Developers

Tran Minh Tuan - UET - tuantmtb@gmail.com

Nguyen Van Nhat - UET - nguyenvannhat152@gmail.com

Do Van Quang - UET 

Nguyen Thi Lan - UET 

