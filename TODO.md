# Sentinel - TODOs

## Version 0.1
- **Env**
  - [ ] Symfony basic API setup
  - [ ] Docker env (apache, php, mysql, elasticsearch)

- **Base**
  - [ ] Security middlewares (SSL check, Escape)
  - [ ] Elasticsearch & mysql online middleware
  - [ ] Internal Exception handler (log only file with LoggerInterface)

- **User System**
  - [ ] User Entity
  - [ ] Register command
  
- **Monitoring**
    - [ ] Service Entity
    - [ ] Service Manager
    - [ ] Monitoring for HTTP
    - [ ] TCP/UDP Service Monitor
    - [ ] Loop Command | Service Checker
    - [ ] Register monitoring service command

- **Email Service**
  - [ ] Email Sending Method
  - [ ] Multiple Email Sending Method

## Version 0.2
- **Content Checker**
  - [ ] Check website content

- **API Base**
  - [ ] API Token Authenticator
  - [ ] Service Status Endpoint
  - [ ] Monitoring Log Endpoint
  - [ ] All Services Status Endpoint

## Version 0.3
- - **Error Handler**
  - [ ] Error entity (store errors in mysql & elastic index)
  - [ ] Simple error add endpoint (accept error message & email send (bool))
  - [ ] Send error to email
  - [ ] Get error list by service name (main get form elasticsearch)

- **Log Handler**
  - [ ] Log entity (mysql, elastic)
  - [ ] Log add endpoint (log name, value)
  - [ ] Get log list by service name (main form elasticsearch)