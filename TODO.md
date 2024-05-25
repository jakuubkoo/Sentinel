# Sentinel - TODOs

## Version 0.1
- **Symfony**
  - [X] Symfony basic API setup
	- [X] Setup phpunit, phpstan & phpcs
	- [X] PHPStan & PHPUnit actions

- **Env**
	- [X] Docker env (apache, php, mysql, elasticsearch)

- **Base**
  - [ ] Log Manager
  - [ ] Error / exception manager
  - [ ] Security middlewares (SSL check, Escape)
  - [ ] Elasticsearch & mysql online middleware
  - [ ] Internal Exception handler (log only file with LoggerInterface)

## Version 0.2
- **User System**
  - [ ] User Entity
  - [ ] Register command
  
- **Email Service**
  - [ ] Email Sending Method
  - [ ] Multiple Email Sending Method

## Version 0.3
- **Monitoring**
    - [ ] Service Entity
    - [ ] Register monitoring service command
    - [ ] Service Manager
    - [ ] Monitoring for HTTP
    - [ ] TCP/UDP Service Monitor
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
  - [ ] Error entity (store errors in mysql & elastic index)
	- [ ] Error elasticsearch repository (index)
  - [ ] Simple error add endpoint (accept error message & email send (bool))
  - [ ] Send error to email
  - [ ] Get error list by service name (main get form elasticsearch)

## Version 0.7
- **Log Handler**
	- [ ] Log entity (mysql, elastic)
	- [ ] Log elasticsearch repository (index)
  - [ ] Log add endpoint (log name, value)
  - [ ] Get log list by service name (main form elasticsearch)
