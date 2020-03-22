# RabbitMQ example with PHP

## Build containers project
 
> ./build.sh 

## Run containers
 
> ./run.sh

You see docker ps 

``` 
$ docker ps 
CONTAINER ID        IMAGE                   COMMAND                  CREATED             STATUS              PORTS                                                                    NAMES
5f8ff0eac8bd        rabbitmq:3-management   "docker-entrypoint.s…"   32 minutes ago      Up 32 minutes       4369/tcp, 5671-5672/tcp, 15671/tcp, 25672/tcp, 0.0.0.0:2083->15672/tcp   rabbitmq-management
```

## Run worker

> ./app.sh example/worker.php
 
```   
 [*] Waiting for messages. To exit press CTRL+C
```

## Run Publisher

> ./app.sh example/publisher.php

You see publisher task in queue

```   
Job created: 451
 [x] Sent Job 451 : {"id":"5e77892a1b2bb","job_number":0,"task":"sleep","sleep_period":3}
Job created: 451
 [x] Sent Job 451 : {"id":"5e7789531cdf4","job_number":1,"task":"sleep","sleep_period":1}
```

If you navigate to the terminar worker ins running, you should see

```   
 [*] Waiting for messages. To exit press CTRL+C
 [x] Received {"id":2211713,"task":"sleep","sleep_period":1}
 [x] Done

 [x] Received {"id":2211714,"task":"sleep","sleep_period":2}
 [x] Done
```

Queue is working!