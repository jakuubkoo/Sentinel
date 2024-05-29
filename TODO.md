# Sentinel - TODOs

## Version 0.1
- **Symfony**
  - [X] Symfony basic API setup
	- [X] Setup phpunit, phpstan & phpcs
	- [X] PHPStan & PHPUnit actions

- **Env**
	- [X] Docker env (apache, php, mysql)

- **Base**
  - [X] Log Manager
  - [X] Error / exception manager
  - [X] Internal Exception handler (log only file with LoggerInterface)
  - [X] Mysql online middleware
  - [X] Security middlewares (SSL check, Escape)
  - [X] Maintenance middleware

## Version 0.2
- **User System**
  - [X] User Entity
  - [X] Password hasher
  - [X] User manager
  - [X] User fixtures
  - [X] Register command
  - [X] Grant admin to user command

- **Email Service**
  - [X] Email Sending Method
  - [X] Multiple Email Sending Method
  - [X] Email manager test
  - [X] Down alert email template

## Version 0.3
- **Monitoring**
    - [X] Service util (service status check)
    - [X] Service Entity
    - [ ] Service Manager
    - [ ] Service monitoring
    - [ ] Register monitoring service command
    - [ ] Loop Command | Service Checker
		- [ ] Service status command (all / specific service)
		- [ ] Status history command

## Version 0.4
- **Content Checker**
  - [ ] Check website content

## Version 0.5
- **API Base**
  - [ ] API Token Authenticator
  - [ ] Service Status Endpoint
  - [ ] Monitoring Log Endpoint
  - [ ] All Services Status Endpoint

## Version 0.6
- - **Error Handler**
  - [ ] Error entity (store errors in mysql)
  - [ ] Simple error add endpoint (accept error message & email send (bool))
  - [ ] Send error to email
  - [ ] Get error list by service name

## Version 0.7
- **Log Handler**
	- [ ] Log entity (mysql)
  - [ ] Log add endpoint (log name, value)
  - [ ] Get log list by service name

## Version 0.8
- **Elasticsearch**
  - [ ] Implement elasticsearch
  - [ ] Elastic connection check middleware
  - [ ] Elastic index ORM repository
  - [ ] Database env switch (mysql, elastic)
